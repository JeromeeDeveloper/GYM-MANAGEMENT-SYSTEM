<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use App\Http\Controllers\DmemberController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\MemberCrudController;
use App\Http\Controllers\Admin\CheckInsCrudController;


Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin'),
        ['capability']
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    Route::crud('user', 'UserCrudController');
    Route::crud('membership', 'MembershipCrudController');
    Route::crud('member', 'MemberCrudController');
    Route::crud('payments', 'PaymentsCrudController');
    Route::crud('check-ins', 'CheckInsCrudController');

Route::get('report', [ReportsController::class, 'index'])->name('reports.index');
Route::get('filter-data', [CheckInsCrudController::class, 'filterData'])->name('filterData');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/pay', [PayController::class, 'index'])->name('pay.index');
Route::get('/filter-data2', [PayController::class, 'filterData2'])->name('filterData2');
Route::get('/dmember', [DmemberController::class, 'index'])->name('dmember.index');
Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflow.index');
Route::get('/filter-data3', [CashflowController::class, 'filterData3'])->name('filterData3');
Route::get('/fetchTotalGcashPayments', [CashflowController::class, 'fetchTotalGcashPayments'])->name('fetchTotalGcashPayments');

Route::get('/filterData4', [DmemberController::class, 'filterData4'])->name('filterData4');

});
