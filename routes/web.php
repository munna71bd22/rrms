<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/register', function () {
    return 'not allowed!';
});


/*
|--------------------------------------------------------------------------
| All Auth Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'home', 'middleware' => 'auth'], function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('/seat-builder', App\Http\Controllers\SeatController::class);
    Route::post('/seat-builder/build', [App\Http\Controllers\SeatController::class , 'build'])->name('seat-builder.build');
    Route::get('/seat-builder/get/build-data', [App\Http\Controllers\SeatController::class , 'getBuildData'])->name('seat-builder.get-build-data');
    Route::resource('/menu-builder', App\Http\Controllers\MenuController::class);
    Route::resource('/customers', App\Http\Controllers\CustomerController::class);
    Route::resource('/bookings', App\Http\Controllers\BookingController::class);
    Route::get('/bookings/status/update/{id}', [App\Http\Controllers\BookingController::class,'updateStatus'])->name('bookings.update-status');
    Route::resource('/configurations', App\Http\Controllers\ConfigurationController::class);
    Route::resource('/pages', App\Http\Controllers\PageController::class);

    Route::get('/user/profile', [App\Http\Controllers\CustomerController::class, 'getOwnProfile'])->name('get-user-profile');
    Route::patch('/user/profile', [App\Http\Controllers\CustomerController::class, 'updateOwnProfile'])->name('update-user-profile');

});

Route::get('/website/pages/{slug}', [App\Http\Controllers\PageController::class, 'getPageBySlug']);
