<?php

namespace Squashjedi\Basecamp\App\Http\Repositories\User;

use DB;
use Auth;
use App\User;
use App\Transaction;
use Squashjedi\Basecamp\App\Http\Repositories\DbRepository;
use Illuminate\Support\Facades\Hash;

class DbUserRepository extends DbRepository implements UserRepositoryInterface {

	/**
	 * @var User
	 */
	private $model;

	function __construct(User $model)
	{
		parent::__construct($model);

		$this->model = $model;
	}

	public function getPaginate($request)
	{
        $query = User::query();

        if ($request->has('filter')) {
            $filters = explode(',', $request->input('filter'));
            foreach ($filters as $filter) {
                list($criteria, $value) = explode(':', $filter);
                if ($criteria == 'id') {
                    $query->where($criteria, $value);
                } else {
                    $query->where($criteria, 'LIKE', '%' . $value . '%');
                }
            }
        }

        if ($request->input('sort')) {
            $sortCol = $request->input('sort');
            $sortDir = starts_with($sortCol, '-') ? 'desc' : 'asc';
            $sortCol = ltrim($sortCol, '-');
        } else {
            $sortCol = 'id';
            $sortDir = 'asc';
        }
        return $query
            ->orderBy($sortCol, $sortDir)
            ->paginate($this->paginateAfter);
    }

    public function create($request)
    {
        return User::create([
                'verified' => $request->input('verified'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'verify_token' => str_random(60),
            ]);
    }

    public function update($request)
    {
        $id = $request->input('id');
        $user = User::find($id)->update([
                        'verified' => $request->input('verified'),
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'verify_token' => str_random(60),
                    ]);
        if ($request->input('password')) {
            $user = User::find($id)->update([
                'password' => bcrypt($request->input('password'))
            ]);
        }
        return $user;
    }

    public function settingsAccount($request)
    {
        $id = $request->input('id');
        $user = '';
        $user = User::find($id);
        if ($user['email'] != $request->input('email') || $user['verified'] == 0) {
            $verified = 0;
            $user->social()->delete();
        } else {
            $verified = $user['verified'];
        }
        $user = User::find($id)->update([
                        'verified' => $verified,
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'verify_token' => str_random(60),
                    ]);
        return User::find($id);
    }

}