<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->name = "lockdown";
        $setting->description = "If site is on lockdown only moderators and admins can view anything";
        $setting->setting = "false";
        $setting->save();
    }
}
