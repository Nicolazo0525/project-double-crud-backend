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

Route::apiResource('categories', CategoryController::class)->names('categories');
Route::post('category/edit/{id}', [CategoryController::class, 'update']);

Route::get('book/index/{categories_id}', [BookController::class, 'list']);
Route::post('book/edit/{id}', [BookController::class, 'update']);
Route::apiResource('books', BookController::class)->names('books');



