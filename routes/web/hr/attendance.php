<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('attendances', \App\Http\Controllers\HR\AttendanceController::class);
    Route::get('/employee-attendance/{employee_id}',[\App\Http\Controllers\HR\AttendanceController::class,'employeeAttendance'])->name('employee-attendance');
    Route::get('/all-employees-attendance/',[\App\Http\Controllers\HR\AttendanceController::class,'allEmployeeAttendance'])->name('all-employees-attendance');
});




