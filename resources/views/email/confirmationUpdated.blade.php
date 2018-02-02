@component('mail::message')
# Please verify your new email address

Hi {{ $user->name }}!

Somebody just updated your email address. If this was you, please click below to verify your new email
address. If this wasn't you, please ignore this email.

@component('mail::button', ['url' => url("/confirm/{$user->confirmation_hash}")])
Confirm your new email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
