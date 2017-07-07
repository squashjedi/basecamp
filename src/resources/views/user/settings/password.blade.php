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
                    <div class="panel-title">Password</div>
                </div>
                <div class="panel-body">
                    <squashjedi-basecamp-user-settings-password :user="{{ Auth::user() }}"></squashjedi-basecamp-user-settings-password>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('squashjedi/basecamp::partials.clean-facebook-url')
@endsection
