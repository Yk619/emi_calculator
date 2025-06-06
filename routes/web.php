<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/loan-list', [LoanController::class, 'list'])->name('loan.list');
    Route::get('/loan-form', [LoanController::class, 'showForm'])->name('loan.form');
    Route::post('/loan-calculate', [LoanController::class, 'calculate'])->name('loan.calculate');
    Route::post('/process-data', [LoanController::class, 'processEmiData'])->name('emi.process');
    Route::get('/process-data', [LoanController::class, 'showEmiPage'])->name('emi.page');

    //Route::get('/process-emi', [EmiController::class, 'index'])->name('emi.index');
    //Route::post('/process-emi', [EmiController::class, 'process'])->name('emi.process');

});

require __DIR__.'/auth.php';
