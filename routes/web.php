<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
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
    Route::get('/', [AuthController::class, 'index'])->name('login.post');
    Route::post('/store', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::get('/details', [MainController::class, 'index'])->name('details');
Route::get('/ip', [MainController::class, 'searchIP']);