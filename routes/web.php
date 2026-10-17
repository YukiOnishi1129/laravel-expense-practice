<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExpenseApprovalController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', fn () => redirect()->route('expenses.index'));

    Route::resource('expenses', ExpenseController::class)->except(['destroy']);

    Route::get('/approvals/{expense}', [ExpenseApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{expense}/approve', [ExpenseApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{expense}/reject', [ExpenseApprovalController::class, 'reject'])->name('approvals.reject');
});
