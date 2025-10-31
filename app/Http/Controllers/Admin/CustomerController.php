<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->withCount('orders');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtro: aniversariantes do mês corrente
        if ($request->boolean('birthday_month')) {
            $query->whereNotNull('birth_date')
                  ->whereMonth('birth_date', now()->month);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Calcular estatísticas
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();
        
        return view('admin.customers.index', compact('customers', 'totalCustomers', 'activeCustomers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        // Normaliza máscaras antes de validar
        $request->merge([
            'cpf' => $request->cpf ? preg_replace('/\D/', '', $request->cpf) : null,
            'cnpj' => $request->cnpj ? preg_replace('/\D/', '', $request->cnpj) : null,
            'phone' => $request->phone ? preg_replace('/\D/', '', $request->phone) : null,
            'cep' => $request->zip_code ? preg_replace('/\D/', '', $request->zip_code) : null,
        ]);

        $validated = $request->validate([
            'person_type' => 'required|in:PF,PJ',
            'name' => 'required|string|max:255',
            'cpf' => 'nullable|string|size:11|unique:customers,cpf',
            'cnpj' => 'nullable|string|size:14|unique:customers,cnpj',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'birth_date' => 'nullable|date',
            'cep' => 'nullable|string|max:9',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'phone' => ['nullable','string','max:30','regex:/^\d{10,11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Regras condicionais para PF/PJ
        if ($validated['person_type'] === 'PF') {
            if (empty($validated['cpf'])) {
                return back()->withErrors(['cpf' => 'CPF é obrigatório para pessoa física.'])->withInput();
            }
            $cpfNum = preg_replace('/\D/', '', $validated['cpf']);
            if (!$this->validateCPF($cpfNum)) {
                return back()->withErrors(['cpf' => 'CPF inválido.'])->withInput();
            }
            $validated['cpf'] = $cpfNum;
            $validated['cnpj'] = null;
        } else {
            if (empty($validated['cnpj'])) {
                return back()->withErrors(['cnpj' => 'CNPJ é obrigatório para pessoa jurídica.'])->withInput();
            }
            $cnpjNum = preg_replace('/\D/', '', $validated['cnpj']);
            if (!$this->validateCNPJ($cnpjNum)) {
                return back()->withErrors(['cnpj' => 'CNPJ inválido.'])->withInput();
            }
            $validated['cnpj'] = $cnpjNum;
            $validated['cpf'] = null;
        }

        // Já normalizado acima
        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['address'])) {
            $parts = array_filter([
                $validated['street'] ?? null,
                $validated['number'] ?? null,
                $validated['complement'] ?? null,
                $validated['district'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['cep'] ?? null,
            ]);
            $validated['address'] = implode(', ', $parts);
        }

        Customer::create($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Cliente criado com sucesso!');
    }

    public function show(Customer $customer)
    {
        $customer->load('orders')->loadCount('orders');
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Normaliza máscaras antes de validar
        $request->merge([
            'cpf' => $request->cpf ? preg_replace('/\D/', '', $request->cpf) : null,
            'cnpj' => $request->cnpj ? preg_replace('/\D/', '', $request->cnpj) : null,
            'phone' => $request->phone ? preg_replace('/\D/', '', $request->phone) : null,
            'cep' => ($request->zip_code ? preg_replace('/\D/', '', $request->zip_code) : null) ?? ($request->cep ? preg_replace('/\D/', '', $request->cep) : null),
        ]);

        $validated = $request->validate([
            'person_type' => 'required|in:PF,PJ',
            'name' => 'required|string|max:255',
            'cpf' => 'nullable|string|size:11|unique:customers,cpf,' . $customer->id,
            'cnpj' => 'nullable|string|size:14|unique:customers,cnpj,' . $customer->id,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'birth_date' => 'nullable|date',
            'cep' => 'nullable|string|max:9',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'phone' => ['nullable','string','max:30','regex:/^\d{10,11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Regras condicionais PF/PJ
        if ($validated['person_type'] === 'PF') {
            if (empty($validated['cpf'])) {
                return back()->withErrors(['cpf' => 'CPF é obrigatório para pessoa física.'])->withInput();
            }
            $cpfNum = preg_replace('/\D/', '', $validated['cpf']);
            if (!$this->validateCPF($cpfNum)) {
                return back()->withErrors(['cpf' => 'CPF inválido.'])->withInput();
            }
            $validated['cpf'] = $cpfNum;
            $validated['cnpj'] = null;
        } else {
            if (empty($validated['cnpj'])) {
                return back()->withErrors(['cnpj' => 'CNPJ é obrigatório para pessoa jurídica.'])->withInput();
            }
            $cnpjNum = preg_replace('/\D/', '', $validated['cnpj']);
            if (!$this->validateCNPJ($cnpjNum)) {
                return back()->withErrors(['cnpj' => 'CNPJ inválido.'])->withInput();
            }
            $validated['cnpj'] = $cnpjNum;
            $validated['cpf'] = null;
        }

        // Já normalizado acima

        if (empty($validated['address'])) {
            $parts = array_filter([
                $validated['street'] ?? null,
                $validated['number'] ?? null,
                $validated['complement'] ?? null,
                $validated['district'] ?? null,
                $validated['city'] ?? null,
                $validated['state'] ?? null,
                $validated['cep'] ?? null,
            ]);
            $validated['address'] = implode(', ', $parts);
        }

        $customer->update($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    private function validateCPF(string $cpf): bool
    {
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
        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        $tamanho = [5,4,3,2,9,8,7,6,5,4,3,2];
        for ($i = 0, $soma = 0; $i < 12; $i++) { $soma += $cnpj[$i] * $tamanho[$i]; }
        $resto = $soma % 11; $dig1 = ($resto < 2) ? 0 : 11 - $resto;
        $tamanho = [6,5,4,3,2,9,8,7,6,5,4,3,2];
        for ($i = 0, $soma = 0; $i < 13; $i++) { $soma += $cnpj[$i] * $tamanho[$i]; }
        $resto = $soma % 11; $dig2 = ($resto < 2) ? 0 : 11 - $resto;
        return ($cnpj[12] == $dig1 && $cnpj[13] == $dig2);
    }

    public function destroy(Customer $customer)
    {
        if ($customer->orders()->exists()) {
            return back()->withErrors(['error' => 'Não é possível excluir: o cliente possui pedidos associados.']);
        }
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Cliente excluído com sucesso!');
    }

    public function toggle(Customer $customer)
    {
        $customer->is_active = !$customer->is_active;
        $customer->save();
        return back()->with('success', 'Status do cliente atualizado.');
    }
}


