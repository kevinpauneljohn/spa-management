<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','verify.sales.instance'])->group(function(){
    Route::get('/get-selected-therapists',[\App\Http\Controllers\TherapistController::class,'getTherapists'])->name('get-selected-therapists');
    Route::put('/exclude-therapists',[\App\Http\Controllers\TherapistController::class,'excludeTherapists'])->name('exclude-therapists');
    Route::put('/unexclude-therapists',[\App\Http\Controllers\TherapistController::class,'unexcludeTherapists'])->name('unexclude-therapists');
});




