<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\CuadreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Models\Count;
use Illuminate\Support\Facades\DB;
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

Route::controller(AuthController::class)->group(function () {
    Route::get('auth/login', 'login')->name('login');
    Route::post('auth/login', 'store')->name('login.store');
    Route::post('auth/logout', 'logout')->name('auth.logout');
});

/* TODO */
/* Rutas protegidas por Auth */


Route::middleware(['auth'])->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        Route::controller(UserController::class)->group(function () {

            Route::get('users', 'index')->name('users.index');
            Route::get('users/set-permissions/{id}', 'setPermissions')->name('users.setPermissions');
        });

        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
            Route::get('/invoices/create', 'create')->name('invoices.create');
            Route::get('/invoices/orders', 'orders')->name('orders');
            Route::get('/invoices/show/{invoice}', 'show')->name('invoices.show');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::get('/products/sum', 'sum')->name('products.sum');
            Route::get('/products/edit/{product}', 'edit')->name('products.edit');
            Route::get('/products/{product}', 'show')->name('products.show');
        });

        Route::controller(StoreController::class)->group(function () {
            Route::get('/stores', 'index')->name('stores.index');
        });

        Route::controller(ClientController::class)->group(function () {
            Route::get('/clients', 'index')->name('clients.index');
        });
        Route::controller(ProviderController::class)->group(function () {
            Route::get('providers', 'index')->name('providers.index');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings.index');
        });

        Route::controller(RecursoController::class)->group(function () {
            Route::get('recursos', 'index')->name('recursos.index');
            Route::get('recursos/sum', 'sum')->name('recursos.sum');
            Route::get('recursos/{recurso}', 'show')->name('recursos.show');
        });

        Route::controller(ProcesoController::class)->group(function () {
            Route::get('procesos', 'index')->name('procesos.index');
            Route::get('procesos/create', 'create')->name('procesos.create');
            Route::get('procesos/{proceso}', 'show')->name('procesos.show');
        });
        Route::controller(ReportController::class)->group(function () {
            Route::get('general_daily', 'general_daily')->name('reports.general_daily');
            Route::get('general_mayor', 'general_mayor')->name('reports.general_mayor');
            Route::get('catalogue', 'catalogue')->name('reports.catalogue');
            Route::get('view_catalogue', 'view_catalogue')->name('reports.view_catalogue');
        });
        Route::controller(ComprobanteController::class)->group(function () {
            Route::get('comprobantes', 'index')->name('comprobantes.index');
        });

        Route::controller(CuadreController::class)->group(function () {
            Route::get('cuadres', 'index')->name('cuadres.index');
        });
    });
});


Route::get('prueba', function () {

    $model = "App\Models\User";
    dd($model::find(1));

    dd($model::setContable(1,'201','credit'));
    $user=DB::table('users')->select('*')->first();
    dd($user->contable);
    $counts = Count::with('contable')->get();
    dd($counts);
})->name('prueba');
