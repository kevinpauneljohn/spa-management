<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/access-payroll-by-spa',[\App\Http\Controllers\Payroll\PayrollController::class,'accessPayrollBySpa'])->name('access-payroll-by-spa');
    Route::resource('payroll',\App\Http\Controllers\Payroll\PayrollController::class);
});
