<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildInfoController;
use App\Http\Controllers\ParentController;
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
Route::get('/api/csrf-token', [AuthController::class, 'settingCsrfToken']);
Route::post('/api/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/api/logout', [AuthController::class, 'logout'])->name('auth.logout');
// 

// Route::post('/parents/mission/list', )

Route::get('/api/parents/home', [ParentController::class, 'index']);
Route::get('/api/parents/mission/list', [ParentController::class, 'index']);
Route::get('/api/child/calendar', [CalendarController::class, 'index']);
// Route::get('/api/child/calendar/{id}', [CalendarController::class, 'show']);

//부모 미션 리스트
Route::get('/api/parents/mission/list', [ParentController::class, 'index']);

//부모 미션 상세 페이지
Route::get('/api/parents/detail/{id}', [ParentController::class, 'show']);

//부모 미션 작성 페이지
Route::post('/api/parents/mission/create', [ParentController::class, 'store']);

Route::get('/api/parents/mission/list', [ChildInfoController::class, 'index']);

// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
