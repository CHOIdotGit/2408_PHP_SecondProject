<?php

namespace Database\Seeders;

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
        $this->call([
            ParentSeeder::class
            ,ChildrenSeeder::class
        ]);
        Transaction::factory(50)->create();
        Mission::factory(50)->create();
    }
}
