<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Category;
use App\Models\Customer;
use App\Services\OrderService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected WhatsAppService $whatsappService
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
            }])
            ->get()
            ->filter(function ($category) {
                return $category->products->isNotEmpty();
            })
            ->values();

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
        $order->load('items.product.category');
        return view('admin.orders.show', compact('order'));
    }

    public function pdf(Order $order)
    {
        $order->load('items.product.category');
        return view('admin.orders.pdf', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'required|in:pendente,aguardando_pagamento,aprovado,entregue,cancelado',
            ]);

            $this->orderService->updateOrderStatus($order, $request->status);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Status do pedido atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar status: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function toggleStatus(Order $order)
    {
        try {
            $nextStatus = $order->getNextStatus();
            
            if ($nextStatus === null) {
                return back()->withErrors(['error' => 'Este pedido já está no status final e não pode ser alterado.']);
            }

            $this->orderService->updateOrderStatus($order, $nextStatus);

            return back()->with('success', 'Status do pedido atualizado para: ' . $order->status_label);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Order $order)
    {
        $order->load('items.product.category');
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        
        // Encontrar o cliente do pedido
        $orderCustomer = Customer::where('cpf', $order->customer_cpf)
            ->orWhere('cnpj', $order->customer_cpf)
            ->orWhere('email', $order->customer_email)
            ->first();
        
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->filter(function ($category) {
                return $category->products->isNotEmpty();
            })
            ->values();

        return view('admin.orders.edit', [
            'order' => $order,
            'customers' => $customers,
            'categories' => $categories,
            'orderCustomer' => $orderCustomer,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'observations' => 'nullable|string',
            'due_date' => 'nullable|date',
            'payment_method' => 'nullable|string|in:pix,credito,debito,dinheiro,boleto,transferencia',
            'status' => 'required|in:pendente,aguardando_pagamento,aprovado,entregue,cancelado',
        ], [
            'items.required' => 'Selecione pelo menos um produto.',
        ]);

        try {
            $customer = Customer::findOrFail($request->customer_id);
            
            // Atualizar dados do pedido
            $order->update([
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'customer_cpf' => $customer->person_type === 'PF' ? $customer->cpf : $customer->cnpj,
                'customer_address' => $this->buildAddressString($customer),
                'observations' => $request->observations,
                'due_date' => $request->due_date ?: null,
                'payment_method' => $request->payment_method ?: null,
                'status' => $request->status,
            ]);

            // Recalcular total e atualizar itens
            $total = 0;
            $order->items()->delete(); // Remove itens antigos

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $unitPrice = $product->sale_price ?? $product->price;
                $subtotal = $unitPrice * $itemData['quantity'];
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pedido atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao atualizar pedido: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Order $order)
    {
        try {
            $orderId = $order->id;
            $order->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', "Pedido #{$orderId} excluído com sucesso!");
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao excluir pedido: ' . $e->getMessage()]);
        }
    }

    public function sendWhatsApp(Order $order)
    {
        try {
            $success = $this->whatsappService->sendOrder($order);
            
            if ($success) {
                return back()->with('success', 'Mensagem enviada via WhatsApp com sucesso!');
            }
            
            return back()->withErrors(['error' => 'Erro ao enviar mensagem via WhatsApp. Verifique as configurações.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao enviar WhatsApp: ' . $e->getMessage()]);
        }
    }

    public function reverseStock(Order $order)
    {
        try {
            $reversed = $this->orderService->reverseStockForCancelledOrder($order);
            
            if ($reversed) {
                return back()->with('success', 'Estoque revertido com sucesso!');
            }
            
            return back()->with('info', 'Não havia movimentações de estoque para reverter ou já foram revertidas anteriormente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao reverter estoque: ' . $e->getMessage()]);
        }
    }

    private function buildAddressString(Customer $customer): string
    {
        $addressParts = array_filter([
            $customer->street,
            $customer->number,
            $customer->complement,
            $customer->district,
            $customer->city,
            $customer->state,
            $customer->cep ? 'CEP: ' . $customer->cep : null
        ]);
        return implode(', ', $addressParts);
    }
}

