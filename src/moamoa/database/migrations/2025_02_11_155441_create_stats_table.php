<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 기존 프로시저가 있다면 삭제
        DB::unprepared("DROP PROCEDURE IF EXISTS get_weekly_transaction_report");

        // 새로운 프로시저 생성
        DB::unprepared("
            CREATE PROCEDURE get_weekly_transaction_report(IN s_d DATE, IN e_d DATE, IN child_id INT)
            BEGIN
                DECLARE start_week INT;
                DECLARE end_week INT;

                -- 시작 주 및 종료 주 계산
                SET start_week = CAST(DATE_FORMAT(s_d, '%u') AS SIGNED);
                SET end_week = CAST(DATE_FORMAT(e_d, '%u') AS SIGNED);
                
                -- 재귀 CTE를 사용하여 주 차이 생성
                WITH RECURSIVE weeks_table AS (
                    SELECT start_week AS week
                    UNION ALL
                    SELECT week + 1 FROM weeks_table
                    WHERE week < end_week
                )
                SELECT
                    weeks_table.week AS weeks,
                    IFNULL(SUM(transactions.amount), 0) AS total
                FROM weeks_table
                LEFT JOIN (
                    SELECT
                        CAST(DATE_FORMAT(transaction_date, '%u') AS SIGNED) AS week,
                        amount
                    FROM transactions
                    WHERE transactions.transaction_date BETWEEN s_d AND e_d
                    AND transactions.child_id = child_id
                    AND deleted_at IS NULL
                ) transactions ON weeks_table.week = transactions.week
                GROUP BY weeks_table.week
                ORDER BY weeks_table.week;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_weekly_transaction_report");
    }
};
