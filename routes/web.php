<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/settings/profile', 'ProfileController@index')->name('settings.profile');
Route::patch('/settings/profile', 'ProfileController@update');
Route::get('/confirm/resend', 'ProfileController@resendConfirmation')->name('confirm.resend');
Route::get('/confirm/{confirmation}', 'ProfileController@confirm');


Route::get('/settings/billing', 'ProfileController@index')->name('settings.billing');
Route::patch('/settings/billing', 'ProfileController@update');

Route::post('/stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

Auth::routes();
