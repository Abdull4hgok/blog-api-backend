<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Controller;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function(){
 Route::post('user-details', [UserController::class, 'userDetails']);
 Route::post('logout',[UserController::class,'logout'])->name('logout');

});



Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/search', [PostController::class, 'search']);
Route::get('delete/{id}', [PostController::class, 'destroy'])->name('delete');
Route::post('update/{id}', [PostController::class, 'update'])->name('update');
Route::post('add', [PostController::class, 'store'])->name('add');
    Route::post('create-category', [PostController::class, 'createCategory']);
    Route::get('get-categories', [PostController::class, 'getCategories']);
    // Route::post('/assignCategory/{categoryId}/{postId}', [PostController::class, 'assignCategory']);
    Route::get('all-posts', [PostController::class, 'allPosts']);
    // Route::get('/categories/{categoryId}/posts', [PostController::class, 'getPostsByCategory']);
    // Route::get('/categories/{categoryId}/posts', [PostController::class, 'getPostsByCategory']);

    Route::get('posts', [PostController::class, 'index'])->name('posts');
    Route::get('posts/{category}', [PostController::class, 'index'])->name('posts.category');
    Route::get('detail/{id}', [PostController::class, 'show'])->name('detail');

});