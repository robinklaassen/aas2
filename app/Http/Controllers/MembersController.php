<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Course;
use App\Exports\MembersExport;
use App\Http\Requests\MemberRequest;
use App\Member;
use App\Skill;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MembersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Member::class, 'member');
    }

    // Index page
    public function index()
    {
        $current_members = Member::orderBy('voornaam', 'asc')->whereIn('soort', ['normaal', 'aspirant', 'info'])->get();
        $former_members = Member::orderBy('voornaam', 'asc')->where('soort', 'oud')->get();
        return view('members.index', compact('current_members', 'former_members'));
    }

    // Member details page
    public function show(Member $member, string $viewType = 'admin')
    {
        return view('members.show', compact('member', 'viewType'));
    }

    // TODO creating and storing new members from here is never used -- we have a registration page... consider removing

    // Create new member
    public function create()
    {
        $viewType = 'admin';
        return view('members.create', compact('viewType'));
    }

    // Store new member
    public function store(MemberRequest $request)
    {
        Member::create($request->except('roles'));
        return redirect('members')->with([
            'flash_message' => 'Het lid is aangemaakt!',
        ]);
    }

    // Edit member form
    public function edit(Member $member, string $viewType = 'admin')
    {
        return view('members.edit', compact('member', 'viewType'));
    }

    // Update member in DB
    public function update(Member $member, MemberRequest $request, string $successMessage = null)
    {
        $member->update($request->except(['skills', 'roles']));

        // Update skills
        $skills = $request->input('skills') ?? []; // this is an array with ids of existing skills (as strings!) and string tags of new skills

        $skill_ids = [];
        foreach ($skills as $skill_id) {
            $skill_ids[] = Skill::findOrCreateFromString($skill_id)->id;
        }

        $member->skills()->sync($skill_ids);

        // Update roles
        $user = $member->user()->first();
        if ($user) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect('members/' . $member->id)->with([
            'flash_message' => $successMessage ?? 'Het lid is bewerkt!',
        ]);
    }

    // Delete confirmation
    public function delete(Member $member)
    {
        $this->authorize('delete', $member);
        return view('members.delete', compact('member'));
    }

    // Remove member from database
    public function destroy(Member $member)
    {
        $member->user()->delete();
        $member->delete();
        return redirect('members')->with([
            'flash_message' => 'Het lid is verwijderd!',
        ]);
    }

    // Send member on event (form)
    public function onEvent(Member $member)
    {
        $this->authorize('onEvent', $member);
        return view('members.onEvent', compact('member'));
    }

    // Send member on event (update database)
    public function onEventSave(Request $request, Member $member)
    {
        $this->authorize('onEvent', $member);
        // Should not be attached if the member has already been sent on this event! That's why we use sync instead of attach, without detaching (second parameter false)
        $status = $member->events()->sync([$request->input('selected_event')], false);
        $message = ($status['attached'] === []) ? 'Het lid is reeds op dit evenement gestuurd!' : 'Het lid is op evenement gestuurd!';
        return redirect('members/' . $member->id)->with([
            'flash_message' => $message,
        ]);
    }

    // Add course for this member (form)
    public function addCourse(Member $member)
    {
        $this->authorize('editPractical', $member);
        $viewType = 'admin';
        return view('members.addCourse', compact('member', 'viewType'));
    }

    // Add course for this member (update database)
    public function addCourseSave(Request $request, Member $member)
    {
        $this->authorize('editPractical', $member);
        $status = $member->courses()->sync([$request->input('selected_course')], false);
        if ($status['attached'] === []) {
            $message = 'Vak reeds toegevoegd!';
        } else {
            $member->courses()->updateExistingPivot($request->input('selected_course'), [
                'klas' => $request->input('klas'),
            ]);
            $message = 'Vak toegevoegd!';
        }
        return redirect('members/' . $member->id)->with([
            'flash_message' => $message,
        ]);
    }

    // Edit course for this member (form)
    public function editCourse(Member $member, $course_id)
    {
        $this->authorize('editPractical', $member);
        $course = $member->courses->find($course_id);
        $viewType = 'admin';
        return view('members.editCourse', compact('member', 'course', 'viewType'));
    }

    // Edit course for this member (update database)
    public function editCourseSave(Request $request, Member $member, $course_id)
    {
        $this->authorize('editPractical', $member);
        $member->courses()->updateExistingPivot($course_id, [
            'klas' => $request->input('klas'),
        ]);
        return redirect('members/' . $member->id)->with([
            'flash_message' => 'Het vak is bewerkt!',
        ]);
    }

    // Remove (detach) a course from this member (form)
    public function removeCourseConfirm(Member $member, $course_id)
    {
        $this->authorize('editPractical', $member);
        $course = Course::findOrFail($course_id);
        $viewType = 'admin';
        return view('members.removeCourse', compact('member', 'course', 'viewType'));
    }

    // Remove (detach) a course from this member (update database)
    public function removeCourse(Member $member, $course_id)
    {
        $this->authorize('editPractical', $member);
        $member->courses()->detach($course_id);
        return redirect('members/' . $member->id)->with([
            'flash_message' => 'Het vak is van dit lid verwijderd!',
        ]);
    }

    // Export all members to Excel
    public function export()
    {
        return Excel::download(new MembersExport(), date('Y-m-d') . ' AAS leden.xlsx');
    }

    // Show all members on a Google Map
    public function map()
    {
        $this->authorize('viewAny', Member::class);
        $members = Member::where('soort', '<>', 'oud')->whereNotNull('geolocatie')->get();

        $markers = $members
            ->map(
                fn ($m) => [
                    'latlng' => [$m->geolocatie->getLat(), $m->geolocatie->getLng()],
                    'name' => $m->volnaam,
                    'type' => $m->soort,
                    'link' => "<a href='" . url('members', $m->id) . "'>{$m->volnaam}</a>",
                ]
            )->values();

        return view('members.map', compact('markers'));
    }

    // Search members by coverage
    // TODO method is quite long, refactor into model/service
    public function search(Request $request)
    {
        $this->authorize('viewAny', Member::class);
        $this->authorize('showPracticalAny', Member::class);

        /** @var User $user */
        $user = $request->user();

        $courses = $request->input('courses', []);
        $vog = $request->input('vog', 0);

        $courseList = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
        $courseCodes = Course::pluck('code', 'id')->toArray();

        $allMembers = Member::where('soort', '<>', 'oud');
        if ($user->can('showAdministrativeAny', Member::class)) {
            $allMembers = $allMembers->where('vog', '>=', $vog);
        }
        $allMembers = $allMembers->orderBy('voornaam')->get();

        $members = [];

        $level = [];
        foreach ($allMembers as $member) {
            $status = true;

            foreach ($courses as $course_id) {
                if ($member->courses->find($course_id) === null) {
                    $status = false;
                } else {
                    $level[$member->id][$course_id] = $member->courses->find($course_id)->pivot->klas;
                }
            }

            if ($status) {
                $members[] = $member;
            }
        }

        return view('members.search', compact('courseList', 'courseCodes', 'courses', 'vog', 'members', 'level'));
    }

    // Search members by skills
    public function searchSkills(Request $request)
    {
        $this->authorize('viewAny', Member::class);

        $skills = $request->input('skills', []);
        $require_how = $request->input('require_how', 'any');

        $all_skills = Skill::pluck('tag', 'id')->toArray();

        $num_matches = ($require_how === 'all') ? count($skills) : 1;
        $members = Member::whereHas('skills', function (Builder $query) use ($skills) {
            $query->whereIn('id', $skills);
        }, '>=', $num_matches)->get();

        return view('members.searchSkills', compact('all_skills', 'skills', 'require_how', 'members'));
    }
}
