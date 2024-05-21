<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::patch('/inventory-quantity-update/{inventory}',[\App\Http\Controllers\InventoryController::class,'updateInventory'])->name('inventory.update.quantity');
});
