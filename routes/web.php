<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChannelSourceController;
use App\Http\Controllers\MediaProjectController;
use App\Http\Controllers\MediaSourceController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route for normal user (isAuthenticated)
Route::middleware(['isAuthenticated'])->group(function () {

    Route::get('/', function () {
        return view('layouts.homepage');
    });

    Route::get('/project/index', [MediaProjectController::class, 'index'])->name('media-project-index');
    Route::get('/project/edit/{id}', [MediaProjectController::class, 'edit'])->name('media-project-edit');
    Route::post('/project/update/{id}', [MediaProjectController::class, 'update'])->name('media-project-update');
    Route::get('/project/create', [MediaProjectController::class, 'create'])->name('media-project-create');
    Route::post('/project/store', [MediaProjectController::class, 'store'])->name('media-project-store');
    Route::get('/project-list', [MediaProjectController::class, 'listMediaProjects'])->name('media-project-list');

    Route::get('/source/index', [MediaSourceController::class, 'index'])->name('media-source-index');
    Route::get('/source/edit/{id}', [MediaSourceController::class, 'edit'])->name('media-source-edit');
    Route::post('/source/update/{id}', [MediaSourceController::class, 'update'])->name('media-source-update');
    Route::get('/source/create', [MediaSourceController::class, 'create'])->name('media-source-create');
    Route::post('/source/store', [MediaSourceController::class, 'store'])->name('media-source-store');

    Route::get('/source-retry-download/{id}', [MediaSourceController::class, 'retryDownload'])->name('media-source-retry-download');
    Route::post('/source-retry-cut/{id}', [MediaSourceController::class, 'retryCut'])->name('media-source-retry-cut');
    Route::post('/source-retry-upload/{id}', [MediaSourceController::class, 'retryUpload'])->name('media-source-retry-upload');

    Route::get('/source-view-video-cutted/{id}', [MediaSourceController::class, 'viewVideoCutted'])->name('media-source-view-video-cutted');
    Route::get('/source-view-video-downloaded/{id}', [MediaSourceController::class, 'viewVideoDownloaded'])->name('media-source-view-video-downloaded');
    Route::get('/source-view-video/{id}', [MediaSourceController::class, 'viewVideo'])->name('media-source-view-video');


    Route::middleware(['isAdmin'])->group(function () {

        Route::get('/app/index', [ApplicationController::class, 'index'])->name('app-index');
        Route::get('/app/edit/{id}', [ApplicationController::class, 'edit'])->name('app-edit');
        Route::post('/app/update/{id}', [ApplicationController::class, 'update'])->name('app-update');
        Route::get('/app/create', [ApplicationController::class, 'create'])->name('app-create');
        Route::post('/app/store', [ApplicationController::class, 'store'])->name('app-store');
    
        Route::get('/channel-source/index', [ChannelSourceController::class, 'index'])->name('channel-source-index');
        Route::get('/channel-source/edit/{id}', [ChannelSourceController::class, 'edit'])->name('channel-source-edit');
        Route::post('/channel-source/update/{id}', [ChannelSourceController::class, 'update'])->name('channel-source-update');
        Route::get('/channel-source/create', [ChannelSourceController::class, 'create'])->name('channel-source-create');
        Route::post('/channel-source/store', [ChannelSourceController::class, 'store'])->name('channel-source-store');
        Route::get('/channel-source-list', [ChannelSourceController::class, 'listChannelSources'])->name('channel-source-list');
    
        Route::get('/user/index', [UserController::class, 'index'])->name('user-index');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user-edit');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user-update');
        Route::get('/user/create', [UserController::class, 'create'])->name('user-create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user-store');
    
    });

    
});





Route::get('/login', [UserAuthController::class, 'index'])->name('login');

Route::post('/login', [UserAuthController::class, 'login'])->name('post-login');
Route::get('/logout', [UserAuthController::class, 'logout'])->name('logout');
