<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SalesReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoices/{id}/return', [InvoiceController::class, 'createReturn'])->name('invoices.createReturn');
Route::post('/invoices/{id}/return', [InvoiceController::class, 'storeReturn'])->name('invoices.storeReturn');

Route::get('/sales-returns', [SalesReturnController::class, 'index'])->name('sales-returns.index');
Route::get('/sales-returns/{id}', [SalesReturnController::class, 'show'])->name('sales-returns.show');
Route::put('/sales-returns/{id}/status', [SalesReturnController::class, 'updateStatus'])->name('sales-returns.updateStatus');
