@component('mail::message')

The 6 digit code to reset your password is:
**{{ $user->passwordCode()->first()->code }}**

Regards,<br>
{{ config('app.name') }}

@endcomponent