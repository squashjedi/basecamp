@component('mail::message')

Please click the button below to verify your account.

@component('mail::button', ['url' => route('verifyAccount', ["email" => $user->email, "verify_token" => $user->verify_token])])
Verify Account
@endcomponent

Regards,<br>
{{ config('app.name') }}

@endcomponent