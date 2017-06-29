@extends('squashjedi/basecamp::layouts.user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Log In</div>
                </div>
                <div class="panel-body">
                    @include('squashjedi/basecamp::alert')
                    <div class="row">
                        <div class="col-md-7 border-right">

                            <form role="form" method="POST" action="{{ route('login') }}">
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

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="control-label">Password</label>
                                    <span style="float: right;">
                                        <a href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </span>
                                    <input id="password" type="password" class="form-control input-lg" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top:30px;margin-bottom:30px;">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Log In
                                    </button>
                                    <span style="padding-left:10px;padding-right:10px;">or</span>
                                    <a href="{{ route('register') }}" class="btn btn-default btn-lg">
                                        Sign Up
                                    </a>
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    Haven't received the verification email yet?<br />
                                    <a href="{{ route('resendVerification') }}">Resend the email</a>
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
