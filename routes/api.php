<?php

use App\Http\Controllers\Api\SuperAdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CourseCommentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');


Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1');
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::middleware('verified')->group(function () {

        Route::prefix('profile')->group(function () {

            Route::get('/', [UserController::class, 'show']);
            Route::patch('/update', [UserController::class, 'update']);
            Route::patch('/update/password', [UserController::class, 'updatePassword']);
        });

        Route::prefix('courses')->group(function () {
            Route::get('/', [CourseController::class, 'index']);
            Route::get('/my-deleted-courses', [CourseController::class, 'deletedCourses']);
            Route::get('/my-courses', [CourseController::class, 'myCourses']);

            Route::post('/create', [CourseController::class, 'store']);
            Route::get('/{course}', [CourseController::class, 'showOne']);

            Route::post('/{course}/comment', [CourseCommentController::class, 'comment']);
            Route::patch('/comments/{comment}', [CourseCommentController::class, 'updateComment']);
            Route::delete('/comments/{comment}', [CourseCommentController::class, 'destroy']);

            Route::patch('/{course}/update', [CourseController::class, 'update']);
            Route::delete('/{course}/delete', [CourseController::class, 'destroy']);
            Route::post('my-deleted/{course}/restore', [CourseController::class, 'restore'])->withTrashed();
        });

        Route::prefix('blogs')->group(function () {
            Route::get('/', [BlogController::class, 'index']);
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

        Route::middleware('role:Admin|Super Admin')->group(function () {
            Route::prefix('category')->group(function () {
                Route::post('/create', [CategoryController::class, 'store']);
                Route::patch('/{category}/update', [CategoryController::class, 'update']);
                Route::delete('/{category}/delete', [CategoryController::class, 'destroy']);
            });
            Route::prefix('tag')->group(function () {
                Route::post('/create', [TagController::class, 'store']);
                Route::patch('/{tag}/update', [TagController::class, 'update']);
                Route::delete('/{tag}/delete', [TagController::class, 'destroy']);
            });
        });

        Route::middleware('role:Super Admin')->prefix('super_admin')->group(function () {
            Route::get('/users', [SuperAdminController::class, 'index']);
            Route::post('/users/{user}/status', [SuperAdminController::class, 'block']);
            Route::post('/users/{user}/role', [SuperAdminController::class, 'addRole']);
            Route::delete('/users/{user}', [SuperAdminController::class, 'destroy']);

            Route::get('/deleted-users', [SuperAdminController::class, 'deletedUsers']);
            Route::post('/deleted-users/{user}/restore', [SuperAdminController::class, 'restore'])->withTrashed();
            Route::delete('/deleted-users/{user}/force-delete', [SuperAdminController::class, 'forceDelete'])->withTrashed();
        });
        Route::middleware(['role:Editor|Super Admin'])->group(function () {
            Route::get('/deleted-courses', [CourseController::class, 'allDeleted'])->withTrashed();
            Route::post('/deleted-courses/{course}/restore', [CourseController::class, 'restore'])->withTrashed();
            Route::delete('/deleted-courses/{course}/force-delete', [CourseController::class, 'forceDelete'])->withTrashed();
            Route::get('/blocked-courses', [CourseController::class, 'blocked']);
            Route::post('/courses/{course}/block', [CourseController::class, 'block']);

            Route::get('/blocked-blogs', [BlogController::class, 'blocked']);
            Route::post('/blogs/{blog}/block', [BlogController::class, 'block']);

            Route::get('deleted-blogs', [BlogController::class, 'allDeleted'])->withTrashed();
            Route::post('/deleted-blogs/{blog}/restore', [BlogController::class, 'restore'])->withTrashed();
            Route::delete('deleted-blogs/{blog}/force-delete', [BlogController::class, 'forceDelete'])->withTrashed();
        });
    });
});
