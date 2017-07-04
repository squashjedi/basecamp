<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\User\Settings\Password;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ForgotController extends Controller
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
    public function edit()
    {
        return view('squashjedi/basecamp::user.settings.password.forgot');
    }

}