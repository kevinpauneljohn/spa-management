<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/access-payroll-by-spa',[\App\Http\Controllers\Payroll\PayrollController::class,'accessPayrollBySpa'])->name('access-payroll-by-spa');
    Route::resource('payroll',\App\Http\Controllers\Payroll\PayrollController::class);
    Route::get('/employees-payroll',[\App\Http\Controllers\Payroll\PayrollController::class,'employeePayroll'])->name('employees-payroll');
    Route::get('/get-employees-payroll',[\App\Http\Controllers\Payroll\PayrollController::class,'getEmployeesPayroll'])->name('get-employees-payroll');
    Route::post('/save-payroll',[\App\Http\Controllers\Payroll\PayrollController::class,'save_payroll'])->name('save-payroll');
    Route::get('/get-payroll-by-date-range',[\App\Http\Controllers\Payroll\PayrollController::class,'get_payroll_by_date_range'])->name('get-payroll-by-date-range');
});
