<?php

namespace Squashjedi\Basecamp\App\Http\Repositories\User;

use DB;
use Auth;
use App\User;
use App\Transaction;
use Squashjedi\Basecamp\App\Http\Repositories\DbRepository;
use Illuminate\Support\Facades\Hash;
use Mail;
use Squashjedi\Basecamp\App\Mail\Reactivated;

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
        $query = User::query()->withTrashed();

        if ($request->has('filter')) {
            $filters = explode(',', $request->input('filter'));
            foreach ($filters as $filter) {
                list($criteria, $value) = explode(':', $filter);
                if ($criteria == 'id') {
                    $query->where($criteria, $value);
                } else if ($criteria == 'deleted_at') {
                    if ($value == '%') {
                    } else if ($value == 0) {
                        $query->whereNull($criteria);
                    } else {
                        $query->whereNotNull($criteria);
                    }
                }else {
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
        $user = User::create([
                'verified' => $request->input('verified'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'verify_token' => str_random(60)
            ]);

        if ($request->input('deactivate') == "1") {
            User::find($user->id)->update([
                'deleted_at' => NULL
            ]);
        } else if ($request->input('deactivate') == "2") {
            User::find($user->id)->update([
                'deleted_at' => date('Y-m-d h:i:s')
            ]);
        }

        return;
    }

    public function update($request)
    {
        $id = $request->input('id');
        $user = User::withTrashed();
        $user->find($id)->update([
                        'verified' => $request->input('verified'),
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'verify_token' => str_random(60)
                    ]);
        if ($request->input('deactivate') == "1") {
            $user->find($id)->update([
                'deleted_at' => NULL
            ]);
        } else if ($request->input('deactivate') == "2") {
            $user->find($id)->update([
                'deleted_at' => date('Y-m-d h:i:s')
            ]);
        }
        if ($request->input('password')) {
            $user->find($id)->update([
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

    public function deactivateAccount($request)
    {
        $id = $request->input('id');
        return User::find($id)->delete();
    }

    public function reactivateAccount($request)
    {
        $user = User::withTrashed()->where('email', $request->input('email'))->first();
        if ($user) {
            if (Hash::check($request->input('password'), $user->password) && $user->deleted_at != null) {
                Mail::to($user->email)->queue(new Reactivated($user));
                User::withTrashed()
                    ->where('email', $request->input('email'))
                    ->update(['deleted_at' => null]);
            }
        }
        return;
    }

    public function reactivateOAuthAccount($request)
    {
        if ($user = User::withTrashed()->where('email', $request->email)->where('deleted_at', '!=', null)->first()) {
            Mail::to($user->email)->queue(new Reactivated($user));
            User::withTrashed()->where('email', $request->email)->update(['deleted_at' => null]);
        }
        return;
    }

}