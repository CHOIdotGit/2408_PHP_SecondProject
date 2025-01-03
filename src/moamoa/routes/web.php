<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildMissionController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth 관련
Route::post('/api/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/api/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/api/csrf-token', [AuthController::class, 'settingCsrfToken'])->name('auth.csrf.token');

Route::prefix('/api/auth')->group(function () {
    Route::get('/chkAccount/{account}', [AuthController::class, 'chkAccount'])->name('auth.chk.account');
    Route::post('/childRegistMatching', [AuthController::class, 'childRegistMatching'])->name('auth.child.regist.matching');
    Route::post('/storeUser', [AuthController::class, 'storeUser'])->name('auth.store.user');
});
// 

// 자녀 달력
Route::get('/api/child/calendar', [CalendarController::class, 'index']);
// 자녀 달력 모달
Route::get('/api/child/calendar', [CalendarController::class, 'show']);

// 부모 홈
Route::get('/api/parent/home', [HomeController::class, 'index']);

// 부모 미션 리스트
Route::get('/api/parent/mission/list/{id}', [MissionController::class, 'index']);

// 부모 미션 상세 페이지
Route::get('/api/parent/mission/detail/{id}', [MissionController::class, 'show']);

// 부모 미션 작성 페이지
Route::post('/api/parent/mission/create/{child_id}', [MissionController::class, 'store']);
// Route::get('/api/parent/mission/create/{child_id}', [MissionController::class, 'create']);

// 부모 미션 삭제 페이지
Route::delete('/api/parent/mission/delete/{id}', [MissionController::class, 'destroy']);

// 부모 미션 수정 페이지
Route::patch('/api/parent/mission/update/{mission_id}', [MissionController::class, 'update']);

// 부모 지출 리스트 페이지
Route::get('/api/parent/spend/list/{id}', [TransactionController::class, 'index']);

// 부모 지출 상세 페이지
Route::get('/api/parent/spend/detail/{id}', [TransactionController::class, 'show']);

// 부모 통계 페이지
Route::get('/api/parent/stats', [StatsController::class, 'index']);

//********************************************************************************************* */

// 자녀 홈
Route::get('/api/child/home', [HomeController::class, 'show']);

// 자녀 미션 리스트 페이지
Route::get('/api/child/mission/list', [ChildMissionController::class, 'index']);

// 자녀 미션 상세 페이지
Route::get('/api/child/mission/detail/{id}', [ChildMissionController::class, 'show']);

// 자녀 미션 작성 페이지
Route::post('/api/child/mission/create', [ChildMissionController::class, 'store']);

// 자녀 미션 삭제 페이지
Route::delete('/api/child/mission/delete/{id}', [ChildMissionController::class, 'destroy']);

// 자녀 미션 수정 페이지
Route::patch('/api/child/mission/update/{mission_id}', [MissionController::class, 'update']);

//********************************************************************************************* */

// 헤더 관련(드랍메뉴 자녀이름 출력)
Route::get('/api/parent/header', [HeaderController::class, 'index']);


// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
