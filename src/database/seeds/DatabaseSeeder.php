<?php

namespace Squashjedi\Basecamp;

use Illuminate\Database\Seeder;
use Squashjedi\Basecamp\UsersTableSeeder;
use Squashjedi\Basecamp\RolesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
