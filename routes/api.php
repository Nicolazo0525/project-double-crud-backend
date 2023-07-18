<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->apiResource('categories', CategoryController::class)->names('categories');
Route::middleware(['auth:sanctum'])->get('search/{user_id}', [CategoryController::class, 'search']);
Route::middleware(['auth:sanctum'])->post('category/edit/{id}', [CategoryController::class, 'update']);

Route::middleware(['auth:sanctum'])->get('book/index/{categories_id}', [BookController::class, 'list']);
Route::middleware(['auth:sanctum'])->post('book/edit/{id}', [BookController::class, 'update']);
Route::middleware(['auth:sanctum'])->apiResource('books', BookController::class)->names('books');

Route::middleware(['auth:sanctum'])->apiResource('cities',CityController::class);


