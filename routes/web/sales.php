<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/display-therapist-availability-in-progress-bar/{spa}',[\App\Http\Controllers\Pos\SalesController::class,'displayTherapistsAvailabilityInProgressBar'])->name('display-availability-in-progress-bar');
    Route::patch('/extend-time/{transaction}',[\App\Http\Controllers\Pos\TransactionController::class,'extendTime'])->name('extend-time');
    Route::get('/print-invoice/{sale}',[\App\Http\Controllers\Pos\SalesController::class,'printInvoice'])->name('print-invoice');
    Route::get('/sales-activity-logs/{spaId}',[\App\Http\Controllers\Pos\SalesController::class,'salesActivityLogs'])->name('sales-activity-logs');
    Route::patch('/pay/{salesId}',[\App\Http\Controllers\Pos\SalesController::class,'pay'])->name('sales-payment');
    Route::get('/total-amount-to-be-paid-in-sales/{salesId}',[\App\Http\Controllers\Pos\SalesController::class,'getAmountToBePaid'])->name('get-sales-amount-to-be-paid');
    Route::patch('/isolate-transaction/{spaId}/{transactionId}',[\App\Http\Controllers\Pos\SalesController::class,'isolateTransaction'])->name('isolate-transaction');
    Route::post('/sales-display-by-date-range/{spaId}',[\App\Http\Controllers\Pos\SalesController::class,'getSalesByDateRange'])->name('display-sales-by-date-selected');
    Route::get('/point-of-sale-lists/{spa}',[\App\Http\Controllers\Pos\SalesController::class,'salesList'])->name('point-of-sale-lists');
    Route::get('/point-of-sale/sales-transaction-list/{spaId}/{saleId}',[\App\Http\Controllers\Pos\TransactionController::class,'transactionClientLists'])->name('pos-sales-client-transactions');
    Route::get('/point-of-sale/add-transaction/{spa}/{sale}',[\App\Http\Controllers\Pos\SalesController::class,'addTransactions'])->name('pos.add.transaction');
    Route::resource('point-of-sale',\App\Http\Controllers\Pos\SalesController::class);
});

