<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('getUsers', [UserController::class, "totalUsers"]);
Route::get('getComment', [CommentController::class, "getComment"]);
Route::get('getPost', [PostController::class, "getPost"]);
Route::get('searchPosts/{user_id}', [PostController::class, "searchPosts"]);
Route::post('newPost' , [PostController::class, "newPost"]);


Route::get('searchUsers/{email}', [UserController::class, "searchUsers"]);

Route::post('register', [UserController::class, "register"]);



