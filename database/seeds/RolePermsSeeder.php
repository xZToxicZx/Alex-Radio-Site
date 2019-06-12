<?php

use App\role_perm;
use Illuminate\Database\Seeder;

class RolePermsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        role_perm::create([
            "name" => "all",
            "role_id" => 2
        ]);
    }
}
