<?php

namespace App\Console;

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
                })->dailyAt('17:00'); //매일 17시에 실행
        // })->everyMinute();
        // 매일 자정에 물리삭제 검사 실행
        $schedule->command('records:delete-old')->dailyAt('00:00'); // 매일 자정
        // ->monthlyOn(1, '00:00'); // 매달 1일 자정

        $schedule->call(function() {
            new AutoSavingRecord();
        })->daily();
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
