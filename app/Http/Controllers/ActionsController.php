<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Action;
use App\Http\Requests;

class ActionsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Action::class, 'action');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $actions = Action::orderBy('date')->get();

        $members = [];
        foreach (\App\Member::orderBy('voornaam')->get() as $member) {
            if ($member->points > 0) {
                $members[] = $member;
            }
        }

        return view('actions.index', compact('actions', 'members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Requests\ActionRequest $request)
    {
        Action::create($request->all());
        return redirect('actions')->with([
            'flash_message' => 'De actie is aangemaakt!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $action
     * @return Response
     */
    public function show(Action $action)
    {
        return view('actions.show', compact('action'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $action
     * @return Response
     */
    public function edit(Action $action)
    {
        return view('actions.edit', compact('action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $action
     * @return Response
     */
    public function update(Action $action, Requests\ActionRequest $request)
    {
        $action->update($request->all());
        return redirect('actions')->with([
            'flash_message' => 'De actie is bewerkt!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $action
     * @return Response
     */
    public function delete(Action $action)
    {
        $this->authorize('delete', $action);
        return view('actions.delete', compact('action'));
    }

    public function destroy(Action $action)
    {
        $action->delete();
        return redirect('actions')->with([
            'flash_message' => 'De actie is verwijderd!',
        ]);
    }
}
