<?php

use App\Http\Controllers\MediaProjectController;
use App\Http\Controllers\MediaSourceController;
use App\Http\Controllers\UserAuthController;
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

    Route::get('/source/index', [MediaSourceController::class, 'index'])->name('media-source-index');
    Route::get('/source/edit/{id}', [MediaSourceController::class, 'edit'])->name('media-source-edit');
    Route::post('/source/update/{id}', [MediaSourceController::class, 'update'])->name('media-source-update');
    Route::get('/source/create', [MediaSourceController::class, 'create'])->name('media-source-create');
    Route::post('/source/store', [MediaSourceController::class, 'store'])->name('media-source-store');
});



Route::get('/login', [UserAuthController::class, 'index'])->name('login');

Route::post('/login', [UserAuthController::class, 'login'])->name('post-login');
Route::get('/logout', [UserAuthController::class, 'logout'])->name('logout');
