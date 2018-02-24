<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Address;
use App\Plan;
use App\Mail\EmailConfirmationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'country_code' => 'required|string|max:255',
            'vat' => 'nullable|vat_number',
            'password' => 'required|string|min:6|confirmed',
            'plan' => 'nullable|string',
            'stripe_token' => 'nullable|string',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_hash' => str_random(16),
            'trial_ends_at' => $data['stripe_token'] ? null : now()->addDays(7),
        ]);

        // This trick plays a bit dirty, but it works reliably. ¯\_(ツ)_/¯
        $userCountry = \Locale::getDisplayRegion('-'.$data['country_code'], 'en');
        $user->address()->create([
            'country_code' => $data['country_code'],
            'country' => $userCountry,
        ]);

        if ($data['stripe_token']) {
            $plan = Plan::where('name', $data['plan'])->first();

            $user->newSubscription('main', $plan->name)
                 ->create($data['stripe_token']);

        }

        \Mail::to($user)->send(new EmailConfirmationMail($user));
        
        return $user;
    }
}
