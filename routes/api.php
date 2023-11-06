<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'website'], function () {
    Route::get('/menu-types', [App\Http\Controllers\MenuTypeController::class, 'getAllMenuType'])->name('menu-types');
    Route::get('/menus', [App\Http\Controllers\MenuController::class, 'getAllMenu'])->name('menus');
    Route::get('/floors', [App\Http\Controllers\SeatController::class, 'getAllFloor'])->name('floors');
    Route::get('/tables', [App\Http\Controllers\SeatController::class, 'getBuildData'])->name('tables');
    Route::post('/make-booking', [App\Http\Controllers\BookingController::class, 'store'])->name('make-booking');

});
