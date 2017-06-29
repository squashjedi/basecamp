<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Socialite;
use Mail;
use Squashjedi\Basecamp\App\Mail\Welcome;
use Squashjedi\Basecamp\App\User;
use Squashjedi\Basecamp\App\Social;

class OAuthController extends Controller
{
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
        $this->middleware('guest');
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    public function sendEmailWelcome($user)
    {
        Mail::to($user['email'])->queue(new Welcome($user));
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where(['email' => $user->email])->first();
        if ($authUser) {
            $authSocial = Social::where(['user_id' => $authUser->id, 'provider' => $provider])->first();
            if (!$authSocial) {
                $authSocial = $authUser->social()->create([
                    'provider' => $provider,
                    'provider_id' => $user->id
                ]);
            }
            if ($authUser->verified == false) {
                User::where(['email' => $user->email])->update(['verified' => true]);
            }
            return $authUser;
        }

        $newUser = User::create([
        	'verified' => true,
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt(str_random(20))
        ]);
        $userSocial = $newUser->social()->create([
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
        $this->sendEmailWelcome($newUser);
        return $newUser;
    }
}
