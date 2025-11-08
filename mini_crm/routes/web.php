<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\WidgetController;

// Auth
Route::get('/login', [AuthController::class, 'auth'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Widgets
Route::get('/widget', [WidgetController::class, 'index'])->name('widget');

// Protected routes
Route::middleware(['auth', 'role:manager|admin'])->group(function () {
    // Home - Users page
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [TicketController::class, 'details'])->name('tickets.details');
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');

    // File Management
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/file/{id}', [FileController::class, 'details'])->name('file.details');
});
