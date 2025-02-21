<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChildMissionController;
use App\Http\Controllers\ChildPointController;
use App\Http\Controllers\ChildSavingController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\ParentPointController;
use App\Http\Controllers\ParentSavingController;
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
Route::get('/api/check-session', [AuthController::class, 'checkSession'])->name('auth.check.session');

Route::prefix('/api/auth')->group(function () {
    Route::get('/chkAccount/{account}', [AuthController::class, 'chkAccount'])->name('auth.chk.account');
    Route::get('/chkEmail/{email}', [AuthController::class, 'chkEmail'])->name('auth.chk.email');
    Route::post('/sendEmail', [AuthController::class, 'sendEmail'])->name('auth.send.email');
    Route::get('/chkFamCode/{famCode}', [AuthController::class, 'chkFamCode'])->name('auth.chk.famCode');
    Route::post('/matchingParent', [AuthController::class, 'matchingParent'])->name('auth.matching.parent');
    Route::post('/storeUser', [AuthController::class, 'storeUser'])->name('auth.store.user');
    Route::post('/parentInfo', [AuthController::class, 'parentInfo'])->name('auth.parent.info');
    Route::post('/childInfo', [AuthController::class, 'childInfo'])->name('auth.child.info');
    Route::post('/identUser', [AuthController::class, 'identUser'])->name('auth.ident.user');
    Route::post('/modifyUser', [AuthController::class, 'modifyUser'])->name('auth.modify.user');
    Route::post('/removeUser', [AuthController::class, 'removeUser'])->name('auth.remove.user');
    Route::post('/applyChild', [AuthController::class, 'applyChild'])->name('auth.apply.child');
    Route::post('/deleteChild', [AuthController::class, 'deleteChild'])->name('auth.delete.child');
    Route::post('/findUser', [AuthController::class, 'findUser'])->name('auth.find.user');
    Route::post('/userInfo', [AuthController::class, 'userInfo'])->name('auth.user.info');
    Route::post('/resetPassword', [AuthController::class, 'resetPassword'])->name('auth.reset.password');
    Route::post('/chkInfo', [AuthController::class, 'chkInfo'])->name('auth.chk.info');
    
    // --------------------------- V001 del start -----------------------------
    // Route::post('/changePassword', [AuthController::class, 'changePassword'])->name('auth.change.password');
    // Route::post('/childManyInfo', [AuthController::class, 'childManyInfo'])->name('auth.child.many.info');
    // Route::post('/childReMatching', [AuthController::class, 'childRegistMatching'])->name('auth.child.matching');
    // Route::post('/modifyReMatching', [AuthController::class, 'modifyReMatching'])->name('auth.modify.re.matching');
    // --------------------------- V001 del end -------------------------------
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
Route::get('/api/parent/mission/detail/{mission_id}', [MissionController::class, 'show']);
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

// 자녀 미션 완료
Route::post('/api/child/mission/complete', [ChildMissionController::class, 'completeChildMission']);

// ******************************
// *         검색 관련           *
// ******************************
// 부모 미션 리스트 검색
Route::get('/api/mission/search', [MissionController::class, 'search']);

// 부모 지출 리스트 검색
Route::get('/api/transaction/search', [TransactionController::class, 'search']);

// 자녀 지출 리스트 검색
Route::get('/api/child/transaction/search', [TransactionChildrenController::class, 'search']);

// 자녀 미션 리스트 검색
Route::get('/api/child/mission/search', [ChildMissionController::class, 'search']);

// ******************************
// *         헤더 관련           *
// ******************************
// 헤더 관련(드랍메뉴 자녀이름 출력)
Route::get('/api/parent/header', [HeaderController::class, 'index']);

// 헤더 관련(미션/지출 등록/승인 알람)
Route::get('/api/parent/header/bell', [HeaderController::class, 'bellList']);

// 헤더 관련(미션/지출 등록/승인 알람) --- to do : 나중에 부모랑 합치기
Route::get('/api/child/header/bell', [HeaderController::class, 'childBellList']);

// 헤더 관련(미션/지출 등록/승인 알람 확인)
Route::patch('/api/parent/header/bell/check/{mission_id}', [HeaderController::class, 'update']);

// 헤더 관련(자녀 로그인 시 자녀 프로필 출력)
Route::get('/api/child/info', [HeaderController::class, 'childInfo']);

// 헤더 관련('자녀 선택-셀렉트 박스)
// Route::get('/api/parent/moabank/{child_id}', [HeaderController::class, 'selectChild']);


// ******************************
// *         달력 관련           *
// ******************************
// 자녀 달력
Route::get('/api/child/calendar', [CalendarController::class, 'childIndex']);

// 자녀 달력 모달
Route::get('/api/child/calendar/modal', [ModalController::class, 'show']);

// 부모 달력
Route::get('/api/parent/calendar/{id}', [CalendarController::class, 'parentIndex']);

Route::get('/api/parent/calendar/modal/{id}', [ModalController::class, 'index']);


// ******************************
// *         은행 관련           *
// ******************************
// 한국은행 open api 
Route::get('/api/koreabank', [BankController::class, 'koreaBank']);

// 적금 상품 페이지
Route::get('/api/moabank/product', [BankController::class, 'savingList']);

// 적금 상품 상세
Route::get('/api/moabank/product/detail/{id}', [BankController::class, 'product']);

// 적금 가입 페이지
Route::get('/api/moabank/product/regist/{id}', [BankController::class, 'product']);

// ******************************
// *      부모 은행 관련         *
// ******************************
// 부모: 자녀가 가입한 적금 목록 받아오기
Route::get('/api/parent/saving/list/{child_id}', [ParentSavingController::class, 'index']);

// 부모 은행 페이지
Route::get('/api/parent/moabank/{id}', [BankController::class, 'index']);

// 부모: 자녀 적금 통장 페이지
Route::get('/api/parent/bankbook/{saving_sign_up_id}', [ParentSavingController::class, 'show']);

// 부모 자녀 포인트 페이지
Route::get('/api/parent/point/{id}', [ParentPointController::class, 'parent']);

// ******************************
// *      자녀 은행 관련         *
// ******************************
// 자녀가 가입한 적금 목록 받아오기
Route::get('/api/child/saving/list', [ChildSavingController::class, 'index']);

// 자녀 은행 페이지
Route::get('/api/child/moabank', [ChildPointController::class, 'total']);

// 자녀 포인트 페이지
Route::get('/api/child/point', [ChildPointController::class, 'child']);

// 자녀 적금 통장 상세
Route::get('/api/child/moabank/bankbook/{saving_sign_up_id}', [ChildSavingController::class, 'show']);

// 자녀 만기 적금 리스트 페이지
Route::get('/api/child/expired/saving', [ChildSavingController::class, 'expiredSaving']);

// 자녀 적금 이자율 계산
Route::get('/api/child/moabank/interest/rate/{productId}', [BankController::class, 'calculateFinalAmount']);

// 자녀 적금 가입하기
Route::post('/api/child/moabank/saving/create/{product_id}', [ChildSavingController::class, 'store']);

// 자녀 적금 중도해지
Route::patch('/api/child/moabank/early/termination', [ChildSavingController::class, 'earlyTermination']);

// 이건 마지막 위치
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
