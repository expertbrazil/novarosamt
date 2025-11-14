<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->withCount('orderItems');

        // Filtro de estoque mínimo (tem prioridade)
        if ($request->filled('low_stock') && $request->low_stock == 1) {
            $query->where('is_active', true)
                  ->whereNotNull('min_stock')
                  ->where('min_stock', '>', 0)
                  ->whereColumn('stock', '<=', 'min_stock');
        }

        // Filtro de status (aplicar apenas se não estiver filtrando por estoque mínimo)
        if (!($request->filled('low_stock') && $request->low_stock == 1)) {
            if ($request->filled('status') && $request->status != '') {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }
        }

        // Busca por nome ou descrição
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Filtro por categoria
        if ($request->filled('category_id') && $request->category_id != '') {
            $query->where('category_id', (int) $request->category_id);
        }

        // Filtro "com pedidos"
        if ($request->boolean('with_orders')) {
            $query->whereHas('orderItems');
        }

        // Filtros de faixa de estoque (não aplicar quando filtrando por estoque mínimo)
        if (!($request->filled('low_stock') && $request->low_stock == 1)) {
            if ($request->filled('stock_min') && $request->stock_min !== '') {
                $query->where('stock', '>=', (int) $request->stock_min);
            }
            if ($request->filled('stock_max') && $request->stock_max !== '') {
                $query->where('stock', '<=', (int) $request->stock_max);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::all();
        
        // Contar produtos ATIVOS com estoque abaixo do mínimo
        $lowStockCount = Product::where('is_active', true)
                                  ->whereNotNull('min_stock')
                                  ->where('min_stock', '>', 0)
                                  ->whereColumn('stock', '<=', 'min_stock')
                                  ->count();
        
        // Contar produtos com estoque zerado
        $zeroStockCount = Product::where('stock', '<=', 0)->count();

        return view('admin.products.index', compact('products', 'categories', 'lowStockCount', 'zeroStockCount'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'profit_margin_percent' => 'nullable|numeric|min:0',
            'unit' => 'nullable|in:kg,l,g,ml,cm,un',
            'unit_value' => 'nullable|numeric|min:0.001',
            'min_stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $margin = (float) ($validated['profit_margin_percent'] ?? 0);
        $validated['sale_price'] = round($validated['price'] * (1 + ($margin/100)), 2);

        // Estoque é gerido pelo módulo de estoque; inicia em 0 (default na migration)
        unset($validated['stock']);
        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'profit_margin_percent' => 'nullable|numeric|min:0',
            'unit' => 'nullable|in:kg,l,g,ml,cm,un',
            'unit_value' => 'nullable|numeric|min:0.001',
            'min_stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $margin = (float) ($validated['profit_margin_percent'] ?? 0);
        $validated['sale_price'] = round($validated['price'] * (1 + ($margin/100)), 2);

        // Não permitir edição direta do estoque aqui
        unset($validated['stock']);
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            return back()->withErrors(['error' => 'Não é possível excluir: o produto possui pedidos vinculados. Inative-o.']);
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function toggle(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return back()->with('success', 'Status do produto atualizado.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $orders = \App\Models\OrderItem::with(['order' => function($q){ $q->select('id','customer_name','created_at'); }])
            ->where('product_id', $product->id)
            ->latest()
            ->get();
        $customers = \App\Models\Customer::whereIn('id', function($q) use ($product){
                $q->select('orders.customer_id')
                  ->from('order_items')
                  ->join('orders','orders.id','=','order_items.order_id')
                  ->where('order_items.product_id', $product->id);
            })->get();
        return view('admin.products.show', compact('product','orders','customers'));
    }

    public function exportZeroStock()
    {
        $products = Product::with('category')
            ->where('stock', '<=', 0)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $filename = 'produtos_estoque_zerado_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para abrir corretamente no Excel)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho
            fputcsv($file, [
                'Código',
                'Nome',
                'Categoria',
                'Descrição',
                'Preço',
                'Estoque Atual',
                'Estoque Mínimo',
                'Unidade',
            ], ';');

            // Dados
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name ?? 'Sem categoria',
                    $product->description ?? '',
                    'R$ ' . number_format($product->price, 2, ',', '.'),
                    $product->stock,
                    $product->min_stock,
                    $product->unit ?? '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportLowStock()
    {
        // Filtrar produtos ATIVOS com estoque abaixo ou igual ao mínimo configurado
        // Apenas produtos com status ATIVO (is_active = true)
        $products = Product::with('category')
            ->where('is_active', true)
            ->whereNotNull('min_stock')
            ->where('min_stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $filename = 'produtos_estoque_minimo_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 (para abrir corretamente no Excel)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalho otimizado para envio ao fornecedor
            fputcsv($file, [
                'Código',
                'Nome do Produto',
                'Categoria',
                'Unidade',
                'Quantidade Atual',
                'Estoque Mínimo',
                'Quantidade Necessária',
                'Último Preço de Compra',
                'Data Última Compra',
                'Observações',
            ], ';');

            // Dados
            foreach ($products as $product) {
                // Calcular quantidade necessária (estoque mínimo - estoque atual)
                // Se o estoque estiver zerado, usar o mínimo como quantidade necessária
                $quantidadeNecessaria = max($product->min_stock - $product->stock, $product->min_stock);
                
                // Formatar unidade e valor unitário
                $unidade = '';
                if ($product->unit && $product->unit_value) {
                    $unidade = $product->unit_value . ' ' . $product->unit;
                } elseif ($product->unit) {
                    $unidade = $product->unit;
                }
                
                // Formatar último preço de compra
                $ultimoPreco = '';
                if ($product->last_purchase_cost !== null) {
                    $ultimoPreco = 'R$ ' . number_format($product->last_purchase_cost, 2, ',', '.');
                }
                
                // Formatar data da última compra
                $dataUltimaCompra = '';
                if ($product->last_purchase_at) {
                    $dataUltimaCompra = $product->last_purchase_at->format('d/m/Y');
                }
                
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category->name ?? 'Sem categoria',
                    $unidade,
                    $product->stock,
                    $product->min_stock,
                    $quantidadeNecessaria,
                    $ultimoPreco,
                    $dataUltimaCompra,
                    $product->description ?? '',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function duplicate(Product $product)
    {
        // Criar novo produto com base no original
        $newProduct = $product->replicate();
        
        // Adicionar " (Cópia)" ao nome
        $newProduct->name = $product->name . ' (Cópia)';
        
        // Gerar novo slug
        $newProduct->slug = Str::slug($newProduct->name);
        
        // Garantir que o slug seja único
        $originalSlug = $newProduct->slug;
        $counter = 1;
        while (Product::where('slug', $newProduct->slug)->exists()) {
            $newProduct->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Zerar estoque e dados de última compra
        $newProduct->stock = 0;
        $newProduct->last_purchase_cost = null;
        $newProduct->last_purchase_at = null;
        
        // Não copiar a imagem (deixar sem imagem)
        $newProduct->image = null;
        
        // Salvar o novo produto
        $newProduct->save();
        
        // Redirecionar para a edição do produto duplicado
        return redirect()->route('admin.products.edit', $newProduct)
            ->with('success', 'Produto duplicado com sucesso! Você pode editar as informações agora.');
    }
}

