<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('appointments',\App\Http\Controllers\Pos\AppointmentController::class);
});
