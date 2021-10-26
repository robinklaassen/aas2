<?php

namespace App\Http\Controllers;

use App\Location;
use App\Event;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Chart\ChartServiceInterface;

class LocationsController extends Controller
{

	public function __construct()
	{
		$this->authorizeResource(Location::class, 'location');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$locations = Location::orderBy('plaats')->get();
		return view('locations.index', compact('locations'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('locations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\LocationRequest $request)
	{
		Location::create($request->all());
		return redirect('locations')->with([
			'flash_message' => 'De locatie is aangemaakt!'
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Location $location)
	{
		$locString = $location->adres . ', ' . $location->plaats;
		return view('locations.show', compact('location', 'locString'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Location $location)
	{
		return view('locations.edit', compact('location'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Location $location, Requests\LocationRequest $request)
	{
		$location->update($request->all());
		return redirect('locations/' . $location->id)->with([
			'flash_message' => 'De locatie is bewerkt!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function delete(Location $location)
	{
		$this->authorize("delete", $location);
		return view('locations.delete', compact('location'));
	}

	public function destroy(Location $location)
	{
		$location->delete();
		return redirect('locations')->with([
			'flash_message' => 'De locatie is verwijderd!'
		]);
	}

	# Enqueteresultaten
	public function reviews(Location $location, Event $event, ChartServiceInterface $chartService)
	{
		$this->authorize("viewReviewResults", $event);
		
		$questions = collect([
			'kh-slaap',
			'kh-bijspijker',
			'kh-geheel'
		]);

		$questions->map(function ($question) use ($event, $chartService) {
			$chartService->prepareEventReviewChart($event, $question);
		});

		return view('locations.reviews', compact('location', 'event'));
	}
}
