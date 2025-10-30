<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->get();

        return view('orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_cpf' => 'required|string|size:11',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'observations' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'customer_cpf.size' => 'O CPF deve conter exatamente 11 dígitos.',
            'items.required' => 'Selecione pelo menos um produto.',
            'items.*.product_id.exists' => 'Um dos produtos selecionados é inválido.',
            'items.*.quantity.min' => 'A quantidade deve ser maior que zero.',
        ]);

        // Validação de CPF
        $cpf = preg_replace('/\D/', '', $request->customer_cpf);
        if (!$this->validateCPF($cpf)) {
            $validator->errors()->add('customer_cpf', 'CPF inválido.');
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->all();
            $data['customer_cpf'] = $cpf;
            
            $order = $this->orderService->createOrder($data);

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Pedido realizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao processar pedido: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function success($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        return view('orders.success', compact('order'));
    }

    private function validateCPF(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}

