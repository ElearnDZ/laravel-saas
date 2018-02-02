@component('mail::message')
# Your registration at {{ config('app.name') }}

Hi {{ $user->name }}!

We're so glad that you decided to join us! To complete your registration we ask you to
confirm your email address. You can do so quickly by pressing the button below!

@component('mail::button', ['url' => url("/confirm/{$user->confirmation_hash}")])
Confirm your email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
