<?php

use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\TicketApiController;
use Illuminate\Support\Facades\Route;

// Public api routes for widget
Route::post('/customers', [CustomerApiController::class, 'store']);
Route::get('/customers', [CustomerApiController::class, 'index']);
Route::post('/tickets', [TicketApiController::class, 'store']);
Route::get('/tickets/statistics', [TicketApiController::class, 'statistics']);

