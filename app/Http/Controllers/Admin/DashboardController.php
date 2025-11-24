<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pendente')->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'low_stock_products' => Product::where('stock', '<', 10)->count(),
        ];

        $recentOrders = Order::with('items.product')
            ->latest()
            ->take(10)
            ->get();

        $birthdaysCount = Customer::whereNotNull('birth_date')
            ->whereMonth('birth_date', now()->month)
            ->count();

        // Produtos mais vendidos (últimos 30 dias)
        $topProducts = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('order', function($query) {
                $query->where('created_at', '>=', now()->subDays(30))
                      ->whereIn('status', ['aprovado', 'entregue']);
            })
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->product->name ?? 'Produto removido',
                    'quantity' => $item->total_quantity,
                ];
            });

        return view('admin.dashboard', compact('stats', 'recentOrders', 'birthdaysCount', 'topProducts'));
    }
}

