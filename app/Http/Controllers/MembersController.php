<?php

namespace App\Http\Controllers;

use App\Member;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MembersExport;

class MembersController extends Controller
{

	public function __construct()
	{
		$this->authorizeResource(Member::class, 'member');
	}

	# Index page
	public function index()
	{
		$current_members = Member::orderBy('voornaam', 'asc')->whereIn('soort', ['normaal', 'aspirant', 'info'])->get();
		$former_members = Member::orderBy('voornaam', 'asc')->where('soort', 'oud')->get();
		return view('members.index', compact('current_members', 'former_members'));
		if (\Auth::user()->can("listOldMembers", Member::class)) {
			// Show standard index
			$current_members = Member::orderBy('voornaam', 'asc')->whereIn('soort', ['normaal', 'aspirant', 'info'])->get();
			$former_members = Member::orderBy('voornaam', 'asc')->where('soort', 'oud')->get();
			return view('members.index', compact('current_members', 'former_members'));
		} else {
			// Something
			$members = Member::orderBy('voornaam', 'asc')->whereIn('soort', ['normaal', 'aspirant', 'info'])->get();
			return view('members.list', compact('members'));
		}
	}

	# Member details page
	public function show(Member $member)
	{
		$viewType = 'admin';

		return view('members.show', compact('member', 'viewType'));
	}

	# Create new member
	public function create()
	{
		$viewType = 'admin';
		return view('members.create', compact('viewType'));
	}

	# Store new member
	public function store(Requests\MemberRequest $request)
	{

		Member::create($request->except("roles"));
		return redirect('members')->with([
			'flash_message' => 'Het lid is aangemaakt!'
		]);
	}

	# Edit member form
	public function edit(Member $member)
	{
		$viewType = 'admin';
		return view('members.edit', compact('member', 'viewType'));
	}

	# Update member in DB
	public function update(Member $member, Requests\MemberRequest $request)
	{
		$member->update($request->except(["skills", "roles"]));

		$member->skills()->sync($request->input("skills"));

		$user = $member->user()->first();
		if ($user) {
			$user->roles()->sync($request->input("roles"));
		}

		return redirect('members/' . $member->id)->with([
			'flash_message' => 'Het lid is bewerkt!'
		]);
	}

	# Delete confirmation
	public function delete(Member $member)
	{
		$this->authorize("delete", $member);
		return view('members.delete', compact('member'));
	}

	# Remove member from database
	public function destroy(Member $member)
	{
		$member->user()->delete();
		$member->delete();
		return redirect('members')->with([
			'flash_message' => 'Het lid is verwijderd!'
		]);
	}

	# Send member on event (form)
	public function onEvent(Member $member)
	{
		$this->authorize("onEvent", $member);
		return view('members.onEvent', compact('member'));
	}

	# Send member on event (update database)
	public function onEventSave(Member $member)
	{
		$this->authorize("onEvent", $member);
		// Should not be attached if the member has already been sent on this event! That's why we use sync instead of attach, without detaching (second parameter false)
		$status = $member->events()->sync([\Request::input('selected_event')], false);
		$message = ($status['attached'] == []) ? 'Het lid is reeds op dit evenement gestuurd!' : 'Het lid is op evenement gestuurd!';
		return redirect('members/' . $member->id)->with([
			'flash_message' => $message
		]);
	}

	# Add course for this member (form)
	public function addCourse(Member $member)
	{
		$this->authorize("editPractical", $member);
		$viewType = 'admin';
		return view('members.addCourse', compact('member', 'viewType'));
	}

	# Add course for this member (update database)
	public function addCourseSave(Member $member)
	{
		$this->authorize("editPractical", $member);
		$status = $member->courses()->sync([\Request::input('selected_course')], false);
		if ($status['attached'] == []) {
			$message = 'Vak reeds toegevoegd!';
		} else {
			$member->courses()->updateExistingPivot(\Request::input('selected_course'), ['klas' => \Request::input('klas')]);
			$message = 'Vak toegevoegd!';
		}
		return redirect('members/' . $member->id)->with([
			'flash_message' => $message
		]);
	}

	# Edit course for this member (form)
	public function editCourse(Member $member, $course_id)
	{
		$this->authorize("editPractical", $member);
		$course = $member->courses->find($course_id);
		$viewType = 'admin';
		return view('members.editCourse', compact('member', 'course', 'viewType'));
	}

	# Edit course for this member (update database)
	public function editCourseSave(Member $member, $course_id)
	{
		$this->authorize("editPractical", $member);
		$member->courses()->updateExistingPivot($course_id, ['klas' => \Request::input('klas')]);
		return redirect('members/' . $member->id)->with([
			'flash_message' => 'Het vak is bewerkt!'
		]);
	}

	# Remove (detach) a course from this member (form)
	public function removeCourseConfirm(Member $member, $course_id)
	{
		$this->authorize("editPractical", $member);
		$course = \App\Course::findOrFail($course_id);
		$viewType = 'admin';
		return view('members.removeCourse', compact('member', 'course', 'viewType'));
	}

	# Remove (detach) a course from this member (update database)
	public function removeCourse(Member $member, $course_id)
	{
		$this->authorize("editPractical", $member);
		$member->courses()->detach($course_id);
		return redirect('members/' . $member->id)->with([
			'flash_message' => 'Het vak is van dit lid verwijderd!'
		]);
	}

	# Export all members to Excel
	public function export()
	{
		return Excel::download(new MembersExport, date('Y-m-d') . ' AAS leden.xlsx');
	}

	# Show all members on a Google Map
	public function map()
	{
		$this->authorize("viewAny", Member::class);
		$members = Member::where('soort', '<>', 'oud')->orderBy('soort')->get();

		// Create member data for map
		foreach ($members as $member) {
			$markerURL = 'http://maps.google.com/mapfiles/ms/icons/';
			switch ($member->soort) {
				case 'normaal':
					$markerURL .= 'red-dot.png';
					break;
				case 'aspirant':
					$markerURL .= 'green-dot.png';
					break;
				case 'info':
					$markerURL .= 'blue-dot.png';
					break;
			}

			$memberData[] = [
				'address' => $member->adres . ', ' . $member->plaats,
				'name' => str_replace('  ', ' ', $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam),
				'markerURL' => $markerURL
			];
		}

		$memberJSON = json_encode($memberData);

		return view('members.map', compact('memberJSON'));
	}

	# Search members by coverage
	public function search(\Illuminate\Http\Request $request)
	{
		$this->authorize("viewAny", \App\Member::class);
		$this->authorize("showPracticalAny", \App\Member::class);

		$courses = $request->input('courses', []);
		$vog = $request->input('vog', 0);

		$courseList = \App\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
		$courseCodes = \App\Course::pluck('code', 'id')->toArray();

		$allMembers = \App\Member::where('soort', '<>', 'oud');
		if (\Auth::user()->can("showAdministrativeAny", \App\Member::class)) {
			$allMembers = $allMembers->where('vog', '>=', $vog);
		}
		$allMembers = $allMembers->orderBy('voornaam')->get();

		$members = [];

		$level = [];
		foreach ($allMembers as $member) {
			$status = true;

			foreach ($courses as $course_id) {
				if ($member->courses->find($course_id) == null) {
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
}
