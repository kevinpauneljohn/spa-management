<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('discounts',\App\Http\Controllers\DiscountController::class);
    Route::get('/discount-table',[\App\Http\Controllers\DiscountController::class,'discountTable'])->name('discount.table');
    Route::get('/generate-bar-code/{id}',[\App\Http\Controllers\DiscountController::class,'generateCode'])->name('generate.bar.code');
    Route::get('/get-discount/{code}',[\App\Http\Controllers\DiscountController::class,'getDiscount'])->name('get.discount.code');
    Route::get('/check-voucher-availability/{code}',[\App\Http\Controllers\DiscountController::class,'checkVoucherAvailability'])->name('check.voucher.availability');
    Route::delete('/delete-discount/{discount}',[\App\Http\Controllers\DiscountController::class,'deleteDiscount'])->name('delete.discount');
});




