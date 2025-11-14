<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
        // Permitir acesso para admin/gerente por papel; permissões refinadas se existirem
        $this->middleware('role:admin|gerente');
    }

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user'])
            ->orderByDesc('moved_at');

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }
        if ($request->filled('from')) {
            $query->where('moved_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->where('moved_at', '<=', $request->date('to'));
        }

        // Estatísticas para o período/seleção
        $base = clone $query;
        $totalIn = (clone $base)->whereIn('type', ['in', 'adjustment_in'])->sum('quantity');
        $totalOut = (clone $base)->whereIn('type', ['out', 'adjustment_out'])->sum('quantity');

        $selectedProduct = null;
        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->integer('product_id'));
        }

        // Buscar todas as movimentações para calcular saldo acumulado
        // Primeiro, obter lista de produtos únicos (sem ORDER BY para evitar erro com DISTINCT)
        $baseQueryForProducts = StockMovement::query();
        
        if ($request->filled('product_id')) {
            $baseQueryForProducts->where('product_id', $request->integer('product_id'));
        }
        if ($request->filled('type')) {
            $baseQueryForProducts->where('type', $request->get('type'));
        }
        if ($request->filled('from')) {
            $baseQueryForProducts->where('moved_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $baseQueryForProducts->where('moved_at', '<=', $request->date('to'));
        }
        
        $allProductsInMovements = $baseQueryForProducts->distinct()->pluck('product_id');
        $initialBalances = [];
        
        foreach ($allProductsInMovements as $productId) {
            // Buscar todas as movimentações anteriores às filtradas para este produto
            $initialQuery = StockMovement::where('product_id', $productId);
            
            if ($request->filled('from')) {
                $initialQuery->where('moved_at', '<', $request->date('from'));
            }
            
            $initialIn = (clone $initialQuery)->whereIn('type', ['in', 'adjustment_in'])->sum('quantity');
            $initialOut = (clone $initialQuery)->whereIn('type', ['out', 'adjustment_out'])->sum('quantity');
            $initialBalances[$productId] = $initialIn - $initialOut;
        }
        
        // Agora calcular saldo acumulado para as movimentações filtradas
        $allMovementsForBalance = (clone $query)->orderBy('moved_at')->orderBy('id')->get();
        $balanceMap = [];
        
        foreach ($allMovementsForBalance as $movement) {
            $productId = $movement->product_id;
            
            // Inicializar com saldo anterior se não existir
            if (!isset($balanceMap['_current_' . $productId])) {
                $balanceMap['_current_' . $productId] = $initialBalances[$productId] ?? 0;
            }
            
            // Calcular saldo acumulado até esta movimentação
            if (in_array($movement->type, ['in', 'adjustment_in'])) {
                $balanceMap['_current_' . $productId] += $movement->quantity;
            } else {
                $balanceMap['_current_' . $productId] -= $movement->quantity;
            }
            
            // Armazenar saldo para esta movimentação específica
            $balanceMap[$movement->id] = $balanceMap['_current_' . $productId];
        }

        return view('admin.stock.index', [
            'movements' => $query->orderByDesc('moved_at')->paginate(20)->withQueryString(),
            'products' => Product::orderBy('name')->get(),
            'filters' => $request->only(['product_id', 'type', 'from', 'to']),
            'stats' => [
                'entries' => (int) round($totalIn),
                'exits' => (int) round($totalOut),
                'net' => (int) round($totalIn - $totalOut),
                'product' => $selectedProduct,
                'stock' => $selectedProduct?->stock,
                'last_purchase_cost' => $selectedProduct?->last_purchase_cost,
            ],
            'balanceMap' => $balanceMap,
        ]);
    }

    public function create()
    {
        return view('admin.stock.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['nullable', 'numeric', 'gte:0'],
            'reason' => ['nullable', 'string', 'max:255'],
            'moved_at' => ['nullable', 'date'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $meta = [
            'reason' => $data['reason'] ?? null,
            'moved_at' => $data['moved_at'] ?? now(),
        ];

        if ($data['direction'] === 'in') {
            $unitCost = $data['unit_cost'] ?? null;
            if ($unitCost === null) {
                return back()->withErrors(['unit_cost' => 'Obrigatório para entrada.'])->withInput();
            }
            $this->stockService->registerEntry($product, (float) $data['quantity'], (float) $unitCost, $meta);
        } else {
            $this->stockService->registerExit($product, (float) $data['quantity'], $meta);
        }

        return redirect()->route('admin.stock.index')->with('status', 'Movimentação registrada com sucesso.');
    }
}


