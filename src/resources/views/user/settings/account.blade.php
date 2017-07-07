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
                    <div class="panel-title">Account</div>
                </div>
                <div class="panel-body">
                    <squashjedi-basecamp-user-settings-account :user="{{ Auth::user() }}"></squashjedi-basecamp-user-settings-account>
                </div>
                <div class="panel-footer text-center">
                    <a href="{{ route('settings.account.deactivate') }}">Deactivate My Account</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('squashjedi/basecamp::partials.clean-facebook-url')
@endsection
