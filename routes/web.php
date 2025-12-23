<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\BackupController;

// PWA Manifest
Route::get('/manifest.json', [ManifestController::class, 'index'])->name('manifest.json');

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categoria/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/produto/{id}', [HomeController::class, 'showProduct'])->name('product.show');

// Rotas do carrinho
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrinho/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrinho', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/carrinho/contador', [CartController::class, 'getCount'])->name('cart.count');

// Rotas de pedido
Route::get('/pedido', [OrderController::class, 'create'])->name('order.create');
Route::post('/pedido', [OrderController::class, 'store'])->name('order.store');
Route::get('/pedido/sucesso/{id}', [OrderController::class, 'success'])->name('order.success');
Route::get('/pedido/buscar-cliente', [OrderController::class, 'findCustomerByCpf'])->name('order.find-customer');

// Autenticação
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rotas administrativas (protegidas por auth e role)
Route::middleware(['auth', 'role:admin|gerente|vendedor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil do usuário (todos os usuários autenticados podem acessar)
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::middleware(['role:admin|gerente'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
        Route::get('products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
        Route::get('products/export/zero-stock', [ProductController::class, 'exportZeroStock'])->name('products.export.zero-stock');
        Route::get('products/export/low-stock', [ProductController::class, 'exportLowStock'])->name('products.export.low-stock');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
        
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [AdminOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{order}/pdf', [AdminOrderController::class, 'pdf'])->name('orders.pdf');
        Route::get('orders/{order}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/toggle-status', [AdminOrderController::class, 'toggleStatus'])->name('orders.toggle-status');
        Route::post('orders/{order}/send-whatsapp', [AdminOrderController::class, 'sendWhatsApp'])->name('orders.send-whatsapp');
        Route::post('orders/{order}/send-email', [AdminOrderController::class, 'sendEmail'])->name('orders.send-email');
        Route::post('orders/{order}/sync-customer', [AdminOrderController::class, 'syncCustomer'])->name('orders.sync-customer');
        Route::post('orders/{order}/reverse-stock', [AdminOrderController::class, 'reverseStock'])->name('orders.reverse-stock');

        // Pedidos de compra
        Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
        Route::post('purchase-orders/{purchaseOrder}/send-email', [PurchaseOrderController::class, 'sendEmail'])->name('purchase-orders.send-email');
        Route::post('purchase-orders/{purchaseOrder}/toggle-status', [PurchaseOrderController::class, 'toggleStatus'])->name('purchase-orders.toggle-status');
        Route::get('purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'exportPdf'])->name('purchase-orders.pdf');
        Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
        Route::put('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');

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

        // Parâmetros do Sistema
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('settings/municipios/{estado}', [SettingsController::class, 'getMunicipios'])->name('settings.municipios');
        
        // Evolution API
        Route::get('evolution-api', [\App\Http\Controllers\Admin\EvolutionApiController::class, 'index'])->name('evolution-api.index');
        Route::put('evolution-api', [\App\Http\Controllers\Admin\EvolutionApiController::class, 'update'])->name('evolution-api.update');
        Route::post('evolution-api/test-connection', [\App\Http\Controllers\Admin\EvolutionApiController::class, 'testConnection'])->name('evolution-api.test-connection');
        Route::post('evolution-api/disconnect', [\App\Http\Controllers\Admin\EvolutionApiController::class, 'disconnect'])->name('evolution-api.disconnect');

        // Backups
        Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('backups', [BackupController::class, 'create'])->name('backups.create');
        Route::post('backups/upload', [BackupController::class, 'upload'])->name('backups.upload');
        Route::get('backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::post('backups/restore', [BackupController::class, 'restore'])->name('backups.restore');
        Route::delete('backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });
});
