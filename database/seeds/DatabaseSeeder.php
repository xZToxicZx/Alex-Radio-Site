<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([RoleSeeder::class, SettingsSeeder::class, RolePermsSeeder::class]);

        User::create([
            'name' => 'SuperUser',
            'email' => 'superuser@test.com',
            'password' => Hash::make('admin'),
            'role_id' => 2
        ]);
    }
}
