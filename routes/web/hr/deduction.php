<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('deductions', \App\Http\Controllers\HR\DeductionController::class);
    Route::get('/get-employee-payslip-deductions/{owner_id}/{payroll_id}', [\App\Http\Controllers\Hr\DeductionController::class, 'get_deductions'])->name('get-deduction-datatable');
});




