<?php

use App\Http\Controllers\Account\AvatarController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Affiliation\AffiliationAdController;
use App\Http\Controllers\Affiliation\AffiliationTecController;
use App\Http\Controllers\Comment\CreateCommentController;
use App\Http\Controllers\Comment\ManageTecCommentController;
use App\Http\Controllers\Comment\ViewCommentController;
use App\Http\Controllers\Hiring\HiringController;
use App\Http\Controllers\Hiring\ManageHiringController;
use App\Http\Controllers\Register\TecnicoController;
use App\Http\Controllers\Register\ClienteController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Service\ViewServiceController;
use Illuminate\Support\Facades\Route;

// Se hace uso de grupo de rutas
// https://laravel.com/docs/9.x/routing#route-groups
// https://laravel.com/docs/9.x/routing#route-group-prefixes

Route::prefix('v1')->group(function () {
    // CREACION DE PRUEBA UNITARIA POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // ⚪ Hacer uso del archivo auth.php
    require __DIR__ . '/auth.php';

    // 🔵 Ruta pública para el registro de usuario tecnico
    Route::post('/register-tecnico', [TecnicoController::class, 'register'])->name('register.tecnico');

    // 🟣 Ruta pública para el registro de usuario cliente
    Route::post('/register-cliente', [ClienteController::class, 'register'])->name('register.cliente');

    // 🟣 Grupo de rutas para la gestion de servicios
    Route::prefix('view-service')->group(function () {
        Route::controller(ViewServiceController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{service}', 'show');
        });
    });

    // 🔐 Se hace uso de grupo de rutas y que pasen por el proceso de auth con sanctum
    Route::middleware(['auth:sanctum'])->group(function () {

        // ⚪ Se hace uso de grupo de rutas para la gestion de perfil
        Route::prefix('profile')->group(function () {
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/', 'show')->name('profile');
                Route::post('/', 'store')->name('profile.store');
            });
            Route::post('/avatar', [AvatarController::class, 'store'])->name('profile.avatar');
        });

        // 🔵 Grupo de rutas para la afiliacion del tenico
        Route::prefix("affiliation")->group(function () {
            Route::controller(AffiliationTecController::class)->group(function () {
                Route::get('/show', 'show');
                Route::post('/create', 'create');
                Route::post('/update', 'update');
            });
        });

        // 🟢 Grupo de rutas para la gestion de las afiliaciones del lado del administrador
        Route::prefix("manage")->group(function () {
            Route::controller(AffiliationAdController::class)->group(function () {
                Route::get('/affiliation', 'index');
                Route::get('/affiliation/{affiliationtec}', 'showOne');
                Route::post('/affiliation/create/{affiliationtec}', 'create');
                // ------------------------------------------------
                Route::get('/affiliations', 'showAll');
                Route::get('/affiliation/show/{affiliation}', 'show');
                Route::post('/affiliation/update/{affiliation}', 'update');
            });
        });

        // 🔵 Grupo de rutas para la gestion de servicios
        Route::prefix('service')->group(function () {
            Route::controller(ServiceController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'create');
                Route::get('/show/{service}', 'show');
                Route::post('/update/{service}', 'update');
                Route::get('/destroy/{service}', 'destroy');
            });
        });

        // 🟣 Grupo de rutas para la gestion de contratacion del lado del cliente
        Route::prefix('hiring')->group(function () {
            Route::controller(HiringController::class)->group(function () {
                Route::post('/{service}', 'create');
                // --------------------------------
                Route::get('/show', 'index');
                Route::get('/show/{hiring}', 'show');
                Route::post('/update/{hiring}', 'update');
                Route::get('/cancel/{hiring}', 'destroy');
            });
        });

        // 🔵 Grupo de rutas para la gestion de contratacion del lado del tecnico
        Route::prefix('manage-hiring')->group(function () {
            Route::controller(ManageHiringController::class)->group(function () {
                Route::get('/shownew', 'shownew');
                Route::get('/showone/{hiring}', 'showone');
                Route::get('/approve/{hiring}', 'approve');
                Route::get('/decline/{hiring}', 'decline');
                Route::post('/finalize/{hiring}', 'create');
                // ------------------------------------
                Route::get('/show-finalize', 'index');
                Route::get('/show-finalize/{hiring}', 'show');
                Route::post('/update-finalize/{hiring}', 'updateFinalize');
            });
        });

        // 🟣 Grupo de rutas para creacion de formulario de satisfacion
        Route::prefix('satisfaction-form')->group(function () {
            Route::controller(CreateCommentController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/{hiring}', 'show');
                Route::post('/create/{hiring}', 'create');
            });
        });

        // 🔵 Ruta para la visualizacion de formularios de satisfacion para el tecnico
        Route::get('/view-satisfaction-form', [ViewCommentController::class, 'index']);

        // 🟢 Grupo de rutas para la gestionar los tecnicos
        Route::prefix('manage-tec')->group(function () {
            Route::controller(ManageTecCommentController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/show/{tec}', 'show');
                Route::post('/change-state/{tec}', 'change');
            });
        });
    });
});
