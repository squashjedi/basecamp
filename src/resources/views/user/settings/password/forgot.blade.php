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
                    <div class="panel-title">Forgot Your Password?</div>
                </div>
                <div class="panel-body">

                    @include('squashjedi/basecamp::alert')
                    
                    <basecamp-settings-password-forgot :user="{{ Auth::user() }}"></basecamp-settings-password-forgot>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection