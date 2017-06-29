<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Api\User\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface;

use Squashjedi\Basecamp\App\Http\Requests\ResetPassword;
use Squashjedi\Basecamp\App\Http\Requests\ResetPasswordModal;
use Squashjedi\Basecamp\App\Mail\PasswordCode as EmailCode;
use Squashjedi\Basecamp\App\Mail\PasswordUpdated;
use Squashjedi\Basecamp\App\PasswordCode;
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
    public function sendModal(Request $request)
    {
        $reset = PasswordCode::firstOrCreate([
                'email' => $request->email
            ], [
                'code' => sprintf("%06d", mt_rand(1, 999999)),
                'created_at' => date('Y-m-d H:i:s')
            ]);

        $user = User::find($request->input('id'));
        Mail::to($request->email)->queue(new EmailCode($user));
        return Response::json([
                'success' => 'code',
            ], 200);
    }

    /**
     * Send the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function codeModal(Request $request)
    {
        if ($request->input('code') == PasswordCode::where('email', $request->input('email'))->first()->code) {
            PasswordCode::where('code', $request->input('code'))->delete();
            return Response::json([
                    'success' => 'password',
                ], 200);
        }
        return Response::json([
                'errors' => 'errors',
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateModal(Request $request)
    {
        $form = new ResetPasswordModal;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $form = new ResetPassword;
        Validator::extend('password_match', function($attribute, $value, $parameters, $validator) use ($request) {
                if ( ! Hash::check($request->input('passwordCurrent'), User::where('email', $request->input('email'))->first()->password) )
                {
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

    public function updatePasswordEmail(Request $request)
    {
        $user = User::find($request->input('id'));
        $user->password = bcrypt($request->input('passwordNew'));
        $user->save();

        Mail::to($request->input('email'))->queue(new PasswordUpdated($user));
    }

}
