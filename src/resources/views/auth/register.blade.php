@extends('squashjedi/basecamp::layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Sign Up</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7 border-right">
                            <form role="form" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="control-label">Name</label>
                                    <input id="name" type="text" class="form-control input-lg" name="name" value="{{ old('name') }}" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="control-label">Email</label>
                                    <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control input-lg" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="control-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control input-lg" name="password_confirmation" required>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    By signing up you agree with our terms and privacy policy.
                                </div>

                                <div class="form-group" style="margin-top:30px;margin-bottom:0;">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Sign Up
                                    </button>
                                    <span style="padding-left:10px;padding-right:10px;">or</span>
                                    <a href="{{ route('login') }}" class="btn btn-default btn-lg">
                                        Log In
                                    </a>
                                </div>

                            </form>
                        </div>
                        <div class="col-md-5">
                            <div class="signup-controls">
                                <a href="/auth/facebook" class="btn btn-facebook btn-block btn-lg"><i class="fa fa-facebook-square fa-lg" aria-hidden="true"></i> Log in with Facebook</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
