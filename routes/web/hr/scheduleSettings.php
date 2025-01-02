<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('schedule-settings', \App\Http\Controllers\HR\ScheduleSettingsController::class);
//    Route::get('/get-schedules',[\App\Http\Controllers\HR\ScheduleController::class,'displaySchedules'])->name('get-schedules');
});




