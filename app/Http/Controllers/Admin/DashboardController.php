<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Customer;

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

        return view('admin.dashboard', compact('stats', 'recentOrders', 'birthdaysCount'));
    }
}

