<?php

namespace Squashjedi\Basecamp;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;
use App\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   	    DB::table('roles')->delete();
    	$roles = [
            [
		        'user_id' => 1,
		        'role' => 'webmaster',
		        'created_at' => Carbon::now(),
		        'updated_at' => Carbon::now()
			]
		];
    	DB::table('roles')->insert($roles);
    }
}
