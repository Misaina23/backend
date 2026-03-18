<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/producers', [DashboardController::class, 'producers'])->name('producers');
Route::get('/producers/{id}', [DashboardController::class, 'producerDetail'])->name('producers.detail');
Route::get('/inspections', [DashboardController::class, 'inspections'])->name('inspections');
Route::get('/inspections/{id}', [DashboardController::class, 'inspectionDetail'])->name('inspections.detail');
