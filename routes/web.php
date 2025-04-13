<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;

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
//     return view('welcome');
// });




Auth::routes();

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
// Route::post('/admin/login', [AdminAuthController::class, 'login']);
// Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('doctors', DoctorController::class);
    });

    Route::get('/', [AppointmentController::class, 'index'])->name('doctors.index');

    Route::get('appointments/{doctor}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments/{doctor}', [AppointmentController::class, 'store'])->name('appointments.store');
