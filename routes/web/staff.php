<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','verify.sales.instance'])->group(function(){
    Route::put('staff/{staff_id}/change-password',[\App\Http\Controllers\MyStaffController::class,'change_password'])->name('change-staff-password');
});




