<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $plans = Plan::all();

        return view('settings.billing', compact('plans'));
    }

    public function update() {
        $user = auth()->user();

        // This gets called when you update your credit card through Stripe.
        if (request('stripeCardToken')) {
            $user->updateCard(request('stripeCardToken'));
            return back()->with('status', 'We have updated your credit card.');
        }

        // This gets called if you change your plan to a different one.
        if (request('plan') !== $user->subscription('main')->stripe_plan) {
            $plan = Plan::where([ 'name' => request('plan') ])->first();
            $user->subscription('main')->skipTrial()->swap($plan->name); 
            
            return back()->with('status', "We have updated your plan to '{$plan->pretty_name}'.");
        }
    }

    public function destroy() {
        auth()->user()->subscription('main')->cancel();
        return redirect()->route('settings.billing')->with('status', "We've canceled your subscription. We hope to see you again soon!");
    }

    public function resume() {
        auth()->user()->subscription('main')->resume();
        return redirect()->route('settings.billing')->with('status', "We've re-activated your subscription! Welcome back!");
    }
}
