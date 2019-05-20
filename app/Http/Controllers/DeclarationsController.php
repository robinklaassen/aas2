<?php namespace App\Http\Controllers;

use App\Declaration;
use App\Member;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;

use Illuminate\Http\Request;

class DeclarationsController extends Controller {

	public function __construct()
	{
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
		
		$uploaded_files = [];
		if ($request->hasFile('files')) {
			$files = $request->file('files');
			foreach ($files as $file) {
				if ($file->isValid()) {
					// Upload to user folder
					$dest = 'uploads/declarations/' . $member->id;
					$fname = $file->getClientOriginalName();
					
					if (file_exists($dest . '/' . $fname)) {
						return redirect()->back()->with(['flash_error' => 'Fout: één van de geüploade bestanden bestaat reeds!']);
					} else {
						$file->move($dest, $fname);
					}

					// Add to array
					$uploaded_files[] = $fname;
				}
			}
		} else {
			$uploaded_files[] = "";
		}

		// Get current list of files for user
		$all_files = array_diff(scandir('uploads/declarations/' . $member->id), ['..', '.']);
		$all_files = array_combine($all_files, $all_files);
		
		return view('declarations.create', compact('uploaded_files', 'all_files', 'member'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{		
		$member = \Auth::user()->profile;
		
		// Loop through rows
		foreach ($request->get('filename') as $key => $filename) {
			
			
			# VALIDATION STILL REQUIRED!
			if ($filename == "") {$filename = null;}
			$record = [
				'filename' => $filename,
				'date' => $request->get('date')[$key],
				'member_id' => $member->id,
				'description' => $request->get('description')[$key],
				'amount' => $request->get('amount')[$key],
				'gift' => $request->get('gift')[$key]
			];
			
			Declaration::create($record);
		}

		return redirect('declarations')->with([
			'flash_message' => 'De declaratie(s) is/zijn opgeslagen!'
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Declaration $declaration)
	{
		## YOU ARE ONLY ALLOWED TO EDIT YOUR OWN DECLARATIONS
		$member = \Auth::user()->profile;
		if ($member != $declaration->member) {
			return redirect()->back();
		}

		$all_files = array_diff(scandir('uploads/declarations/' . $member->id), ['..', '.']);
		$all_files = array_combine($all_files, $all_files);
		$all_files[""] = "-";
		return view('declarations.edit', compact('declaration', 'all_files'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Declaration $declaration, Request $request)
	{
		$declaration->update($request->all());
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
		$all_files = array_diff(scandir('uploads/declarations/' . $member->id), ['..', '.']);
		
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
	
}
