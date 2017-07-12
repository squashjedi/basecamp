<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\User\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AccountController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('squashjedi/basecamp::user.settings.account');
    }

}
