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
        return;
    }
}
