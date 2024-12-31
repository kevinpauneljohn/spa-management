<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('departments', \App\Http\Controllers\HR\DepartmentController::class);
    Route::get('/department-list',[\App\Http\Controllers\HR\DepartmentController::class,'lists'])->name('departments.list');
});




