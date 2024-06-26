<?php

use App\Http\Controllers\ApiAttendance;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();


});

Route::post('/timeinApi/{id}/{spaCode}', [EmployeeController::class, 'timeInApi']);
Route::match(['get', 'put'], '/timeUpdate/{id}/{action}', [EmployeeController::class, 'timeOutBreakInBreakOutApi']);

