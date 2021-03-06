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

Route::get('/settings/billing', 'BillingController@index')->name('settings.billing');
Route::patch('/settings/billing', 'BillingController@update');
Route::get('/settings/billing/cancel', 'BillingController@destroy')->name('settings.billing.cancel');
Route::get('/settings/billing/resume', 'BillingController@resume')->name('settings.billing.resume');


Route::get('/settings/billing/receipt/{receipt}', function () {
    $invoice = auth()->user()->findInvoiceOrFail(request('receipt'));

    return view('vendor.cashier.receipt', [
        'invoice' => $invoice,
        'owner' => auth()->user(),
        'product' => config('app.name'),
        'vendor' => config('company.name'),
        'street' => config('company.street'),
        'location' => config('company.location'),
        'country' => config('company.country'),
    ]);
});


Route::get('/settings/billing/invoice/{invoice}', function () {
    return request()->user()->downloadInvoice(request('invoice'), [
        'product' => config('app.name'),
        'vendor' => config('company.name'),
        'street' => config('company.street'),
        'location' => config('company.location'),
        'country' => config('company.country'),
    ]);
});

Route::post('/stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');

Auth::routes();
