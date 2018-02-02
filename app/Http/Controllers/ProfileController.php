<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmailConfirmationMail;

class ProfileController extends Controller
{
    // Such protection. Very auth. Much user.
    public function __construct()
    {
        $this->middleware('auth')->except([ 'confirm' ]);
    }

    public function index() {
        return view('settings.profile');
    }

    public function update() {
        $data = request()->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if ($data['name']) {
            $user->name = $data['name'];
        }

        if ($data['email'] && $user->email != $data['email']) {
            $user->email = $data['email'];
            $user->confirmed = false;
            $user->confirmation_hash = str_random(16);
            $user->save();

            \Mail::to($user)->send(new EmailConfirmationMail($user, true));
        }

        if ($data['password']) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        return redirect()->route('home')->with('status', 'Your profile has been updated.');
    }

    public function confirm() {
        $user = User::where([ 'confirmation_hash' => request('confirmation') ])->first();

        if ($user) {
            $user->confirmed = true;
            $user->confirmation_hash = null;
            $user->save();

            auth()->login($user);
            return redirect()->route('home')->with('status', 'We have confirmed your email address!');
        }

        // If there's no such confirmation hash then we just don't deal with the request.
        return;
    }

    public function resendConfirmation() {
        $user = auth()->user();

        if ($user) {
            $user->confirmed = false;
            $user->confirmation_hash = str_random(16);
            $user->save();

            \Mail::to($user)->send(new EmailConfirmationMail($user));

            return redirect()->route('home')->with('status', 'We sent out another confirmation mail.');
        }

    }
}
