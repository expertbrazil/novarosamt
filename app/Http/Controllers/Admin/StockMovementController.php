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

        return view('admin.stock.index', [
            'movements' => $query->paginate(20)->withQueryString(),
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


