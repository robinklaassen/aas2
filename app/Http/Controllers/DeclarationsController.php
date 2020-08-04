<?php namespace App\Http\Controllers;

use App\Declaration;
use App\Member;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class DeclarationsController extends Controller {
	const BASE_DIR = "uploads/declarations/";

	/** @var Filesystem */
	private $storage;

	public function __construct()
	{
		$this->storage = Storage::disk("local");
		// You need to be logged in and be a member to access
		$this->middleware('auth');
		$this->middleware('member');
		$this->middleware('admin', ['only' => ['admin', 'processConfirm', 'process']]);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	
		$member = \Auth::user()->profile;
		
		$total_open = $member->declarations()->open()->where('gift',0)->sum('amount');
		
		return view('declarations.index', compact('member', 'total_open'));
	}
	
	/**
	 *	Show the preparation form for a new declaration (file upload etc)
	*/
	public function upload()
	{
		return view('declarations.upload');
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$member = \Auth::user()->profile;
		return view('declarations.create', compact('member'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{	
		/** @var UploadedFile */
		$image = $request->file("image");	
		/** @var Member */
		$member = \Auth::user()->profile;
		$data = $request->except("image");
		
		$data["member_id"] = $member->id;
		$data["filename"] = $this->getNewFilePath($member, $image);
		$data["original_filename"] = $image->getClientOriginalName();
		$this->storage->putFileAs(
			dirname($data["filename"]),
			$image,
			basename($data["filename"])
		);
		
		Declaration::create($data);

		return redirect('declarations')->with([
			'flash_message' => 'De declaratie is opgeslagen!'
		]);
	}

	public function file(Declaration $declaration)
	{
		$file = $this->storage->get($declaration->filename);
		$type = $this->storage->mimeType($declaration->filename);
		
		return response(
			$file,
			200,
			[
				"Content-Type" => $type
			]
		);
	
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Declaration $declaration)
	{
		$member = \Auth::user()->profile;
		return view('declarations.edit', compact('declaration'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Declaration $declaration, Request $request)
	{
		$data = $request->except("image");
		if ($request->hasFile("image")) {
			/** @var UploadedFile */
			$image = $request->file("image");
			$data["filename"] = $this->getNewFilePath(
				$declaration->member, 
				$image
			);
			$data["original_filename"] = $image->getClientOriginalName();
			$this->storage->putFileAs(
				dirname($data["filename"]),
				$image,
				basename($data["filename"])
			);

			$this->storage->delete($declaration->filename);
		}


		$declaration->update($data);
		return redirect('declarations')->with([
			'flash_message' => 'De declaratie is bewerkt!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete(Declaration $declaration)
	{
		## YOU ARE ONLY ALLOWED TO DELETE YOUR OWN DECLARATIONS
		$member = \Auth::user()->profile;
		if ($member != $declaration->member) {
			return redirect()->back();
		}

		return view('declarations.delete', compact('declaration'));
	}
	
	public function destroy(Declaration $declaration)
	{
		$declaration->delete();
		return redirect('declarations')->with([
			'flash_message' => 'De declaratie is verwijderd!'
		]);
	}
	
	# Show a listing of all files
	public function showFiles()
	{
		$member = \Auth::user()->profile;
		$all_files = Storage::files('uploads/declarations/' . $member->id, true);
		
		$items = [];
		foreach ($all_files as $filename) {
			$x = filemtime('uploads/declarations/' . $member->id . '/' . $filename);
			$items[] = [
				'filename' => $filename,
				'num_declarations' => $member->declarations()->where('filename', $filename)->count(),
				'date_modified' => date('Y-m-d', $x)
			];
		}
		
		
		return view('declarations.files', compact('member', 'items'));
	}
	
	# Confirmation to delete a file
	public function fileDelete($filename)
	{
		$member = \Auth::user()->profile;
		
		// Return with error if file is used in any declaration
		$count = $member->declarations()->where('filename', $filename)->count();
		
		if ($count > 0) {
			return redirect('declarations/files')->with([
				'flash_error' => 'Dit bestand wordt gebruikt door een declaratie!'
			]);
		} else {
			return view('declarations.fileDelete', compact('filename'));
		}

	}
	
	# Actually delete a file
	public function fileDestroy($filename)
	{
		$member = \Auth::user()->profile;
		unlink('uploads/declarations/' . $member->id . '/' . $filename);
		return redirect('declarations/files')->with([
			'flash_message' => 'Het bestand is verwijderd!'
		]);
	}
	
	# Admin dashboard view
	public function admin()
	{
		$m_ids = \DB::table('declarations')->distinct()->pluck('member_id')->toArray();
		$members = [];
		foreach ($m_ids as $id) {
			$members[] = \App\Member::find($id);
		}
		
		// sort $members by first name?
		
		$total_open = Declaration::open()->where('gift',0)->sum('amount');
		
		return view('declarations.admin', compact('members', 'total_open'));
	}
	
	# Confirmation of processing a members declarations
	public function confirmProcess(Member $member)
	{
		//$total_open = $member->declarations()->open()->where('gift',0)->sum('amount');
		
		// TODO: move destination variable to global setting
		$memberFileLocation = 'uploads/declarations/' . $member->id . '/';
		$destination = 'uploads/declarations/admin/';
		if (!file_exists($destination)) {
			mkdir($destination);
		}

		// Prepare declaration overview file
		$declarations = $member->declarations()->open()->get();
		$total = $declarations->where('gift',0)->sum('amount');
		$formFilePath = $destination . date('c') . ' declaratieformulier ' . $member->volnaam . '.pdf';
		$pdf = \PDF::loadView('declarations.declarePDF', compact('member', 'declarations', 'total'))
			->setPaper('a4')
			->setWarnings(true)
			->save($formFilePath);

		//return $pdf->stream();

		// Zip declaration files together with overview
		$files = $member->declarations()
						->open()
						->select('filename')
						->distinct()
						->pluck('filename')->toArray();

		$files = array_filter($files);

		array_walk($files, function(&$item) use ($memberFileLocation) { $item = $memberFileLocation . $item; });

		$files[] = $formFilePath;

		$zipname = $destination . date('c') . ' declaratiebestanden ' . $member->volnaam . '.zip';

		$status = create_zip($files, $zipname, true);

		return view('declarations.process', compact('member', 'zipname'));
	}
	
	# Process all declarations of a given member
	public function process(Member $member)
	{
		$member->declarations()->open()->update(['closed_at' => date('Y-m-d')]);
		
		return redirect('declarations/admin')->with([
			'flash_message' => 'De declaraties zijn verwerkt!'
		]);
	}

	private function getFilePath(Member $member, string $fileName) {
		return DeclarationsController::BASE_DIR . $member->id . '/' . $fileName;
	}

	private function getNewFilePath(Member $member, UploadedFile $file) {
		return $this->getFilePath(
			$member,  
			date('YmdHis') . '_' . random_int(0, 9999) . '.' . $file->extension()
		);
	}
	
}
