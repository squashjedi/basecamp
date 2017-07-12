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
                    <div class="panel-title">Enter Your New Password</div>
                </div>
                <div class="panel-body">
                    @if ($isFailed)
                        <div class="alert alert-danger" role="alert">
                            Authorisation to update your password has failed!
                        </div>
                    @else
                        <basecamp-user-settings-password-reset :user="{{ Auth::user() }}"></basecamp-user-settings-password-reset>
                    @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection