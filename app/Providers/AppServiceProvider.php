<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Plan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (\App::runningInConsole()) {
            return;
        }

        view()->share('plans', Plan::all());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
