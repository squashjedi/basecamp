@component('mail::message')

Click the button below to reset your password.

@component('mail::button', ['url' => route('settings.password.reset', [$user->email, $user->passwordReset()->first()->token])])
Reset Password
@endcomponent

Regards,<br>
{{ config('app.name') }}

@endcomponent