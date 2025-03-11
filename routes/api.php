<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\TagController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/tags', TagController::class);

