<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\EventPackage;
use Illuminate\Http\Request;

class EventPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventPackagesGrouped = EventPackage::all()->groupBy('type');

        return view('event-packages.index', compact('eventPackagesGrouped'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('event-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        EventPackage::create($request->all());
        return redirect('event-packages')->with([
            'flash_message' => 'Het pakket is aangemaakt!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(EventPackage $eventPackage)
    {
        return view('event-packages.show', compact('eventPackage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(EventPackage $eventPackage)
    {
        return view('event-packages.edit', compact('eventPackage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventPackage $eventPackage)
    {
        $eventPackage->update($request->all());
        return redirect('event-packages/' . $eventPackage->id)->with([
            'flash_message' => 'Het pakket is bewerkt!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $eventPackage
     * @return Response
     */
    public function delete(EventPackage $eventPackage)
    {
        return view('event-packages.delete', compact('eventPackage'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventPackage $eventPackage)
    {
        $eventPackage->delete();
        return redirect('event-packages')->with([
            'flash_message' => 'Het pakket is verwijderd!',
        ]);
    }
}
