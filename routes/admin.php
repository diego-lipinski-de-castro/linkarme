<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TypeController;
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

Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/sites');

    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->middleware(['guest'])
        ->name('login');
    
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store'])
        ->middleware(['guest']);
    
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->middleware(['auth'])
        ->name('logout');
    
    // Impersonation
    Route::get('/impersonate/take/{id}/{guardName?}/{redirectTo?}', [ImpersonateController::class, 'take'])
        ->middleware(['auth', 'verified'])
        ->name('impersonate');

    Route::get('/impersonate/leave/{redirectTo?}', [ImpersonateController::class, 'leave'])
        ->name('impersonate.leave');
    
    Route::get('audits', [AuditController::class, 'index'])->name('audits.index')->middleware(['auth']);
    Route::get('audits-filter', [AuditController::class, 'filter'])->name('audits.filter')->middleware(['auth']);
    Route::get('audits/{audit}', [AuditController::class, 'show'])->name('audits.show')->middleware(['auth']);
    
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics')->middleware(['auth']);

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware(['auth']);
    Route::post('notifications', [NotificationController::class, 'store'])->middleware(['auth']);
    
    // site
    Route::post('sites/import', [SiteController::class, 'import'])->name('sites.import')->middleware(['auth']); // ok
    Route::get('sites/exportUrls', [SiteController::class, 'exportUrls'])->name('sites.exportUrls')->middleware(['auth']); // ok
    Route::get('sites/exportPending', [SiteController::class, 'exportPending'])->name('sites.exportPending')->middleware(['auth']); // ok
    Route::get('sites/sync', [SiteController::class, 'sync'])->name('sites.sync')->middleware(['auth']); // ok
    
    Route::get('sites/offers', [OfferController::class, 'index'])->name('sites.offers')->middleware(['auth']);
    
    Route::post('sites/offers/{offer}', [OfferController::class, 'accept'])->name('offers.accept')->middleware(['auth']);
    Route::delete('sites/offers/{offer}', [OfferController::class, 'reject'])->name('offers.reject')->middleware(['auth']);
    
    Route::resource('sites', SiteController::class)->except(['destroy'])->middleware(['auth']);
    
    Route::put('sites/{site}/aprovar', [SiteController::class, 'approve'])->withTrashed()->name('sites.approve')->middleware(['auth']);
    Route::put('sites/{site}/rejeitar', [SiteController::class, 'reject'])->withTrashed()->name('sites.reject')->middleware(['auth']);
    
    Route::delete('sites/{site}', [SiteController::class, 'destroy'])->withTrashed()->name('sites.destroy')->middleware(['auth']);
    Route::post('sites/{site}/toggle', [SiteController::class, 'toggle'])->withTrashed()->name('sites.toggle')->middleware(['auth']);
    
    Route::put('sites/{site}/note', [NoteController::class, 'update'])->name('notes.update')->middleware(['auth']);
    
    // orders
    Route::post('orders/import', [OrderController::class, 'import'])->name('orders.import')->middleware(['auth']);
    Route::post('orders/go', [OrderController::class, 'go'])->name('orders.go')->middleware(['auth']);
    
    Route::resource('orders', OrderController::class)->middleware(['auth']);

    // invoices
    Route::get('invoices/{invoice}/generate', [InvoiceController::class, 'generate'])->withTrashed()->name('invoices.generate')->middleware(['auth']);
    Route::resource('invoices', InvoiceController::class)->middleware(['auth']);
    
    // clients
    Route::resource('clients', ClientController::class)->middleware(['auth']);
    
    // sellers
    Route::resource('sellers', SellerController::class)->middleware(['auth']);
    
    // misc
    Route::resource('categories', CategoryController::class)->middleware(['auth']);
    Route::resource('languages', LanguageController::class)->middleware(['auth']);
    Route::resource('countries', CountryController::class)->middleware(['auth']);
    Route::resource('teams', TeamController::class)->middleware(['auth']);
    Route::resource('types', TypeController::class)->middleware(['auth']);
});
