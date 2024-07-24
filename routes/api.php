<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;


Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

     Route::get('/recipes', [RecipeController::class, 'getAllRecipes'])->middleware('auth:api')->name('api.recipes.index');
    Route::get('/recipes/user/{id}', [RecipeController::class, 'getRecipesByUser'])->middleware('auth:api')->name('api.recipes.user');
    Route::post('/recipes', [RecipeController::class, 'create'])->middleware('auth:api')->name('api.recipes.create');
    Route::put('/recipes/{id}', [RecipeController::class, 'update'])->middleware('auth:api')->name('api.recipes.update');
    Route::delete('/recipes/{id}', [RecipeController::class, 'delete'])->middleware('auth:api')->name('api.recipes.delete');

    Route::get('/comments/{recipeId}', [CommentController::class, 'index'])->middleware('auth:api')->name('api.comments.index');
    Route::post('/comments', [CommentController::class, 'create'])->middleware('auth:api')->name('api.comments.create');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->middleware('auth:api')->name('api.comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'delete'])->middleware('auth:api')->name('api.comments.delete');
});