<?php

use App\Http\Controllers\Client\IndexController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::controller(App\Http\Controllers\Auth\LoginController::class)->group(function (){
    Route::get('google_auth/redirect', 'redirectGoogle')->name('authorize.google');
    Route::get('google_auth/callback', 'callbackGoogle');
    Route::get('vk_auth/redirect', 'redirectVk')->name('authorize.vk');
    Route::get('vk_auth/callback', 'callbackVk');
})->middleware('guest');

Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/delete_image', [ProfileController::class, 'destroyImage'])->name('profile.destroy_image');
});


require __DIR__.'/auth.php';

Route::get('{page}', IndexController::class)->where('page','^(?!admin).*$');
