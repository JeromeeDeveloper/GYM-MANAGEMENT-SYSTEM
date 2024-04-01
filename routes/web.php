<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\MemberCrudController;
use App\Http\Controllers\Admin\CheckInsCrudController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('gymlandingpage');
// });
// Route::get('/landingpage', function () {
//     return view('gymlandingpage');
// });

Route::get('/', function () {
    return view('landingpage');
});

// Route::get('/error', function () {
//     return view('error');
// })->name('error');

// Inside your routes file
Route::get('/', [MemberCrudController::class, 'searchMembers'])->name('members.search');
// Route::get('/', [MemberCrudController::class, 'searchMembers'])->name('members.search');
// // Route::get('admin/member/{id}/payment', [MemberCrudController::class, 'payment'])->name('admin.member.payment');
// Route::get('admin/report', [ReportsController::class, 'index'])->name('reports.index');
// Route::get('filter-data', [CheckInsCrudController::class, 'filterData'])->name('filterData');

