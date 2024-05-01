<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('clients',\App\Http\Controllers\Pos\ClientController::class);
});




