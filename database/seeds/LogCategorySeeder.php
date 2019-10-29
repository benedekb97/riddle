<?php

use App\Models\LogCategory;
use Illuminate\Database\Seeder;

class LogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LogCategory::create([
            'name' => 'API',
            'description' => ''
        ]);

        LogCategory::create([
            'name' => 'Admin',
            'description' => ''
        ]);

        LogCategory::create([
            'name' => 'Moderátor',
            'description' => ''
        ]);

        LogCategory::create([
            'name' => 'Felhasználó',
            'description' => ''
        ]);

        LogCategory::create([
            'name' => 'Riddle',
            'description' => ''
        ]);
    }
}
