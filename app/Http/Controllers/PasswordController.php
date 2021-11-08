<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// The PasswordController handles an external password reset. Changing your password when logged in is handled by the UserController.
class PasswordController extends Controller
{
    public function __construct()
    {
    }

    // Forgot password form
    public function forgot()
    {
        return view('auth.password');
    }

    // Reset password action
    public function reset(Request $request)
    {
        // Validate input
        $this->validate($request, [
            'username' => 'required|exists:users,username',
            'geboortedatum' => 'required|regex:/\d{4}-\d{2}-\d{2}/',
        ]);

        // Find user profile, check birth date
        $user = \App\Models\User::where('username', '=', $request->username)->first();
        $profile = $user->profile;
        $type = ($user->profile_type === 'App\Models\Member') ? 'member' : 'participant';

        if ($request->geboortedatum !== $profile->geboortedatum->toDateString()) {
            // Redirect back to form
            return redirect()->back()->with([
                'flash_error' => 'De ingevoerde geboortedatum komt niet overeen met die van het te resetten account.',
            ]);
        }
        // Reset password
        $password = User::generatePassword();
        $user->password = bcrypt($password);
        $user->save();

        // Send email
        Mail::send(
            new ResetPassword($user, $password)
        );

        // Return to login page with success message
        return redirect('/')->with([
            'flash_message' => 'Je wachtwoord is gereset en gemaild!',
        ]);
    }
}
