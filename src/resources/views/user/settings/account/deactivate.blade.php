@extends('squashjedi/basecamp::layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('squashjedi/basecamp::user.settings.partials.sidemenu')
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Is This Goodbye?</div>
                </div>
                <div class="panel-body">
                    <ul>
                        <li>We will only retain your user data for 30 days and then it will be permanently deleted. You can reactivate your account at any point within 30 days of deactivation by logging back in.</li>
                        <li>You don't need to deactivate your account to change your name or email address. You can change it in the <a href="{{ route('settings.account') }}">account page</a>.</li>
                        <li>If you want to use this account's email address on another account, change it before you deactivate. The email will not be available for use until the user data has been permanently deleted.</li>
                        <li>Your account should be removed within a few minutes, but some content may be viewable for a few days after deactivation.</li>
                        <li>We have no control over content indexed by search engines like Google.</li>
                    </ul>
                </div>
                <div class="panel-footer">
                    <squashjedi-basecamp-user-settings-account-deactivate
                        :linkaccount="{{ json_encode(route('settings.account')) }}"
                        :linkforgot="{{ json_encode(route('settings.password.forgot')) }}"
                        :user="{{ json_encode(Auth::user()) }}"></squashjedi-basecamp-user-settings-account-deactivate>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection