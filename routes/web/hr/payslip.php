<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('payslips', \App\Http\Controllers\HR\PayslipController::class);
});




