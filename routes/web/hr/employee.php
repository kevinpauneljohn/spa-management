<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    Route::get('/get-employees',[\App\Http\Controllers\HR\EmployeeController::class,'displayEmployees'])->name('get-employees');
});




