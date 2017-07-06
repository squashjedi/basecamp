<?php

namespace Squashjedi\Basecamp\App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface;

use Squashjedi\Basecamp\App\Http\Requests\UserForm;
use Squashjedi\Basecamp\App\Http\Requests\UserFormEdit;
use App\User;
use Squashjedi\Basecamp\App\Traits\Validation;

class UsersController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->userRepo->getPaginate($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = new UserForm;
        $errors = $this->validation($form, $request);
        if ($errors) return $errors;

        $user = $this->userRepo->create($request);

        return Response::json([
                'success' => 'New User created.'
            ], 200);
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

        $user = $this->userRepo->update($request);

        return Response::json([
                'success' => 'User updated.'
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ids)
    {
        $ids = $this->userRepo->destroy($ids);
        $grammar = count($ids) == 1 ? 'User' : 'Users';

        return Response::json([
                'success' => $grammar . ' deleted.'
            ], 200);
    }

}
