@extends('squashjedi/basecamp::layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default login">
                <div class="panel-heading">
                    <div class="panel-title">Resend Verification Email</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('squashjedi/basecamp::alert')

                            <form role="form" method="POST" action="{{ route('resendVerificationEmail') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="control-label">Email</label>
                                    <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group" style="margin-top:30px;margin-bottom:0;">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Resend
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
