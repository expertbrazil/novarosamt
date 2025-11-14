<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;
use App\Models\EstadoMunicipio;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                      ->where('stock', '>', 0);
            }])
            ->get();

        // Buscar dados de entrega
        $companyAddress = Settings::get('company_address', '');
        $deliveryInfo = Settings::get('delivery_info', '');
        
        // Buscar cidades de entrega
        $deliveryCities = [];
        $deliveryCitiesJson = Settings::get('delivery_cities', '[]');
        if ($deliveryCitiesJson) {
            $decoded = json_decode($deliveryCitiesJson, true) ?? [];
            if (is_array($decoded)) {
                foreach ($decoded as $cityData) {
                    $municipioId = $cityData['municipio_id'] ?? $cityData;
                    $municipio = EstadoMunicipio::find($municipioId);
                    if ($municipio) {
                        $deliveryCities[] = $municipio;
                    }
                }
            }
        }

        $useMobileLayout = request()->attributes->get('useMobileLayout', false);
        $viewPath = $useMobileLayout ? 'mobile.home.index' : 'home.index';
        return view($viewPath, compact('categories', 'companyAddress', 'deliveryInfo', 'deliveryCities'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }

    public function showProduct($id)
    {
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $useMobileLayout = request()->attributes->get('useMobileLayout', false);
        $viewPath = $useMobileLayout ? 'mobile.products.show' : 'products.show';
        
        return view($viewPath, compact('product'));
    }
}

