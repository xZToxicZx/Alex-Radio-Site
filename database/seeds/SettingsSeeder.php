<?php

use App\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            "name" => "siteName",
            "value" => "Radio Site"
        ]);
        Settings::create([
            "name" => "paypal",
            "value" => "http://somepaypallink.com/"
        ]);
        Settings::create([
            "name" => "requestDelay",
            "value" => "20"
        ]);
        Settings::create([
            "name" => "requestAutoDJDelay",
            "value" => "30"
        ]);
        Settings::create([
            "name" => "info",
            "value" => "Put your info here."
        ]);
    }
}
