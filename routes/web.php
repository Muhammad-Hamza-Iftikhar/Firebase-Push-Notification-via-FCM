<?php

use App\Http\Controllers\NotificationController;
use App\Models\User;
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

Route::get('/', function () {
    
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/save-token', [NotificationController::class, 'saveToken'])->name('save.token');
Route::post('/store-token', [NotificationController::class, 'saveToken'])->name('store.token');
Route::post('notification/send', [NotificationController::class, 'sendNotification'])->name('send.notification');
Route::post('notification/store', [NotificationController::class, 'sendNotification'])->name('store.notification');
