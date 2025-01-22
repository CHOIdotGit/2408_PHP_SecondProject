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
        // Mission::factory(100)->create();
        // Transaction::factory(300000)->create();

        // for ($i = 0; $i < 300; $i++) { // 100개씩 300번 반복
        //     Transaction::factory(100)->create();
        // }

        // for ($i = 0; $i < 300; $i++) { // 100개씩 300번 반복
        //     Mission::factory(100)->create();
        // }
        
        $totalRecords = 30_000; // 총 생성할 데이터 개수
        $chunkSize = 100;      // 한 번에 생성할 데이터 개수

        for ($i = 0; $i < ceil($totalRecords / $chunkSize); $i++) {
            // Transaction 데이터 생성
            Transaction::factory($chunkSize)->create();

            // Mission 데이터 생성
            Mission::factory($chunkSize)->create();
        }
    }
}
