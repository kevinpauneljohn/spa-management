<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/sales-shift-lists',[\App\Http\Controllers\Pos\SalesShiftController::class,'lists'])->name('sales.shift.lists');
    Route::post('/end-shift-by-owner/{id}',[\App\Http\Controllers\Pos\SalesShiftController::class,'endShiftByOwner'])->name('end.shift.by.owner');
});




