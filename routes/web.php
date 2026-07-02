<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplatesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

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

Route::view('/cms','system.auth');
Route::post('/cms/login', [AuthController::class, 'login']);
Route::get('/cms/logout', [AuthController::class, 'logout']);

Route::get('/cms/dashboard', [DashboardController::class, 'index'])->middleware('admin');
Route::post('/cms/dashboard/addpage', [DashboardController::class, 'addpage'])->middleware('admin');
Route::put('/cms/dashboard/save',[DashboardController::class, 'save'])->middleware('admin');
Route::delete('/cms/dashboard/delete/{id}', [DashboardController::class, 'deletepage'])->middleware('admin');

Route::get('/cms/templates', [TemplatesController::class, 'index'])->middleware('admin');
Route::post('/cms/templates/add', [TemplatesController::class, 'addTemplate'])->middleware('admin');
Route::get('/cms/templates/{id}', [TemplatesController::class, 'templateEdit'])->where('id','[0-9]+')->middleware('admin');
Route::put('/cms/templates/save/{id}', [TemplatesController::class, 'templateSave'])->where('id','[0-9]+')->middleware('admin');
Route::delete('/cms/templates/delete/{id}', [TemplatesController::class, 'templateDelete'])->where('id','[0-9]+')->middleware('admin');

Route::get('/cms/users', [UsersController::class, 'index'])->middleware('admin');
Route::post('/cms/users/create', [UsersController::class, 'createUserAdmin'])->middleware('admin');
Route::get('/cms/users/{id}', [UsersController::class, 'userEdit'])->where('id','[0-9]+')->middleware('admin');
Route::put('/cms/users/save/{user_for_save}',[UsersController::class, 'save'])->whereNumber('user_for_save')->middleware('admin');
//Route::delete('/cms/users/delete/{id}', [UsersController::class, 'deleteuser'])->middleware('admin');

Route::get('/cms/edit/{id}', [AdminController::class, 'index'])->where('id','[0-9]+')->middleware('admin');



Route::get('/', [PageController::class, 'showPage']);

//Route::get('/{lang}/{link}', 'PageController@showPage');
//Route::get('/{link?}', [PageController::class, 'showPage'])->where('link','[.*]');
Route::fallback([PageController::class, 'showPage']);