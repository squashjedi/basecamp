<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Api\User\Settings\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface;

use Squashjedi\Basecamp\App\Mail\Deactivated;
use App\User;
use Mail;
use Hash;
use Squashjedi\Basecamp\App\Traits\Validation;


class DeactivateController extends Controller
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
    public function update(Request $request)
    {
        if (! Hash::check($request->input('password'), User::find($request->input('id'))->password)) {
            return '{"errors": {"password": "Incorrect password"}}';
        }

        $user = $this->userRepo->deactivateAccount($request);
        $user = User::withTrashed()->find($request->input('id'));

        Mail::to($user->email)->queue(new Deactivated($user));
        return Response::json([
                'success' => 'Your account has been deactivated! '
            ], 200);
    }

}