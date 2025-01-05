<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    Route::get('/get-employees',[\App\Http\Controllers\HR\EmployeeController::class,'displayEmployees'])->name('get-employees');
    Route::get('/test-biometrics-connection',[\App\Http\Controllers\HR\EmployeeController::class,'testBiometricsConnection'])->name('test-biometrics-connection');
    Route::post('/add-employee-to-biometrics/{id}',[\App\Http\Controllers\HR\EmployeeController::class,'addEmployeeToBiometrics'])->name('add-employee-to-biometrics');
});




