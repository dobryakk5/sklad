<?php

use App\Http\Controllers\Api\BoxController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Этап 1
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {

    // ------------------------------------------------------------------ //
    //  Склады                                                              //
    // ------------------------------------------------------------------ //
    Route::get('/warehouses', [WarehouseController::class, 'index']);

    // {slug} принимает как числовой ID, так и строковый слаг (CODE секции).
    // Убрано ->where('id', '[0-9]+') — фронт передаёт текстовый slug.
    Route::get('/warehouses/{slug}', [WarehouseController::class, 'show'])
        ->where('slug', '[^/]+');

    // ------------------------------------------------------------------ //
    //  Боксы                                                               //
    // ------------------------------------------------------------------ //
    Route::get('/boxes',      [BoxController::class, 'index']);
    Route::get('/boxes/{id}', [BoxController::class, 'show'])
        ->where('id', '[0-9]+');

    // ------------------------------------------------------------------ //
    //  Личный кабинет                                                      //
    // ------------------------------------------------------------------ //
    Route::get('/client/rentals', [ClientController::class, 'rentals']);

});
