<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\members\NewUserMember;
use App\Mail\participants\NewUserParticipant;
use App\Models\Member;
use App\Models\Participant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $memberUsers = User::where('profile_type', Member::class)->where('id', '<>', 0)->get();
        $participantUsers = User::where('profile_type', Participant::class)->get();
        return view('users.index', compact('memberUsers', 'participantUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createForMember()
    {
        $this->authorize('createMember', User::class);
        $type = 'member';
        $members = Member::orderBy('voornaam')->whereNotIn('soort', ['oud'])->get();
        $profile_options = [];
        foreach ($members as $member) {
            if (! $member->user()->count()) {
                $profile_options[$member->id] = $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam;
            }
        }

        return view('users.create', compact('type', 'profile_options'));
    }

    public function createForParticipant()
    {
        $this->authorize('createParticipant', User::class);

        $type = 'participant';
        $participants = Participant::orderBy('voornaam')->get();
        $profile_options = [];
        foreach ($participants as $participant) {
            if (! $participant->user()->count()) {
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
        $this->authorize('createMember', User::class);

        $this->validate($request, [
            'profile_id' => 'required',
            'is_admin' => 'required',
        ]);

        $member = Member::find($request->profile_id);

        // Check if user account for this member already exists!
        if ($member->user) {
            return redirect('users')->with([
                'flash_message' => 'Dit lid heeft al een gebruikersaccount!',
            ]);
        }
        // Create username
        $thename = strtolower(substr($member->voornaam, 0, 1) . str_replace(' ', '', $member->achternaam));
        $username = $thename;
        $nameList = DB::table('users')->pluck('username');
        $i = 0;
        while ($nameList->contains($username)) {
            $i++;
            $username = $thename . $i;
        }

        // Create password
        $password = User::generatePassword();

        // Attach account
        $user = new User();
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->is_admin = $request->is_admin;
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member'])->get();
        $member->roles()->sync($roles);

        // Send email
        Mail::send(new NewUserMember($member, $username, $password));

        return redirect('users')->with([
            'flash_message' => 'De gebruiker is aangemaakt!',
        ]);
    }

    public function storeForParticipant(Request $request)
    {
        $this->authorize('createParticipant', User::class);

        $this->validate($request, [
            'profile_id' => 'required',
        ]);

        $participant = Participant::find($request->profile_id);

        // Check if user account for this participant already exists!
        if ($participant->user) {
            return redirect('users')->with([
                'flash_message' => 'Deze deelnemer heeft al een gebruikersaccount!',
            ]);
        }
        // Create username
        $thename = strtolower(substr($participant->voornaam, 0, 1) . str_replace(' ', '', $participant->achternaam));
        $username = $thename;
        $nameList = DB::table('users')->pluck('username');
        $i = 0;
        while ($nameList->contains($username)) {
            $i++;
            $username = $thename . $i;
        }

        // Create password
        $password = User::generatePassword();

        // Attach account
        $user = new User();
        $user->username = $username;
        $user->password = bcrypt($password);
        $participant->user()->save($user);

        $roles = Role::whereIn('tag', ['participant'])->get();
        $participant->roles()->sync($roles);

        // Send email
        Mail::send(
            new NewUserParticipant($participant, $username, $password)
        );

        return redirect('users')->with([
            'flash_message' => 'De gebruiker is aangemaakt!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user
     * @return Response
     */
    /*
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    */

    // Grant or revoke admin access
    public function admin(User $user, Request $request)
    {
        $this->authorize('changeAdmin', $user);
        if ($request->user()->is_admin < 2) {
            return redirect('users');
        }

        return view('users.admin', compact('user'));
    }

    public function adminSave(User $user, Request $request)
    {
        $this->authorize('changeAdmin', $user);

        // Check if this user is member! (participants can never be admins)
        if ($user->isMember()) {
            $user->is_admin = $request->input('is_admin');
            $user->save();
            return redirect('users')->with([
                'flash_message' => 'Admin-rechten gewijzigd!',
            ]);
        }
        return redirect('users');
    }

    // Set new password
    public function password(User $user)
    {
        $viewType = 'admin';
        return view('users.password', compact('user', 'viewType'));
    }

    public function passwordSave(User $user, Request $request)
    {
        $this->authorize('changePassword', $user);
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect('users')->with([
            'flash_message' => 'Het nieuwe wachtwoord is ingesteld!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user
     * @return Response
     */
    public function delete(User $user)
    {
        $this->authorize('delete', $user);
        return view('users.delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect('users')->with([
            'flash_message' => 'De gebruiker is verwijderd!',
        ]);
    }
}
