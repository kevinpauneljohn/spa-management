<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('schedules', \App\Http\Controllers\HR\ScheduleController::class);
    Route::get('/get-schedules',[\App\Http\Controllers\HR\ScheduleController::class,'displaySchedules'])->name('get-schedules');
});




