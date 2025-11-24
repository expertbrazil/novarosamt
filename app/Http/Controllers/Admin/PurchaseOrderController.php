<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Mail\PurchaseOrderMail;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::withCount('items')->with('creator');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $maybeId = (int) ltrim($search, '#');
                if ($maybeId > 0) {
                    $q->orWhere('id', $maybeId);
                }
                $q->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $purchaseOrders = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => PurchaseOrder::count(),
            'draft' => PurchaseOrder::where('status', PurchaseOrder::STATUS_DRAFT)->count(),
            'sent' => PurchaseOrder::where('status', PurchaseOrder::STATUS_SENT)->count(),
        ];

        return view('admin.purchase-orders.index', compact('purchaseOrders', 'stats'));
    }

    public function create()
    {
        $initialItems = Product::where('is_active', true)
            ->whereNotNull('min_stock')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                $suggestedQuantity = max(($product->min_stock ?? 0) - $product->stock, 1);

                return [
                    'product_id' => $product->id,
                    'quantity' => $suggestedQuantity,
                ];
            })
            ->values()
            ->toArray();

        return view('admin.purchase-orders.create', [
            'categories' => $this->getFormCategories(),
            'initialItems' => $initialItems,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ], [
            'items.required' => 'Adicione ao menos um produto no pedido de compra.',
        ]);

        DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::create([
                'status' => PurchaseOrder::STATUS_DRAFT,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $itemData) {
                $purchaseOrder->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.purchase-orders.index')
                ->with('success', 'Pedido de compra criado com sucesso!');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Erro ao criar pedido de compra: ' . $th->getMessage()])
                ->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product.category', 'creator');

        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product');

        $initialItems = $purchaseOrder->items
            ->map(fn ($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ])
            ->values()
            ->toArray();

        return view('admin.purchase-orders.edit', [
            'purchaseOrder' => $purchaseOrder,
            'categories' => $this->getFormCategories(),
            'initialItems' => $initialItems,
        ]);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [PurchaseOrder::STATUS_DRAFT, PurchaseOrder::STATUS_SENT]),
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ], [
            'items.required' => 'Adicione ao menos um produto no pedido de compra.',
        ]);

        DB::transaction(function () use ($purchaseOrder, $validated) {
            $purchaseOrder->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $purchaseOrder->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $purchaseOrder->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Pedido de compra atualizado com sucesso!');
    }

    public function sendEmail(Request $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validate([
            'supplier_email' => 'required|email',
        ]);

        try {
            $purchaseOrder->load('items.product');

            Mail::to($data['supplier_email'])
                ->send(new PurchaseOrderMail($purchaseOrder, $data['supplier_email']));

            if ($purchaseOrder->status !== PurchaseOrder::STATUS_SENT) {
                $purchaseOrder->update(['status' => PurchaseOrder::STATUS_SENT]);
            }

            return back()->with('success', 'Pedido enviado ao fornecedor com sucesso!');
        } catch (TransportExceptionInterface $exception) {
            Log::error('Erro ao enviar pedido de compra por e-mail', [
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_email' => $data['supplier_email'],
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withErrors(['email' => 'Não foi possível enviar o e-mail ('. $exception->getMessage() .'). Verifique a configuração do SMTP.'])
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao enviar pedido de compra por e-mail', [
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_email' => $data['supplier_email'],
                'exception' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['email' => 'Erro ao enviar email: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function exportPdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product', 'creator');

        return view('admin.purchase-orders.pdf', [
            'purchaseOrder' => $purchaseOrder,
        ]);
    }

    public function toggleStatus(PurchaseOrder $purchaseOrder)
    {
        $nextStatus = $purchaseOrder->status === PurchaseOrder::STATUS_DRAFT
            ? PurchaseOrder::STATUS_SENT
            : PurchaseOrder::STATUS_DRAFT;

        $purchaseOrder->update(['status' => $nextStatus]);

        return back()->with('success', 'Status atualizado para ' . strtolower($purchaseOrder->status_label) . '.');
    }

    protected function getFormCategories()
    {
        return Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
    }
}

