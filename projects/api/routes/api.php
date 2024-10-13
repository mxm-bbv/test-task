<?php

use App\Http\Controllers\Api\Actions\FileController;
use App\Http\Controllers\Api\Actions\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('accept.json')
    ->prefix('v1')
    ->name('api.')
    ->group(function () {
        Route::prefix('user')
            ->name('user.')
            ->group(function () {
                Route::get('register', [UserController::class, 'registerByIp'])->name('register');
            });
        Route::prefix('file')
            ->name('file.')
            ->group(function () {
                Route::get('', [FileController::class, 'index'])->name('index');
                Route::post('store', [FileController::class, 'store'])->name('store');
                Route::get('{file}/download', [FileController::class, 'download'])->name('download');
                Route::post('check', [FileController::class, 'check'])->name('check');
                Route::get('{file}', [FileController::class, 'show'])->name('show');
                Route::post('', [FileController::class, 'update'])->name('update');
                Route::post('{file}', [FileController::class, 'destroy'])->name('destroy');
            });
    });
