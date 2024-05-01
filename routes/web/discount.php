<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('discounts',\App\Http\Controllers\DiscountController::class);
    Route::get('/discount-table',[\App\Http\Controllers\DiscountController::class,'discountTable'])->name('discount.table');
    Route::get('/generate-bar-code/{id}',[\App\Http\Controllers\DiscountController::class,'generateCode'])->name('generate.bar.code');
});




