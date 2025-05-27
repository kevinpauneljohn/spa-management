<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/spa/expenses/{spa}',[\App\Http\Controllers\SpaController::class,'displaySpaExpenses'])->name('spa.expenses.display');
    Route::get('/spa-expense-list/{spa}',[\App\Http\Controllers\SpaController::class,'spaExpenses'])->name('spa.expenses');
    Route::post('/expenses-set-date',[\App\Http\Controllers\ExpenseController::class,'displayExpensesByDateSelected'])->name('expenses.set.date');
    Route::resource('expenses',\App\Http\Controllers\ExpenseController::class);
});
