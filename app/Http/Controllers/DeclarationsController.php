<?php 

namespace App\Http\Controllers;

use App\Http\Requests\BulkDeclarationsRequest;
use App\Declaration;
use App\Member;
use App\Http\Controllers\Controller;
use App\Services\DeclarationsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeclarationsController extends Controller {
	const BASE_DIR = "uploads/declarations/";

	/** @var DeclarationService */
	private $declarationService;
	public function __construct(DeclarationsService $declarationService)	{
		$this->declarationService = $declarationService;
		
        $this->authorizeResource(Declaration::class, 'declaration');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	
		$this->authorize('viewOwn', Declaration::class);
		$member = \Auth::user()->profile;
		
		$total_open = $member->declarations()->open()->where('gift',0)->sum('amount');
		
		return view('declarations.index', compact('member', 'total_open'));
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
		
		if ($image) {
			$data["original_filename"] = $image->getClientOriginalName();
			$this->declarationService->store($member, $image);
		}

		Declaration::create($data);

		return redirect('declarations')->with([
			'flash_message' => 'De declaratie is opgeslagen!'
		]);
	}

	public function file(Declaration $declaration)
	{
		$this->authorize('view', $declaration);
		
		$data = $this->declarationService->getFileFor($declaration);
		
		return response($data['file'], 200, [ 'Content-Type' => $data['type'] ]);
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
		$oldFilePath = $declaration->filename;
		$data = $request->except("image");
		if ($request->hasFile("image")) {
			/** @var UploadedFile */
			$image = $request->file("image");
			$data["original_filename"] = $image->getClientOriginalName();
			$data["filename"] = $this->declarationService->store(
				$declaration->member,
				$image
			);
		}

		$declaration->update($data);
		// delete file after update, to ensure the new file is stored and visible first
		$this->declarationService->deleteFile($oldFilePath);

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
		$member = \Auth::user()->profile;
		if ($member != $declaration->member) {
			return redirect()->back();
		}

		return view('declarations.delete', compact('declaration'));
	}
	
	public function destroy(Declaration $declaration)
	{
		$this->declarationService->deleteFileFor($declaration);
		$declaration->delete();
		return redirect('declarations')->with([
			'flash_message' => 'De declaratie is verwijderd!'
		]);
	}

	public function bulk()
	{
		$this->authorize('create', Declaration::class);
		
		return view('declarations.bulk');
	}

	public function bulkStore(BulkDeclarationsRequest $request)
	{
		$this->authorize('create', Declaration::class);
		/** @var Member */
		$member = \Auth::user()->profile;

		$dataRows = $request->input('data.*');
		foreach ($dataRows as $key => $data) {
			$data["member_id"] = $member->id;
			$image = $request->file('data.' . $key . '.file');

			if ($image) {
				$data["original_filename"] = $image->getClientOriginalName();
				$data["filename"] = $this->declarationService->store($member, $image);
			}

			Declaration::create($data);
		}
		
		$request->session()->flash(
			'flash_message', 'De declaraties zijn opgeslagen!'
		);

		return response()->json(["status" => "success"]);
	}
	
	
	public function admin()
	{
		$this->authorize('viewAll', Declaration::class);
		$members = Member::whereHas("declarations", function($q) {
			$q->whereNull('closed_at');
		})->get();
		$total_open = Declaration::open()->where('gift',0)->sum('amount');
		
		return view('declarations.admin', compact('members', 'total_open'));
	}
	
	# Confirmation of processing a members declarations
	public function confirmProcess(Member $member)
	{
		$this->authorize('process', Declaration::class);

		$declarationsQuery = $member->declarations()
						->open();

		$total = (clone $declarationsQuery)->billable()->sum('amount');
		$declarations = $declarationsQuery->get();
		
		return view('declarations.process', compact('member', 'declarations', 'total'));
	}

	# Process all declarations of a given member
	public function process(Request $request)
	{
		$this->authorize('process', Declaration::class);

		Declaration::whereIn('id', $request->get('selected'))
			->update(['closed_at' => Carbon::now()]);
		
		return redirect('declarations/admin')->with([
			'flash_message' => 'De declaraties zijn verwerkt!'
		]);
	}
}
