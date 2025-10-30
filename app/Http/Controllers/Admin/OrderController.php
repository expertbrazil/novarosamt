<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\Customer;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
    }

    public function index(Request $request)
    {
        $query = Order::with('items.product');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_cpf', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $selectedCustomerId = $request->integer('customer_id');
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])->get();

        return view('admin.orders.create', [
            'customers' => $customers,
            'categories' => $categories,
            'selectedCustomerId' => $selectedCustomerId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'observations' => 'nullable|string',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|string|in:pix,credito,debito,dinheiro,boleto,transferencia',
        ], [
            'items.required' => 'Selecione pelo menos um produto.',
        ]);

        try {
            $data = $request->all();
            $data['customer_id'] = (int) $request->customer_id;
            $data['due_date'] = $request->due_date ?: null;
            $data['payment_method'] = $request->payment_method ?: null;

            // Verifica se cliente está ativo
            $customer = Customer::findOrFail($data['customer_id']);
            if (!$customer->is_active) {
                return back()->withErrors(['customer_id' => 'Este cliente está inativo e não pode realizar pedidos.'])->withInput();
            }

            $order = $this->orderService->createOrder($data);
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Pedido criado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao criar pedido: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $this->orderService->updateOrderStatus($order, $request->status);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Status do pedido atualizado com sucesso!');
    }
}

