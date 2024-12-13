<?php

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
// Route::post('/parents/mission/list', )

Route::get('/api/parents/home', [ParentController::class, 'index']);

// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
