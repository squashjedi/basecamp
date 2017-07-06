<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Api\User\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface;

use Squashjedi\Basecamp\App\Http\Requests\UserForm;
use Squashjedi\Basecamp\App\Http\Requests\UserFormEdit;
use Squashjedi\Basecamp\App\Mail\Verification;
use App\User;
use Mail;
use Squashjedi\Basecamp\App\Traits\Validation;


class AccountController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form = new UserFormEdit;
        $errors = $this->validation($form, $request);
        if ($errors) return $errors;

        $user = $this->userRepo->settingsAccount($request);

        if ($user->verified == false) {
            Mail::to($user->email)->queue(new Verification($user));
            return Response::json([
                    'success' => 'Please check your email to verify your account! ',
                    'logout' => true
                ], 200);
        } else {
            return Response::json([
                    'success' => 'Your account has been updated!',
                    'logout' => false
                ], 200);
        }
    }

}
