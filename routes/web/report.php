<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::post('/get-profit-report/{spa}',[\App\Http\Controllers\ReportController::class,'displayProfitByDateRange'])->name('get.spa.profit.by.date.range');
});




