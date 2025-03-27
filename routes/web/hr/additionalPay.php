<?php


use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('additional-pay', \App\Http\Controllers\HR\AdditionalPayController::class);
    Route::get('/get-employee-payslip-additional-pay/{payroll_id}', [\App\Http\Controllers\Hr\AdditionalPayController::class, 'get_additional_pays'])->name('get-additional-pay-datatable');
});




