<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => "User",
            'default' => 1,
            'priority' => 0
        ]);

        Role::create([
            'name' => "SuperAdmin",
            'default' => 0,
            'priority' => 99
        ]);
    }
}
