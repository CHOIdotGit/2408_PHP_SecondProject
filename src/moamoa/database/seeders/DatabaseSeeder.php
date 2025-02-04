<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\Mission;
use App\Models\SavingDetail;
use App\Models\SavingSignUp;
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
            // ParentSeeder::class
            // ,ChildrenSeeder::class
            PointSeeder::class
            ,SavingProductSeeder::class
        ]);
        // Mission::factory(100)->create();
        // Transaction::factory(300000)->create();

        // for ($i = 0; $i < 300; $i++) { // 100개씩 300번 반복
        //     Transaction::factory(100)->create();
        // }

        // for ($i = 0; $i < 300; $i++) { // 100개씩 300번 반복
        //     Mission::factory(100)->create();
        // }
        
        // $totalRecords = 30_000; // 총 생성할 데이터 개수
        // $chunkSize = 100;      // 한 번에 생성할 데이터 개수

        // for ($i = 0; $i < ceil($totalRecords / $chunkSize); $i++) {
        //     // Transaction 데이터 생성
        //     Transaction::factory($chunkSize)->create();

        //     // Mission 데이터 생성
        //     Mission::factory($chunkSize)->create();
        // }

        // Child 모델에서 child_id가 1부터 3 사이인 데이터를 가져옴
        $children = Child::whereBetween('child_id', [1, 3])->get();
        
        // 각 child_id에 대해 SavingSignUp 데이터 생성
        foreach ($children as $child) {
            // 팩토리를 사용하여 데이터 생성
            $data = SavingSignUp::factory()->make()->toArray();
            
            // child_id 추가
            $data['child_id'] = $child->child_id;
            
            // saving_product_id에 따라 saving_sign_up_end_at 계산
            $savingPeriod = 7 * $data['saving_product_id'];
            $data['saving_sign_up_end_at'] = (new \DateTime($data['saving_sign_up_start_at']))
            ->modify("+$savingPeriod days")
            ->format('Y-m-d');
            
            // SavingSignUp 모델에 데이터 생성
            SavingSignUp::create($data);
            // SavingSignUp::factory(6)->create();
        }

        SavingDetail::factory()->create();
    }
}
