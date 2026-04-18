<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\AHPController;
use App\Http\Controllers\COCOSOController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('criteria')->name('criteria.')->group(function () {
    Route::get('/', [CriteriaController::class, 'index'])->name('index');
    Route::post('/', [CriteriaController::class, 'store'])->name('store');
    Route::put('/{id}', [CriteriaController::class, 'update'])->name('update');
    Route::delete('/{id}', [CriteriaController::class, 'destroy'])->name('destroy');
});

Route::prefix('alternative')->name('alternative.')->group(function () {
    Route::get('/', [AlternativeController::class, 'index'])->name('index');
    Route::post('/', [AlternativeController::class, 'store'])->name('store');
    Route::put('/{id}', [AlternativeController::class, 'update'])->name('update');
    Route::delete('/{id}', [AlternativeController::class, 'destroy'])->name('destroy');
});

Route::prefix('ahp')->name('ahp.')->group(function () {
    Route::get('/', [AHPController::class, 'index'])->name('index');
    Route::post('/calculate', [AHPController::class, 'calculate'])->name('calculate');
});

Route::prefix('cocoso')->name('cocoso.')->group(function () {
    Route::get('/', [COCOSOController::class, 'index'])->name('index');
    Route::post('/calculate', [COCOSOController::class, 'calculate'])->name('calculate');
});

Route::get('/ranking', [COCOSOController::class, 'ranking'])->name('ranking');
