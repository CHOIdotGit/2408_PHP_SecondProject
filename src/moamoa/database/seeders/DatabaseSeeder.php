<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Mission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // $this->call([
        //     FamilyCodeSeeder::class
        //     ,UserSeeder::class
        //     ,IncomeCategorySeeder::class
        //     ,MissionCategorySeeder::class
        // ]);
        Income::factory(50)->create();
        Mission::factory(50)->create();
    }
}
