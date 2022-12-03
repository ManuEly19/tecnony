<?php

use App\Http\Controllers\Account\AvatarController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Affiliation\AffiliationAdController;
use App\Http\Controllers\Affiliation\AffiliationTecController;
use App\Http\Controllers\Users\ClienteController;
use App\Http\Controllers\Users\TecnicoController;
use Illuminate\Support\Facades\Route;

// Se hace uso de grupo de rutas
// https://laravel.com/docs/9.x/routing#route-groups
// https://laravel.com/docs/9.x/routing#route-group-prefixes

Route::prefix('v1')->group(function () {
    // Hacer uso del archivo auth.php
    require __DIR__ . '/auth.php';

    // Ruta pública para el registro de usuario tecnico
    Route::post('/register-tecnico', [TecnicoController::class, 'register'])->name('register.tecnico');

    // Ruta pública para el registro de usuario cliente
    Route::post('/register-cliente', [ClienteController::class, 'register'])->name('register.cliente');

    // Se hace uso de grupo de rutas y que pasen por el proceso de auth con sanctum
    Route::middleware(['auth:sanctum'])->group(function () {
        // Se hace uso de grupo de rutas
        Route::prefix('profile')->group(function () {
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/', 'show')->name('profile');
                Route::post('/', 'store')->name('profile.store');
            });
            Route::post('/avatar', [AvatarController::class, 'store'])->name('profile.avatar');
        });

        Route::prefix("affiliation")->group(function () {
            Route::controller(AffiliationTecController::class)->group(function () {
                Route::get('/show', 'show');
                Route::post('/create', 'create');
                Route::post('/update', 'update');
            });
        });

        Route::prefix("manage")->group(function () {
            Route::controller(AffiliationAdController::class)->group(function () {
                Route::get('/affiliations', 'index');
                Route::get('/affiliations/show', 'show');
                Route::get('/affiliation/show/{affiliation}', 'showOne');
            });
        });
    });
});
