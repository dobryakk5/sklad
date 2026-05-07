<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SeoMetaController as AdminSeoMetaController;
use App\Http\Controllers\Api\BoxController;
use App\Http\Controllers\Api\CabinetController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SeoMetaController;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
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

    // ------------------------------------------------------------------ //
    //  Публичный контент                                                   //
    // ------------------------------------------------------------------ //
    Route::get('/seo/{pageType}/{pageSlug}', [SeoMetaController::class, 'show'])
        ->where('pageType', 'warehouse|box')
        ->where('pageSlug', '[^/]+');

    Route::get('/reviews', [ReviewController::class, 'index']);

});

Route::prefix('admin')->middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::prefix('admin')->middleware(['api', 'admin.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/seo', [AdminSeoMetaController::class, 'index']);
    Route::post('/seo', [AdminSeoMetaController::class, 'store']);
    Route::put('/seo/{id}', [AdminSeoMetaController::class, 'update']);
    Route::delete('/seo/{id}', [AdminSeoMetaController::class, 'destroy']);

    Route::get('/reviews', [AdminReviewController::class, 'index']);
    Route::post('/reviews', [AdminReviewController::class, 'store']);
    Route::put('/reviews/{id}', [AdminReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);
});

Route::prefix('admin')->middleware(['api', 'admin.auth', 'admin.role'])->group(function () {
    Route::get('/users', [OperatorController::class, 'index']);
    Route::post('/users', [OperatorController::class, 'store']);
    Route::put('/users/{id}', [OperatorController::class, 'update']);
    Route::delete('/users/{id}', [OperatorController::class, 'destroy']);
});

Route::prefix('cabinet')->middleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
])->group(function () {
    Route::post('/auth/login', [CabinetController::class, 'login']);
    Route::post('/auth/logout', [CabinetController::class, 'logout'])
        ->middleware('cabinet.auth');

    Route::middleware('cabinet.auth')->group(function () {
        Route::get('/me', [CabinetController::class, 'me']);
        Route::get('/contracts', [CabinetController::class, 'contracts']);
        Route::get('/invoices', [CabinetController::class, 'invoices']);
        Route::get('/balance', [CabinetController::class, 'balance']);
        Route::get('/payment-method', [CabinetController::class, 'paymentMethod']);
        Route::post('/requests', [CabinetController::class, 'createRequest']);

        Route::get('/invoices/{id}', [CabinetController::class, 'invoice'])
            ->where('id', '[0-9]+');
        Route::post('/invoices/{id}/pay', [CabinetController::class, 'notImplemented'])
            ->where('id', '[0-9]+');
        Route::post('/balance/top-up', [CabinetController::class, 'notImplemented']);
        Route::post('/autopay/enable', [CabinetController::class, 'notImplemented']);
        Route::post('/autopay/disable', [CabinetController::class, 'notImplemented']);
        Route::post('/payment-method/unlink', [CabinetController::class, 'notImplemented']);
    });
});
