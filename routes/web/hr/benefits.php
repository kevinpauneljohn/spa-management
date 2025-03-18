<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('benefits', \App\Http\Controllers\HR\BenefitController::class);
});




