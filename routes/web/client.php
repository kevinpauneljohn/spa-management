<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::resource('clients',\App\Http\Controllers\Pos\ClientController::class);
    Route::get('/client-transactions/{client}',[\App\Http\Controllers\Pos\ClientController::class,'clientTransactionLists'])->name('client.transactions');
    Route::get('/download-clients',[\App\Http\Controllers\Pos\ClientController::class,'downloadClients'])->name('download.clients');
});




