<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LettersApiController;
use App\Http\Controllers\Api\V1\Auth\AuthApiController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {

    Route::get('letters', [LettersApiController::class, 'index']);
    Route::get('letters/{letter}', [LettersApiController::class, 'show']);

    Route::group(['prefix' => 'auth'], function () {

        Route::post('register', [AuthApiController::class, 'register']);
        Route::post('login', [AuthApiController::class, 'login']);

        Route::get('me', [AuthApiController::class, 'me'])->middleware('auth:sanctum');
        Route::post('logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');

        Route::get('email/verify/{code}', [AuthApiController::class, 'verifyEmail'])->name('emails.verify');

        Route::post('passwords/forgot/email', [ForgotPasswordApiController::class, 'forgotPasswordEmail']);
        Route::post('passwords/forgot/mobile', [ForgotPasswordApiController::class, 'forgotPasswordMobile']);
    });
});
