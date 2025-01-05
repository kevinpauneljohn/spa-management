<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('biometrics', \App\Http\Controllers\HR\BiometricController::class);
    Route::get('/biometrics-attendance',[\App\Http\Controllers\HR\BiometricController::class,'attendance'])->name('biometrics-attendance');
    Route::get('/biometrics-employee-attendance',[\App\Http\Controllers\HR\BiometricController::class,'getEmployeeAttendance'])->name('get-employee-biometrics-attendance');
});




