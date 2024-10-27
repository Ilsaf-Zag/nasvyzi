<?php

use App\Http\Controllers\API\Admin\LessonController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\User\SendMailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [\App\Http\Controllers\API\UserController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\API\UserController::class, 'register']);
Route::post('/payments', [PaymentController::class, 'test']);
Route::post('/send_mail', SendMailController::class);
Route::post('/payment_webhook', [PaymentController::class, 'handlePayment']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get("/file/{lesson}", [\App\Http\Controllers\API\FileController::class,'show']);

    Route::get("/lessons", [\App\Http\Controllers\API\User\LessonController::class,'index']);

    Route::controller(\App\Http\Controllers\API\UserController::class)
        ->group(function () {
            Route::delete('/logout', 'logout');
            Route::get('/fetch_user', 'fetchUser');

        });

    Route::prefix('admin')->group(function () {

        Route::resource('users', \App\Http\Controllers\API\Admin\UserController::class)->except(['create', 'show']);
        Route::resource('lessons', LessonController::class)->except(['create', 'show']);
    });
});

