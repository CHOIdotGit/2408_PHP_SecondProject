<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
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
        //     ParentSeeder::class
        //     ,ChildrenSeeder::class
        // ]);
        // Mission::factory(1000)->create();
        // Transaction::factory(300000)->create();

        // for ($i = 0; $i < 300; $i++) { // 1000개씩 300번 반복
        //     Transaction::factory(1000)->create();
        // }

        for ($i = 0; $i < 300; $i++) { // 1000개씩 300번 반복
            Mission::factory(1000)->create();
        }
    }
}
