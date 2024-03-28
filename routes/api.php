<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LettersApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {

    Route::get('letters', [LettersApiController::class, 'index']);
    Route::get('letters/{letter}', [LettersApiController::class, 'show']);
});
