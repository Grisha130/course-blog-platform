<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CourseCommentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\TagController;
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
        Route::get('/my-deleted-courses', [CourseController::class, 'deletedCourses']);
        Route::get('/my-courses', [CourseController::class, 'myCourses']);

        Route::post('/create', [CourseController::class, 'store']);
        Route::get('/{course}', [CourseController::class, 'showOne']);

        Route::post('/{course}/comment', [CourseCommentController::class, 'comment']);
        Route::patch('/comments/{comment}', [CourseCommentController::class, 'updateComment']);
        Route::delete('/comments/{comment}', [CourseCommentController::class, 'destroy']);

        Route::patch('/{course}/update', [CourseController::class, 'update']);
        Route::delete('/{course}/delete',[CourseController::class, 'destroy']);
        Route::post('my-deleted/{course}/restore', [CourseController::class, 'restore'])->withTrashed();

    });
    Route::prefix('blogs')->group(function(){
        Route::get('/', [BlogController::class, 'index'] );
        Route::get('/my-blogs', [BlogController::class, 'myBlogs']);
        Route::get('/{blog}/show', [BlogController::class, 'showOne']);
        Route::get('/my-deleted', [BlogController::class, 'deletedBlogs']);
        
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/tags', [TagController::class, 'index']);
        Route::post('/create', [BlogController::class, 'store']);
        Route::post('/my-deleted/{blog}/restore', [BlogController::class, 'restore'])->withTrashed();
        Route::patch('/{blog}/update', [BlogController::class, 'update']);
        Route::delete('/{blog}/delete', [BlogController::class, 'destroy']);

        Route::post('/{blog}/comment', [BlogCommentController::class, 'comment']);
        Route::patch('/comment/{comment}/update', [BlogCommentController::class, 'update']);
        Route::delete('/comment/{comment}/delete', [BlogCommentController::class, 'destroy']);
    });

    Route::prefix('category')->group(function(){
        Route::post('/create', [CategoryController::class, 'store']);
        Route::patch('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
    });
    Route::prefix('tag')->group(function(){
        Route::post('/create', [TagController::class, 'store']);
        Route::patch('/{tag}/update', [TagController::class, 'update']);
        Route::delete('/{tag}/delete', [TagController::class, 'destroy']);

    });

});
