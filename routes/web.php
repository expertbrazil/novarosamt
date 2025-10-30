<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StockMovementController;

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categoria/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/pedido', [OrderController::class, 'create'])->name('order.create');
Route::post('/pedido', [OrderController::class, 'store'])->name('order.store');
Route::get('/pedido/sucesso/{id}', [OrderController::class, 'success'])->name('order.success');

// Autenticação
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rotas administrativas (protegidas por auth e role)
Route::middleware(['auth', 'role:admin|gerente|vendedor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware(['role:admin|gerente'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
        
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [AdminOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');
        
        // Rota de teste
        Route::get('customers-test', function() {
            return view('admin.customers.create-test');
        })->name('customers.test');

        // Estoque
        Route::get('stock', [StockMovementController::class, 'index'])->name('stock.index');
        Route::get('stock/create', [StockMovementController::class, 'create'])->name('stock.create');
        Route::post('stock', [StockMovementController::class, 'store'])->name('stock.store');
    });
});
