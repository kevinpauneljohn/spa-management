<?php

//use App\Http\Controllers\ApiAttendance;
use App\Http\Controllers\DownloadAttendanceController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\ThrottleRequests;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {

});

//Route::post('/timeinApi/{id}/{spaCode}', [EmployeeController::class, 'timeInApi']);
//Route::match(['get', 'put'], '/timeUpdate/{id}/{action}', [EmployeeController::class, 'timeOutBreakInBreakOutApi']);



Route::middleware('client')->group(function () {
    Route::get('/get-all-employees/{owner_id}',[\App\Http\Controllers\HR\EmployeeController::class,'getEmployees'])->name('get-all-employees');
    Route::post('/add-employee-to-biometrics/{employee_id}',[\App\Http\Controllers\HR\EmployeeController::class,'addEmployeeToBiometrics'])->name('add-employee-to-biometrics');
    Route::post('/store-attendance',[\App\Http\Controllers\HR\AttendanceController::class,'storeAttendance'])->name('store-attendance');
});
