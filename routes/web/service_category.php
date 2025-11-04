<?php

use App\Http\Controllers\ServiceCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('service-category', ServiceCategoryController::class);
    Route::get('/service-category-list/{spa_id}',[ServiceCategoryController::class,'getServiceCategories'])->name('get.service-category');
});
