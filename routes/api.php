<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('books')->group(function(){
    Route::get('/all', [BooksController::class, 'AllBooks']);
    Route::post('/create', [BooksController::class, 'AddBook']);
    Route::get('/byId', [BooksController::class, 'GetBookById']);
    Route::delete('/delete', [BooksController::class, 'DeleteBookById']);
});

Route::middleware('esp')->prefix('user')->group(function(){
    Route::post('/create', [UserController::class, 'AddNewUser']);
    Route::post('/login', [UserController::class, 'LoginUser']);
});