<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Api\User\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface;

use Squashjedi\Basecamp\App\Http\Requests\ResetPassword;
use Squashjedi\Basecamp\App\Http\Requests\ResetPasswordForgot;
use Squashjedi\Basecamp\App\Mail\PasswordReset as MailReset;
use Squashjedi\Basecamp\App\Mail\PasswordUpdated;
use Squashjedi\Basecamp\App\PasswordReset;
use App\User;
use Hash;
use Illuminate\Support\Facades\Password;
use Notifications;
use Mail;
use Illuminate\Support\Facades\Validator;
use Squashjedi\Basecamp\App\Traits\Validation;

class PasswordController extends Controller
{
    use Validation;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Send the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $reset = PasswordReset::firstOrCreate([
                'email' => $request->email
            ], [
                'token' => str_random(60),
                'created_at' => date('Y-m-d H:i:s')
            ]);

        $user = User::find($request->input('id'));

        Mail::to($request->email)->queue(new MailReset($user));
        return Response::json([
                'success' => 'Please check your email for the password reset link.',
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $form = new ResetPassword;
        Validator::extend('password_match', function($attribute, $value, $parameters, $validator) use ($request) {
                if ( ! Hash::check($request->input('password_current'), User::where('email', $request->input('email'))->first()->password) ) {
                    return false;
                }
                return true;
            });
        $errors = $this->validation($form, $request);
        if ($errors) return $errors;

        $this->updatePasswordEmail($request);

        return Response::json([
                'success' => 'updated',
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateForgot(Request $request)
    {
        $form = new ResetPasswordForgot;
        $errors = $this->validation($form, $request);
        if ($errors) return $errors;

        $this->updatePasswordEmail($request);
        $this->deletePasswordReset($request);

        return Response::json([
                'success' => 'updated',
            ], 200);
    }

    public function updatePasswordEmail(Request $request)
    {
        $user = User::find($request->input('id'));
        $user->password = bcrypt($request->input('password_new'));
        $user->save();

        Mail::to($request->input('email'))->queue(new PasswordUpdated($user));
    }

    public function deletePasswordReset(Request $request)
    {
        return PasswordReset::where('email', $request->input('email'))->delete();
    }

}
