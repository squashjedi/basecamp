<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\User\Settings\Password\Forgot;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;

class ResetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['web']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($email, $token)
    {
        if (!Auth::user()->passwordReset()->first()->where(['email' => $email, 'token' => $token])->first()) {
            $isFailed = true;
        } else {
            $isFailed = false;
        }
        return view('squashjedi/basecamp::user.settings.password.forgot.reset', ['isFailed' => $isFailed]);
    }

}