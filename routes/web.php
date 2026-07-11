<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TemplatesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Cms\FileManagerController;


// LOGIN / LOGOUT
Route::view('/cms','system.auth')->name('cms.auth');
Route::post('/cms/login', [AuthController::class, 'login'])->name('cms.login');
Route::get('/cms/logout', [AuthController::class, 'logout'])->name('cms.logout');

Route::middleware('admin')->group(function(){

    // FILEMANAGER
    Route::prefix('cms/filemanager')
        ->group(function () {
            Route::post('/fileslist', [FileManagerController::class, 'filesList']);
            Route::post('/upload', [FileManagerController::class, 'upload']);
            Route::post('/createdir', [FileManagerController::class, 'createDir']);
            Route::post('/dirtree', [FileManagerController::class, 'dirTree']);
            Route::post('/deletefile', [FileManagerController::class, 'deleteFile']);
            Route::post('/deletedir', [FileManagerController::class, 'deleteDir']);
            Route::post('/renamedir', [FileManagerController::class, 'renameDir']);
            Route::post('/renamefile', [FileManagerController::class, 'renameFile']);
            Route::post('/movefile', [FileManagerController::class, 'moveFile']);
            Route::post('/copyfile', [FileManagerController::class, 'copyFile']);
            Route::post('/movedir', [FileManagerController::class, 'moveDir']);
            Route::post('/copydir', [FileManagerController::class, 'copyDir']);
            Route::get('/download', [FileManagerController::class, 'download']);
            Route::get('/thumb', [FileManagerController::class, 'thumb']);
            //Route::get('/downloaddir', [FileManagerController::class, 'downloadDir']);
        });

    // ADMINS
    Route::controller(UsersController::class)->group(function(){
        Route::get('/cms/users', 'index')->name('cms.users.index');
        Route::post('/cms/users/create', 'createUserAdmin')->name('cms.users.create');
        Route::get('/cms/users/edit/{id}', 'userEdit')->name('cms.users.edit')->where('id','[0-9]+');
        Route::put('/cms/users/save/{user_for_save}', 'save')->name('cms.users.update')->whereNumber('user_for_save');
        Route::delete('/cms/users/delete/{id}', 'deleteUser')->name('cms.users.delete');
    });

    // TEMPLATES
    Route::controller(TemplatesController::class)->group(function(){
        Route::get('/cms/templates', 'index')->name('cms.templates.index');
        Route::post('/cms/templates/add', 'addTemplate')->name('cms.templates.add');
        Route::get('/cms/templates/{id}', 'templateEdit')->name('cms.templates.edit')->where('id','[0-9]+');
        Route::put('/cms/templates/save/{id}', 'templateSave')->name('cms.templates.save')->where('id','[0-9]+');
        Route::delete('/cms/templates/delete/{id}', 'templateDelete')->name('cms.templates.delete')->where('id','[0-9]+');
    });

    // DASHBOARD
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/cms/dashboard', 'index')->name('cms.dashboard.index');
        Route::post('/cms/dashboard/addpage', 'addPage')->name('cms.dashboard.addpage');
        Route::put('/cms/dashboard/save','save')->name('cms.dashboard.update');
        Route::delete('/cms/dashboard/delete/{id}', 'deletePage')->name('cms.dashboard.delete')->whereNumber('id');
    });

    // EDIT PAGES
    Route::controller(AdminController::class)->group(function() {
        Route::get('/cms/page/{id}', 'index')->name('cms.page.index')->where('id', '[0-9]+');
        Route::post('/cms/page/create/{parent}', 'createPage')->name('cms.page.create')->whereNumber('parent');
        Route::put('/cms/page/save/{id}', 'savePage')->name('cms.page.update')->where('id', '[0-9]+');
        Route::delete('/cms/pages/delete/{id}', 'deletePage')->name('cms.page.delete')->whereNumber('id');
    });
});

// HOME / PAGES
Route::get('/', [PageController::class, 'showPage']);
//Route::get('/{lang}/{link}', 'PageController@showPage');
//Route::get('/{link?}', [PageController::class, 'showPage'])->where('link','[.*]');
Route::fallback([PageController::class, 'showPage']);