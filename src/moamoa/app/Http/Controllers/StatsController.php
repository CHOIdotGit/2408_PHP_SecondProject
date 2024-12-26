<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        // 요청 데이터 유효성 검사
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:'.date('Y'),
            'month' => 'required|integer|min:1|max:12'
        ]);

        // Carbon을 사용하여 해당 월의 시작과 끝 날짜 설정
        $targetDate = Carbon::createFromDate($validated['year'], $validated['month'], 1);

        $startDate = $targetDate->startOfMonth()->format('Y-m-d');
        $endDate = $targetDate->endOfMonth()->format('Y-m-d');

        // 가장 큰 지출 내역 조회
        $biggestUse = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
                                ->orderBy('amount', 'DESC') // 내림차순 정렬
                                ->first();

        // 결과 반환
        return response()->json([
            'success' => true,
            'msg' => '통계 성공',
            'biggestUse' => $biggestUse
        ], 200);
    }
}
