<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Mission;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();


        $calendarData = Child::select('children.name', 'children.nick_name')
                            ->where('children.parent_id', 1)
                            ->first();
                            
        $sidebarTraffic = Transaction::where([
                                                ['transaction_date' , '>=' , '$startOfMonth']
                                                ,['transaction_date' , '<=' , '$endOfMonth']
                                                ,['category',0]
                                                ,['parent_id', 1]
                                            ]) ->sum('amount');
        
        $sidebarMeal = Transaction::where([
                                            ['transaction_date' , '>=' , '2024-12-01']
                                            ,['transaction_date' , '<=' , '2024-12-31']
                                            ,['category',1]
                                            ,['parent_id', 1]
                                        ]) ->sum('amount');  
                            

        $sidebarShopping = Transaction:: where([
                                                ['transaction_date' , '>=' , '2024-12-01']
                                                ,['transaction_date' , '<=' , '2024-12-31']
                                                ,['category',2]
                                                ,['parent_id', 1]
                                            ])->sum('amount');

        $sidebarEtc = Transaction:: where([
                                            ['transaction_date' , '>=' , '2024-12-01']
                                            ,['transaction_date' , '<=' , '2024-12-31']
                                            ,['category',3]
                                            ,['parent_id', 1]
                                        ])->sum('amount');   

        $sidebarMission = Mission::where([
                                            ['start_at', '>=' , '2024-12-01']
                                            ,['end_at', '<=' , '2024-12-31']
                                            ,['parent_id', 1]
                                        ])->sum('amount');

                        

        return response()->json([
            'success' => true,
            'msg' => '캘린더 이름 획득 성공',
            'calendarData' => $calendarData,
            'sidebarMeal' => $sidebarMeal,
            'sidebarTraffic' => $sidebarTraffic,
            'sidebarShopping' => $sidebarShopping,
            'sidebarEtc' => $sidebarEtc,
            'sidebarMission' => $sidebarMission,
        ], 200);
    }

}