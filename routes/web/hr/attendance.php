<?php

use App\Http\Controllers\HR\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('attendances', \App\Http\Controllers\HR\AttendanceController::class);
    Route::get('/employee-attendance/{employee_id}',[\App\Http\Controllers\HR\AttendanceController::class,'employeeAttendance'])->name('employee-attendance');
    Route::get('/all-employees-attendance/',[\App\Http\Controllers\HR\AttendanceController::class,'allEmployeeAttendance'])->name('all-employees-attendance');
    Route::post('/add-new-employee-attendance',[AttendanceCOntroller::class,'addNewEmployeeAttendance'])->name('add-new-employee-attendance');
    Route::get('/get-attendance-by-date-range',[AttendanceController::class,'get_attendance_by_date_range'])->name('get-attendance-by-date-range');
});




