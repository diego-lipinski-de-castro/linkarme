<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OfferController;
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

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::redirect('/', '/login');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

Route::get('sites/import', [SiteController::class, 'import'])->name('sites.import')->middleware(['auth']);
Route::post('sites/import', [SiteController::class, 'importSubmit'])->name('sites.importSubmit')->middleware(['auth']);

Route::get('orders/import', [OrderController::class, 'import'])->name('orders.import')->middleware(['auth']);
Route::post('orders/import', [OrderController::class, 'importSubmit'])->name('orders.importSubmit')->middleware(['auth']);

Route::get('sites/requests', [SiteController::class, 'requests'])->name('sites.requests')->middleware(['auth']);
Route::get('sites/offers', [OfferController::class, 'index'])->name('sites.offers')->middleware(['auth']);

Route::post('sites/offers/{offer}', [OfferController::class, 'accept'])->name('offers.accept')->middleware(['auth']);
Route::delete('sites/offers/{offer}', [OfferController::class, 'reject'])->name('offers.reject')->middleware(['auth']);

Route::resource('sites', SiteController::class)->except(['destroy'])->middleware(['auth']);

Route::get('sites/{site}/aprovar', [SiteController::class, 'approve'])->withTrashed()->name('sites.approve')->middleware(['auth']);
Route::get('sites/{site}/rejeitar', [SiteController::class, 'reject'])->withTrashed()->name('sites.reject')->middleware(['auth']);

Route::delete('sites/{site}', [SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth']);
Route::post('sites/{site}/toggle', [SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth']);

Route::resource('orders', OrderController::class)->middleware(['auth']);

Route::resource('sellers', SellerController::class)->middleware(['auth']);
Route::resource('clients', ClientController::class)->middleware(['auth']);

Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('languages', LanguageController::class)->middleware(['auth']);
Route::resource('countries', CountryController::class)->middleware(['auth']);

Route::group([
    'prefix' => 'clientes',
    'as' => 'client.',
], function () {
    Route::get('sites', [\App\Http\Controllers\Client\SiteController::class, 'index'])
        ->name('sites.index')
        ->middleware(['auth:client']);

    Route::post('sites/{site}/favorite', [\App\Http\Controllers\Client\SiteController::class, 'favorite'])
        ->name('sites.favorite')
        ->middleware(['auth:client']);

    Route::get('sites/{site}/edit', [\App\Http\Controllers\Client\SiteController::class, 'edit'])
        ->name('sites.edit')
        ->middleware(['auth:client']);

    Route::put('sites/{site}', [\App\Http\Controllers\Client\SiteController::class, 'update'])
        ->name('sites.update')
        ->middleware(['auth:client']);

    Route::get('orders', [\App\Http\Controllers\Client\OrderController::class, 'index'])
        ->name('orders.index')
        ->middleware(['auth:client']);
});

Route::group([
    'prefix' => 'vendedores',
    'as' => 'seller.',
], function () {
    Route::get('sites', [\App\Http\Controllers\Seller\SiteController::class, 'index'])
        ->name('sites.index')
        ->middleware(['auth:seller']);

    Route::get('sites/adicionar', [\App\Http\Controllers\Seller\SiteController::class, 'create'])
        ->name('sites.create')
        ->middleware(['auth:seller']);

    Route::post('sites', [\App\Http\Controllers\Seller\SiteController::class, 'store'])
        ->name('sites.store')
        ->middleware(['auth:seller']);

    Route::post('sites/offer', [\App\Http\Controllers\Seller\SiteController::class, 'offer'])
        ->name('sites.offer')
        ->middleware(['auth:seller']);

    Route::get('sites/{site}/edit', [\App\Http\Controllers\Seller\SiteController::class, 'edit'])
        ->name('sites.edit')
        ->middleware(['auth:seller']);

    Route::put('sites/{site}', [\App\Http\Controllers\Seller\SiteController::class, 'update'])
        ->name('sites.update')
        ->middleware(['auth:seller']);
    
    Route::delete('sites/{site}', [\App\Http\Controllers\Seller\SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth:seller']);
    Route::post('sites/{site}/toggle', [\App\Http\Controllers\Seller\SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth:seller']);

    Route::get('orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])
        ->name('orders.index')
        ->middleware(['auth:seller']);
});

require __DIR__.'/auth.php';
