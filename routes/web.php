<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('home', [HomeController::class, 'index'])->name('home');
});

Route::middleware(['auth', 'checkMemoOwner'])->group(function () {
    Route::post('store', [HomeController::class, 'store'])->name('store');
    Route::get('edit/{id}', [HomeController::class, 'edit'])->name('edit');
    Route::post('update', [HomeController::class, 'update'])->name('update');
    Route::post('delete', [HomeController::class, 'delete'])->name('delete');
});
