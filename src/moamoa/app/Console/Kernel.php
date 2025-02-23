<?php

namespace App\Console;

use App\Jobs\AutoLoginCheck;
use App\Jobs\AutoSavingRecord;
use App\Models\Mission;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    
    // 미션 지정 기간이 지나면 자동으로 상태를 취소로 바꾸는 처리 
     protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $status = Mission::where('end_at', '<', date('Y-m-d'))
                    ->where('status', 0)
                    ->update(['status' => 3]);
            Log::debug('testest');
                //})->dailyAt('17:00'); //매일 17시에 실행
                  //})->dailyAt('15:00'); //매일 15시에 실행
         })->everyMinute();


        // 매일 자정에 물리삭제 검사 실행
        $schedule->command('records:delete-old')->dailyAt('00:00'); // 매일 자정
        // ->monthlyOn(1, '00:00'); // 매달 1일 자정

        // 자동 이체 처리
        $schedule->call(function() {
            new AutoSavingRecord();
        // })->daily();
        })->dailyAt('15:00'); //매일 15시에 실행

        //로그인하면 하루 한번 포인트 지급
        // $schedule->call(function() {
        //     new AutoLoginCheck();
        // })->everyMinute();

        // 49일마다 실행 - 적금 이자율 갱신신
        // $schedule->call(function () {
        //     DB::table('saving_products')
        //         ->whereIn('saving_product_id', range(1, 7)) // pk가 1~7인 행을 대상으로 함
        //         ->where('created_at', '<=', now()->subDays(49)) // created_at이 49일 이전인 경우
        //         ->update([
        //             'saving_product_interest_rate' => DB::raw('CASE 
        //                 WHEN saving_product_id = 1 THEN ROUND(RAND() * (1.5 - 1.0) + 1.0, 1) -- 1.0 ~ 1.5 사이, 소수점 첫 번째 자리
        //                 WHEN saving_product_id = 2 THEN ROUND(RAND() * (2.5 - 1.5) + 1.5, 1) -- 1.5 ~ 2.5 사이, 소수점 첫 번째 자리
        //                 WHEN saving_product_id = 3 THEN 3.0 -- 고정값
        //                 WHEN saving_product_id = 4 THEN ROUND(RAND() * (4.5 - 3.5) + 3.5, 1) -- 3.5 ~ 4.5 사이, 소수점 첫 번째 자리
        //                 WHEN saving_product_id = 5 THEN ROUND(RAND() * (5.5 - 4.5) + 4.5, 1) -- 4.5 ~ 5.5 사이, 소수점 첫 번째 자리
        //                 WHEN saving_product_id = 6 THEN ROUND(RAND() * (6.5 - 5.5) + 5.5, 1) -- 5.5 ~ 6.5 사이, 소수점 첫 번째 자리
        //                 WHEN saving_product_id = 7 THEN ROUND(RAND() * (7.5 - 6.5) + 6.5, 1) -- 6.5 ~ 7.5 사이, 소수점 첫 번째 자리
        //                 ELSE saving_product_interest_rate
        //             END')
        //         ]);
        //     })->cron('0 0 */49 * *'); // 49일마다 실행
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
