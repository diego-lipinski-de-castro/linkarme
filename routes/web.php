<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
    //             ->name('password.request');

    // Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    //             ->name('password.email');

    // Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
    //             ->name('password.reset');

    // Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    //             ->name('password.update');
});

Route::middleware('auth')->group(function () {
    // Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
    //             ->name('verification.notice');

    // Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
    //             ->middleware(['signed', 'throttle:6,1'])
    //             ->name('verification.verify');

    // Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
    //             ->middleware('throttle:6,1')
    //             ->name('verification.send');

    // Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
    //             ->name('password.confirm');

    // Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

// site
Route::post('sites/import', [SiteController::class, 'import'])->name('sites.import')->middleware(['auth']); // ok
Route::get('sites/export', [SiteController::class, 'export'])->name('sites.export')->middleware(['auth']); // ok

Route::get('sites/requests', [SiteController::class, 'requests'])->name('sites.requests')->middleware(['auth']);
Route::get('sites/offers', [OfferController::class, 'index'])->name('sites.offers')->middleware(['auth']);

Route::post('sites/offers/{offer}', [OfferController::class, 'accept'])->name('offers.accept')->middleware(['auth']);
Route::delete('sites/offers/{offer}', [OfferController::class, 'reject'])->name('offers.reject')->middleware(['auth']);

Route::resource('sites', SiteController::class)->except(['destroy'])->middleware(['auth']);

Route::get('sites/{site}/aprovar', [SiteController::class, 'approve'])->withTrashed()->name('sites.approve')->middleware(['auth']);
Route::get('sites/{site}/rejeitar', [SiteController::class, 'reject'])->withTrashed()->name('sites.reject')->middleware(['auth']);

Route::delete('sites/{site}', [SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth']);
Route::post('sites/{site}/toggle', [SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth']);

// orders
Route::get('orders/import', [OrderController::class, 'import'])->name('orders.import')->middleware(['auth']);
Route::post('orders/import', [OrderController::class, 'importSubmit'])->name('orders.importSubmit')->middleware(['auth']);

Route::resource('orders', OrderController::class)->middleware(['auth']);

// clients
Route::resource('clients', ClientController::class)->middleware(['auth']);

// sellers
Route::resource('sellers', SellerController::class)->middleware(['auth']);

// misc
Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('languages', LanguageController::class)->middleware(['auth']);
Route::resource('countries', CountryController::class)->middleware(['auth']);

require __DIR__.'/client.php';
require __DIR__.'/seller.php';