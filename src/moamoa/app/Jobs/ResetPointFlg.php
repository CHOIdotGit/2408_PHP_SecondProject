<?php

namespace App\Jobs;

use App\Models\Child;

class ResetPointFlg extends MyJob
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function process()
    {
        Child::where('point_flg', '1')
                        ->update(['point_flg' => '0']);

    }
}
