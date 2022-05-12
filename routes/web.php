<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SiteController;
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

Route::redirect('/', '/login');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::resource('sites', SiteController::class)->middleware(['auth']);
Route::resource('orders', OrderController::class)->middleware(['auth']);

Route::resource('sellers', SellerController::class)->middleware(['auth']);
Route::resource('clients', ClientController::class)->middleware(['auth']);

Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('languages', LanguageController::class)->middleware(['auth']);
Route::resource('countries', CountryController::class)->middleware(['auth']);

require __DIR__.'/auth.php';
