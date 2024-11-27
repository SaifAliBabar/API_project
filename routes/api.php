<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\AuthorElement;

// signup route

Route::post('signup', [AuthController::class, 'signup']);

// login route

Route::post('login', [AuthController::class, 'login']);

// logout route

Route::middleware('auth:sanctum')->group(function() {

    Route::post('logout', [ AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

});




