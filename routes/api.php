<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->get('/user', function (Request $request) {
    return new \App\Http\Resources\User\ProfileResource($request->user());
});

Route::get('/user/{user}', function (User $user) {
    return new \App\Http\Resources\User\InfoResource($user);
});


Route::post('/gbook/posts/create',\App\Http\Controllers\API\Gbook\StoreController::class)
    ->middleware('logged','verified','not_banned')->middleware('throttle:4,1');
Route::get('/gbook/post/{post}/report', \App\Http\Controllers\API\Gbook\ReportController::class)
    ->middleware('logged','verified','not_banned')->middleware('throttle:5,60');

Route::group(['namespace' => 'App\Http\Controllers\API\Gbook', 'prefix' => 'gbook'], function () {
    Route::get('/posts', 'IndexController');
    Route::get('/post/{post}', 'ShowPostController');
    Route::delete('/post/{post}', 'DeletePostController')->middleware('post_owner');
    Route::post('/posts', 'IndexController');
    Route::get('/posts/{user}', 'IndexController');
    Route::post('/posts/{user}', 'IndexController');
});

