<?php

namespace Squashjedi\Basecamp;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   	    DB::table('users')->delete();
    	$users = [
            [
		        'verified' => true,
		        'name' => 'Joe Bloggs',
		        'email' => 'me@example.com',
		        'password' => bcrypt('123456'),
		        'remember_token' => str_random(10),
		        'created_at' => Carbon::now(),
		        'updated_at' => Carbon::now()
			]
		];
    	DB::table('users')->insert($users);

	    factory(User::class, 999)->create();
    }
}
