<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Course;

class CoursesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Course::class, 'course');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $courses = Course::orderBy('naam')->get();

        $q = \DB::table('course_event_participant')->select(\DB::raw('course_id, COUNT(participant_id) AS num_participants'))->groupBy('course_id')->get();

        foreach ($q as $r) {
            $num_participants[$r->course_id] = $r->num_participants;
        }

        return view('courses.index', compact('courses', 'num_participants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Requests\CourseRequest $request)
    {
        Course::create($request->all());
        return redirect('courses')->with([
            'flash_message' => 'Het vak is aangemaakt!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $course
     * @return Response
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $course
     * @return Response
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $course
     * @return Response
     */
    public function update(Course $course, Requests\CourseRequest $request)
    {
        $course->update($request->all());
        return redirect('courses/' . $course->id)->with([
            'flash_message' => 'Het vak is bewerkt!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $course
     * @return Response
     */
    public function delete(Course $course)
    {
        $this->authorize('courses::delete', $course);
        return view('courses.delete', compact('course'));
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect('courses')->with([
            'flash_message' => 'Het vak is verwijderd!',
        ]);
    }
}
