<?php

use App\Http\Controllers\EnrollementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V2\AuthController;
use App\Http\Controllers\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/tags', TagController::class);
Route::apiResource('/courses', CourseController::class);


Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    // categories
    Route::apiResource('/categories', CategoryController::class);
    // coureses videos
    Route::get('courses/{id}/videos', [VideoController::class, 'index']);
    Route::post('courses/{id}/videos', [VideoController::class, 'store']);
    Route::get('videos/{id}', [VideoController::class, 'show']);
    Route::put('videos/{id}', [VideoController::class, 'update']);
    Route::delete('videos/{id}', [VideoController::class, 'destroy']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    // Enrollments management
    Route::put('enrollments/{id}', [EnrollementController::class, 'updateStatus']);
    
    
    Route::get('enrollments', [EnrollementController::class, 'index']);
    Route::delete('enrollments/{id}', [EnrollementController::class, 'destroy']);
    Route::get('enrollments/{id}', [EnrollementController::class, 'show']);
    Route::post('courses/{id}/enroll', [EnrollementController::class, 'enrollInCourse']);
});
