<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\MissionController;
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
    Route::post('/saveRegistInfo', [AuthController::class, 'saveRegistInfo'])->name('auth.save.regist.info');
});
// 

// 자녀 달력
Route::get('/api/child/calendar', [CalendarController::class, 'index']);

// 부모 홈
Route::get('/api/parent/home', [MissionController::class, 'index']);

// 부모 미션 리스트
Route::get('/api/parent/mission/list/{id}', [MissionController::class, 'index']);

// 부모 미션 상세 페이지
Route::get('/api/parent/detail/{id}', [MissionController::class, 'show']);

// 부모 미션 작성 페이지
Route::post('/api/parent/mission/create', [MissionController::class, 'store']);

// 부모 지출 리스트 페이지
Route::get('/api/parent/spend/list', [TransactionController::class, 'index']);

// 자녀 미션 리스트 페이지
Route::get('/api/child/mission/list', [MissionController::class, 'index']);

// 헤더 관련(드랍메뉴 자녀이름 출력)
Route::get('/api/parent/header', [HeaderController::class, 'index']);

// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
