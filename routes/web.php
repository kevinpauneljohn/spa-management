<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(\route('dashboard'));
});

Auth::routes();
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[\App\Http\Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

    Route::resource('owners',\App\Http\Controllers\Owners\OwnerController::class);
    Route::get('/owners-list',[\App\Http\Controllers\Owners\OwnerController::class,'owner_lists'])->name('owner.lists');
    Route::post('/owners',[\App\Http\Controllers\Owners\OwnerController::class,'store'])->name('owner.store');
    Route::get('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'show'])->name('owner.show');
    Route::put('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'update'])->name('owner.update');
    Route::delete('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'destroy'])->name('owner.delete');

    Route::get('/spa-list/{id}',[\App\Http\Controllers\SpaController::class,'lists'])->name('spa.lists');
    Route::get('/spa/overview/{id}',[\App\Http\Controllers\SpaController::class,'overview'])->name('spa.overview');
    Route::post('/spa',[\App\Http\Controllers\SpaController::class,'store'])->name('spa.store');
    Route::get('/spa/{id}',[\App\Http\Controllers\SpaController::class,'show'])->name('spa.show');
    Route::put('/spa/{id}',[\App\Http\Controllers\SpaController::class,'update'])->name('spa.update');
    Route::delete('/spa/{id}',[\App\Http\Controllers\SpaController::class,'destroy'])->name('spa.delete');

    Route::get('/therapist-list/{id}',[\App\Http\Controllers\TherapistController::class,'lists'])->name('therapist.lists');
    Route::get('/therapist/overview/{id}',[\App\Http\Controllers\TherapistController::class,'overview'])->name('therapist.overview');
    Route::post('/therapist',[\App\Http\Controllers\TherapistController::class,'store'])->name('therapist.store');
    Route::get('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'show'])->name('therapist.show');
    Route::put('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'update'])->name('therapist.update');
    Route::delete('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'destroy'])->name('therapist.delete');

    Route::get('/service-list/{id}',[\App\Http\Controllers\ServiceController::class,'lists'])->name('service.lists');
    Route::get('/service/overview/{id}',[\App\Http\Controllers\ServiceController::class,'overview'])->name('service.overview');
    Route::post('/service',[\App\Http\Controllers\ServiceController::class,'store'])->name('service.store');
    Route::get('/service/{id}',[\App\Http\Controllers\ServiceController::class,'show'])->name('service.show');
    Route::get('/service-duration-range',[\App\Http\Controllers\ServiceController::class,'durationRange'])->name('service.duration.range');
    Route::put('/service/{id}',[\App\Http\Controllers\ServiceController::class,'update'])->name('service.update');
    Route::delete('/service/{id}',[\App\Http\Controllers\ServiceController::class,'destroy'])->name('service.delete');
    
});

Route::get('/home', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('home');
