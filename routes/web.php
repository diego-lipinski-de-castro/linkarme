<?php

use App\Http\Controllers\SubmitSiteController;
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

Route::get('/enviar-site', [SubmitSiteController::class, 'create'])->name('submitSite.create');
Route::post('/enviar-site', [SubmitSiteController::class, 'store']);
Route::get('/enviar-site-sucesso', [SubmitSiteController::class, 'index'])->name('submitSite.success');

Route::redirect('/clientes/sites', '/sites');
Route::redirect('/clientes/login', '/login');
Route::redirect('/clientes/register', '/register');

require __DIR__.'/admin.php';
require __DIR__.'/client.php';
require __DIR__.'/seller.php';
