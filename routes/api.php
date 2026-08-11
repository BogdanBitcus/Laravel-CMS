<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthController;
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



Route::middleware('auth:sanctum')->group(function () {

    /*
GET     /books
GET     /books/{book}
POST    /books
PUT     /books/{book}
PATCH   /books/{book}
DELETE  /books/{book}
*/
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    //PATCH must be here)
    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    //Route::apiResource('books', BookController::class);


    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/tokens', [AuthController::class, 'tokens']);
    Route::delete('/tokens/{id}', [AuthController::class, 'deleteToken']);
    Route::patch('/tokens/{id}', [AuthController::class, 'renameToken']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class,'login']);
