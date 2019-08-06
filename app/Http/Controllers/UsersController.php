<?php

namespace App\Http\Controllers;

use App\User;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Mail\members\NewUserMember;
use App\Mail\participants\NewUserParticipant;

class UsersController extends Controller
{

	public function __construct()
	{
		// You need to be logged in and have admin rights to access
		$this->middleware('auth');
		$this->middleware('admin');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$memberUsers = User::where('profile_type', 'App\Member')->get();
		$participantUsers = User::where('profile_type', 'App\Participant')->get();
		return view('users.index', compact('memberUsers', 'participantUsers'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createForMember()
	{
		$type = 'member';
		$members = \App\Member::orderBy('voornaam')->whereNotIn('soort', ['oud'])->get();
		$profile_options = [];
		foreach ($members as $member) {
			if (!$member->user()->count()) {
				$profile_options[$member->id] = $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam;
			}
		}

		return view('users.create', compact('type', 'profile_options'));
	}

	public function createForParticipant()
	{
		$type = 'participant';
		$participants = \App\Participant::orderBy('voornaam')->get();
		$profile_options = [];
		foreach ($participants as $participant) {
			if (!$participant->user()->count()) {
				$profile_options[$participant->id] = $participant->voornaam . ' ' . $participant->tussenvoegsel . ' ' . $participant->achternaam;
			}
		}

		return view('users.create', compact('type', 'profile_options'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeForMember(Request $request)
	{
		$this->validate($request, [
			'profile_id' => 'required',
			'is_admin' => 'required'
		]);

		$member = \App\Member::find($request->profile_id);

		// Check if user account for this member already exists!
		if ($member->user) {
			return redirect('users')->with([
				'flash_message' => 'Dit lid heeft al een gebruikersaccount!'
			]);
		} else {
			// Create username
			$thename = strtolower(substr($member->voornaam, 0, 1) . str_replace(' ', '', $member->achternaam));
			$username = $thename;
			$nameList = \DB::table('users')->pluck('username');
			$i = 0;
			while ($nameList->contains($username)) {
				$i++;
				$username = $thename . $i;
			}

			// Create password
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password = substr(str_shuffle($chars), 0, 10);

			// Attach account
			$user = new \App\User;
			$user->username = $username;
			$user->password = bcrypt($password);
			$user->is_admin = $request->is_admin;
			$member->user()->save($user);

			// Send email
			Mail::send(new NewUserMember($member, $username, $password));

			return redirect('users')->with([
				'flash_message' => 'De gebruiker is aangemaakt!'
			]);
		}
	}

	public function storeForParticipant(Request $request)
	{
		$this->validate($request, [
			'profile_id' => 'required'
		]);

		$participant = \App\Participant::find($request->profile_id);

		// Check if user account for this participant already exists!
		if ($participant->user) {
			return redirect('users')->with([
				'flash_message' => 'Deze deelnemer heeft al een gebruikersaccount!'
			]);
		} else {
			// Create username
			$thename = strtolower(substr($participant->voornaam, 0, 1) . str_replace(' ', '', $participant->achternaam));
			$username = $thename;
			$nameList = \DB::table('users')->pluck('username');
			$i = 0;
			while ($nameList->contains($username)) {
				$i++;
				$username = $thename . $i;
			}

			// Create password
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password = substr(str_shuffle($chars), 0, 10);

			// Attach account
			$user = new \App\User;
			$user->username = $username;
			$user->password = bcrypt($password);
			$participant->user()->save($user);

			// Send email
			Mail::send(
				new NewUserParticipant($participant, $username, $password)
			);

			return redirect('users')->with([
				'flash_message' => 'De gebruiker is aangemaakt!'
			]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*
	public function show(User $user)
	{
		return view('users.show', compact('user'));
	}
	*/

	# Grant or revoke admin access
	public function admin(User $user)
	{
		if (\Auth::user()->is_admin < 2) {
			return redirect('users');
		}

		return view('users.admin', compact('user'));
	}

	public function adminSave(User $user)
	{
		// Check if this user is member! (participants can never be admins)
		if ($user->profile_type == 'App\Member') {
			$user->is_admin = \Input::get('is_admin');
			$user->save();
			return redirect('users')->with([
				'flash_message' => 'Admin-rechten gewijzigd!'
			]);
		} else {
			return redirect('users');
		}
	}

	# Set new password
	public function password(User $user)
	{
		$viewType = 'admin';
		return view('users.password', compact('user', 'viewType'));
	}

	public function passwordSave(User $user, Request $request)
	{
		$this->validate($request, [
			'password' => 'required|confirmed'
		]);

		$user->password = bcrypt(\Input::get('password'));
		$user->save();

		return redirect('users')->with([
			'flash_message' => 'Het nieuwe wachtwoord is ingesteld!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete(User $user)
	{
		return view('users.delete', compact('user'));
	}

	public function destroy(User $user)
	{
		$user->delete();
		return redirect('users')->with([
			'flash_message' => 'De gebruiker is verwijderd!'
		]);
	}

	public function privacy(User $user)
	{
		return view("pages.privacy-statement", [
			"privacy_md" => "## test\ntester"
		]);
	}
}
