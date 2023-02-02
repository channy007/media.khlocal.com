<?php

use App\Http\Controllers\Facebook\FacebookUploadController;
use App\Http\Controllers\Youtube\YoutubeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/youtube-download',[FacebookUploadController::class,'download']);


Route::get('/youtube/video-details',[YoutubeController::class,'getVideoDetails']);
Route::get('/youtube/auto-donwload',[YoutubeController::class,'autoDonwload']);

