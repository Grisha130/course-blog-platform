<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CourseCommentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\UserController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('profile')->group(function () {

        Route::get('/', [UserController::class, 'show']);
        Route::patch('/update', [UserController::class, 'update']);
        Route::patch('/update/password', [UserController::class, 'updatePassword']);

    });

    Route::prefix('courses')->group(function(){
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/my-deleted', [CourseController::class, 'deletedCourses']);
        Route::get('/my', [CourseController::class, 'myCourses']);

        Route::post('/create', [CourseController::class, 'store']);
        Route::get('/{course}', [CourseController::class, 'showOne']);

        Route::post('/{course}/comment', [CourseCommentController::class, 'comment']);
        Route::patch('/comments/{comment}', [CourseCommentController::class, 'updateComment']);
        Route::delete('/comments/{comment}', [CourseCommentController::class, 'destroy']);

        Route::patch('/{course}/update', [CourseController::class, 'update']);
        Route::delete('/{course}/delete',[CourseController::class, 'destroy']);
        Route::post('my-deleted/{course}/restore', [CourseController::class, 'restore'])->withTrashed();

    });

});
