<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest:web', 'preventBackHistory'])->name('auth.')->group(function () {
    Artisan::call('view:clear');
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/store', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::middleware(['auth:web', 'preventBackHistory'])->name('app.')->group(function () {

    // Users
    Route::prefix('u')->name('user.')->group(function () {
        Route::get('/home', [UserController::class, 'index'])->name('home');
        Route::get('/config', [ConfigController::class, 'index'])->name('config');
        Route::get('/config/user/device', [UserController::class, 'userDevice'])->name('config.user.device');
    });
    
    // Admin
    Route::prefix('a')->name('admin.')->group(function () {
        //home
        Route::get('/home', [AdminController::class, 'index'])->name('home');
        //user
        Route::get('/user', [AdminController::class, 'user'])->name('user');
        Route::post('/user/store', [AdminController::class, 'userStore'])->name('user.store');
        Route::get('/user/list', [AdminController::class, 'userList'])->name('user.list');
        //group
        Route::get('/group', [GroupController::class, 'index'])->name('group');
        Route::post('/group/store', [GroupController::class, 'store'])->name('group.store');
        Route::get('/group/list', [GroupController::class, 'list'])->name('group.list');
        Route::put('/group/update', [GroupController::class, 'update'])->name('group.update');
        // config
        Route::get('/config/list', [ConfigController::class, 'list'])->name('config.list');
    });
    
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


