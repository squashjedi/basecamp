<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Mail;
use Squashjedi\Basecamp\App\Mail\Verification;
use Squashjedi\Basecamp\App\Mail\Welcome;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('squashjedi/basecamp::auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verify_token' => str_random(60)
        ]);
        $this->sendEmailVerification($user);
        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect(route('login'))->with('success', 'Please check your email to verify your account.');
    }

    public function sendEmailVerification($user)
    {
        Mail::to($user['email'])->queue(new Verification($user));
    }

    public function sendEmailWelcome($user)
    {
        Mail::to($user['email'])->queue(new Welcome($user));
    }

    public function verifyAccount($email, $verify_token)
    {
        $user = User::where(['email' => $email, 'verify_token' => $verify_token])->first();
        if ($user) {
            User::where(['email' => $email, 'verify_token' => $verify_token])->update(['verified' => true]);
            $this->sendEmailWelcome($user);
            return redirect(route('login'))->with('success', 'Thanks for verifying your account. You can now log in.');
        } else {
            return redirect(route('login'))->with('danger', 'Verification Failed!');
        }
    }

    public function resendVerification()
    {
        return view('squashjedi/basecamp::auth.resend-verification');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && $user->verified == 0) {
            $this->sendEmailVerification($user);
            return redirect(route('login'))->with('success', 'Please check your email to verify your account');
        } elseif ($user && $user->verified == 1) {
            return redirect()->back()->with('info', 'This account has already been verified!');
        } else {
            return redirect()->back()->with('danger', 'We have no record of that email address!');
        }

    }
}
