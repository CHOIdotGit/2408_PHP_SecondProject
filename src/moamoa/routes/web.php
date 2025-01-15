<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildMissionController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TransactionChildrenController;
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
    Route::post('/parentInfo', [AuthController::class, 'parentInfo'])->name('auth.parent.info');
    Route::post('/childInfo', [AuthController::class, 'childInfo'])->name('auth.child.info');
    Route::post('/modifyUser', [AuthController::class, 'modifyUser'])->name('auth.modify.user');
    Route::post('/removeUser', [AuthController::class, 'removeUser'])->name('auth.remove.user');
});
// 

// ******************************
// *         부모 관련           *
// ******************************
// 부모 홈
Route::get('/api/parent/home', [HomeController::class, 'index']);


// 부모 미션 리스트
Route::get('/api/parent/mission/list/{id}', [MissionController::class, 'index']);

// 부모 미션 상세 페이지
Route::get('/api/parent/mission/detail/{id}', [MissionController::class, 'show']);
Route::post('/api/parent/mission/approval', [MissionController::class, 'approvalMission']);

// 부모 미션 작성 페이지
Route::post('/api/parent/mission/create/{child_id}', [MissionController::class, 'store']);
// Route::get('/api/parent/mission/create/{child_id}', [MissionController::class, 'create']);

// 부모 미션 삭제 페이지
Route::delete('/api/parent/mission/delete/{id}', [MissionController::class, 'destroy']);

// 부모 선택된 미션 삭제
Route::delete('/api/parent/mission/list/checked/delete', [MissionController::class, 'checkedDestroy']);

// 부모 미션 수정 페이지
Route::patch('/api/parent/mission/update/{mission_id}', [MissionController::class, 'update']);

// 부모 지출 리스트 페이지
Route::get('/api/parent/spend/list/{id}', [TransactionController::class, 'index']);

// 부모 지출 상세 페이지
Route::get('/api/parent/spend/detail/{id}', [TransactionController::class, 'show']);

// 부모 통계 페이지
Route::get('/api/parent/stats/{child_id}', [StatsController::class, 'index']);


// ******************************
// *         자녀 관련           *
// ******************************
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

// 자녀 선택된 미션 삭제
Route::delete('/api/child/mission/list/checked/delete', [childMissionController::class, 'checkedDestroy']);

// 자녀 미션 수정 페이지
Route::patch('/api/child/mission/update/{mission_id}', [MissionController::class, 'update']);

// 자녀 지출 리스트 페이지
Route::get('/api/child/spend/list', [TransactionChildrenController::class, 'index']);

// 자녀 지출 상세 페이지
Route::get('/api/child/spend/detail/{id}', [TransactionChildrenController::class, 'show']);

// 자녀 지출 작성 페이지
Route::post('/api/child/spend/create', [TransactionChildrenController::class, 'store']);

// 자녀 지출 삭제 페이지
Route::delete('/api/child/spend/delete/{id}', [TransactionChildrenController::class, 'destroy']);

// 자녀 선택된 지출 삭제
Route::delete('/api/child/spend/list/checked/delete', [TransactionChildrenController::class, 'checkedDestroy']);

// 자녀 지출 수정 페이지
Route::patch('/api/child/spend/update/{transaction_id}', [TransactionChildrenController::class, 'update']);


// ******************************
// *         헤더 관련           *
// ******************************
// 헤더 관련(드랍메뉴 자녀이름 출력)
Route::get('/api/parent/header', [HeaderController::class, 'index']);

// 헤더 관련(미션/지출 등록/승인 알람)
Route::get('/api/parent/header/bell', [HeaderController::class, 'bellList']);


// ******************************
// *         달력 관련           *
// ******************************
// 자녀 달력
Route::get('/api/child/calendar', [CalendarController::class, 'childIndex']);


// 부모 달력
Route::get('/api/parent/calendar/{id}', [CalendarController::class, 'parentIndex']);

// 자녀 달력 모달
Route::get('/api/child/calendar/modal', [ModalController::class, 'show']);

// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
