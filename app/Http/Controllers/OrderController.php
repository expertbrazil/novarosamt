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
                $query->where('is_active', true)
                      ->where('stock', '>', 0)
                      ->orderBy('name');
            }])
            ->get()
            ->filter(function ($category) {
                return $category->products->isNotEmpty();
            })
            ->values();

        $allProducts = \App\Models\Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('categories', 'allProducts'));
    }

    public function findCustomerByCpf(Request $request)
    {
        // Buscar por CPF
        if ($request->has('cpf')) {
            $cpf = preg_replace('/\D/', '', $request->cpf);
            
            if (strlen($cpf) !== 11) {
                return response()->json(['found' => false]);
            }

            if (!$this->validateCPF($cpf)) {
                return response()->json(['found' => false, 'valid' => false]);
            }

            $customer = \App\Models\Customer::where('cpf', $cpf)->first();
        }
        // Buscar por CNPJ
        elseif ($request->has('cnpj')) {
            $cnpj = preg_replace('/\D/', '', $request->cnpj);
            
            if (strlen($cnpj) !== 14) {
                return response()->json(['found' => false]);
            }

            if (!$this->validateCNPJ($cnpj)) {
                return response()->json(['found' => false, 'valid' => false]);
            }

            $customer = \App\Models\Customer::where('cnpj', $cnpj)->first();
        } else {
            return response()->json(['found' => false]);
        }
        
        if ($customer) {
            // Formatar CEP removendo hífen se existir
            $cep = $customer->cep ? preg_replace('/\D/', '', $customer->cep) : '';
            
            return response()->json([
                'found' => true,
                'customer' => [
                    'person_type' => $customer->person_type ?? 'PF',
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'cpf' => $customer->cpf,
                    'cnpj' => $customer->cnpj,
                    'birth_date' => $customer->birth_date ? $customer->birth_date->format('Y-m-d') : null,
                    'cep' => $cep,
                    'street' => $customer->street,
                    'number' => $customer->number,
                    'complement' => $customer->complement,
                    'district' => $customer->district,
                    'city' => $customer->city,
                    'state' => $customer->state,
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function store(Request $request)
    {
        $personType = $request->customer_person_type ?? 'PF';
        
        $rules = [
            'customer_person_type' => 'required|in:PF,PJ',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_cep' => 'required|string|size:9',
            'customer_street' => 'required|string|max:255',
            'customer_number' => 'required|string|max:20',
            'customer_complement' => 'nullable|string|max:255',
            'customer_district' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_state' => 'required|string|size:2',
            'observations' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];

        if ($personType === 'PF') {
            $rules['customer_cpf'] = 'required|string';
        } else {
            $rules['customer_cnpj'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules, [
            'customer_cpf.required' => 'O CPF é obrigatório.',
            'customer_cnpj.required' => 'O CNPJ é obrigatório.',
            'customer_cep.required' => 'O CEP é obrigatório.',
            'customer_street.required' => 'A rua é obrigatória.',
            'customer_number.required' => 'O número é obrigatório.',
            'customer_district.required' => 'O bairro é obrigatório.',
            'customer_city.required' => 'A cidade é obrigatória.',
            'customer_state.required' => 'O estado é obrigatório.',
            'items.required' => 'Selecione pelo menos um produto.',
            'items.*.product_id.exists' => 'Um dos produtos selecionados é inválido.',
            'items.*.quantity.min' => 'A quantidade deve ser maior que zero.',
        ]);

        // Validação de CPF ou CNPJ
        if ($personType === 'PF') {
            $cpf = preg_replace('/\D/', '', $request->customer_cpf ?? '');
            if (strlen($cpf) !== 11 || !$this->validateCPF($cpf)) {
                $validator->errors()->add('customer_cpf', 'CPF inválido.');
            }
        } else {
            $cnpj = preg_replace('/\D/', '', $request->customer_cnpj ?? '');
            if (strlen($cnpj) !== 14 || !$this->validateCNPJ($cnpj)) {
                $validator->errors()->add('customer_cnpj', 'CNPJ inválido.');
            }
        }

        // Validação de CEP
        $cep = preg_replace('/\D/', '', $request->customer_cep);
        if (strlen($cep) !== 8) {
            $validator->errors()->add('customer_cep', 'CEP inválido.');
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->all();
            if ($personType === 'PF') {
                $data['customer_cpf'] = preg_replace('/\D/', '', $request->customer_cpf);
            } else {
                $data['customer_cnpj'] = preg_replace('/\D/', '', $request->customer_cnpj);
            }
            $data['customer_cep'] = $cep;
            
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

    private function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $length = strlen($cnpj) - 2;
        $numbers = substr($cnpj, 0, $length);
        $digits = substr($cnpj, $length);
        $sum = 0;
        $pos = $length - 7;

        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }

        $result = $sum % 11 < 2 ? 0 : 11 - $sum % 11;
        if ($result != $digits[0]) {
            return false;
        }

        $length = $length + 1;
        $numbers = substr($cnpj, 0, $length);
        $sum = 0;
        $pos = $length - 7;

        for ($i = $length; $i >= 1; $i--) {
            $sum += $numbers[$length - $i] * $pos--;
            if ($pos < 2) {
                $pos = 9;
            }
        }

        $result = $sum % 11 < 2 ? 0 : 11 - $sum % 11;
        if ($result != $digits[1]) {
            return false;
        }

        return true;
    }
}

