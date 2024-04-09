<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/cms','system/auth');
Route::post('/cms/login', [AuthController::class, 'login']);
Route::get('/cms/logout', [AuthController::class, 'logout']);
Route::get('/cms/dashboard', [DashboardController::class, 'index'])->middleware('admin');
Route::post('/cms/dashboard/addpage', [DashboardController::class, 'addpage'])->middleware('admin');
Route::delete('/cms/delete/{id}', [DashboardController::class, 'deletepage'])->middleware('admin');
Route::get('/cms/edit/{id}', [AdminController::class, 'index'])->where('id','[0-9]+')->middleware('admin');






Route::get('/', function () {
    return view('views/home');
});
//Route::get('/{lang}/{link}', 'PageController@showPage');

