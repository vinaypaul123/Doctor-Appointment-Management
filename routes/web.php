<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Auth;
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



Auth::routes(['register' => false]);

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/admin', [App\Http\Controllers\DoctorController::class, 'index'])->name('admin');
    Route::resource('doctors', DoctorController::class);
    Route::get('/appointmentdetail', [DoctorController::class, 'appointmentdetail'])->name('doctors.appointmentdetail');

});

    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{doctor}', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments/{doctor}', [AppointmentController::class, 'store'])->name('appointments.store');
