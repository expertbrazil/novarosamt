<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Settings;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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

        // Filtro: status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtro: mês de aniversário
        if ($request->filled('birthday_month')) {
            $month = $request->integer('birthday_month');
            if ($month >= 1 && $month <= 12) {
                $query->whereNotNull('birth_date')
                      ->whereMonth('birth_date', $month);
            }
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

    /**
     * Retorna preview da mensagem de aniversário para um cliente
     */
    public function previewBirthdayMessage(Customer $customer)
    {
        if (!$customer->birth_date) {
            return response()->json(['error' => 'Cliente não possui data de nascimento cadastrada.'], 400);
        }

        $age = \Carbon\Carbon::parse($customer->birth_date)->age;
        $birthdayDate = \Carbon\Carbon::parse($customer->birth_date);
        $birthdayFormatted = $birthdayDate->format('d/m');
        
        // Buscar nome da empresa nas configurações
        $companyName = Settings::get('company_name') 
            ?: Settings::get('smtp_from_name') 
            ?: config('app.name', 'Nova Rosa MT');
        
        $message = "🎉 *Parabéns, {$customer->name}!* 🎉\n\n";
        $message .= "Sabemos que seu aniversário foi em {$birthdayFormatted}, mas nunca é tarde para celebrar mais um ano de vida! Que seus {$age} anos sejam marcados por saúde, sucesso e muitas conquistas.\n\n";
        $message .= "Obrigado por fazer parte da nossa história e confiar no nosso trabalho.\n\n";
        $message .= "*{$companyName}*\n";
        $message .= "*Feliz aniversário!* 🎂✨";

        return response()->json([
            'success' => true,
            'message' => $message,
            'customer_name' => $customer->name,
            'phone' => $customer->phone
        ]);
    }

    /**
     * Envia mensagem de parabéns para um cliente específico
     */
    public function sendBirthdayMessage(Request $request, Customer $customer)
    {
        if (!$customer->phone) {
            return back()->with('error', 'Cliente não possui telefone cadastrado.');
        }

        if (!$customer->birth_date) {
            return back()->with('error', 'Cliente não possui data de nascimento cadastrada.');
        }

        $evolutionApi = new EvolutionApiService();
        
        if (!$evolutionApi->isConfigured()) {
            return back()->with('error', 'Evolution API não está configurada. Configure em Parâmetros > Evolution API.');
        }

        try {
            // Formatar telefone
            $phone = preg_replace('/\D/', '', $customer->phone);
            if (!str_starts_with($phone, '55')) {
                $phone = '55' . $phone;
            }

            // Usar mensagem editada do formulário ou criar mensagem padrão
            $message = $request->input('message');
            
            if (empty($message)) {
                // Criar mensagem personalizada padrão
                $age = \Carbon\Carbon::parse($customer->birth_date)->age;
                $birthdayDate = \Carbon\Carbon::parse($customer->birth_date);
                $birthdayFormatted = $birthdayDate->format('d/m');
                
                // Buscar nome da empresa nas configurações
                $companyName = Settings::get('company_name') 
                    ?: Settings::get('smtp_from_name') 
                    ?: config('app.name', 'Nova Rosa MT');
                
                $message = "🎉 *Parabéns, {$customer->name}!* 🎉\n\n";
                $message .= "Sabemos que seu aniversário foi em {$birthdayFormatted}, mas nunca é tarde para celebrar mais um ano de vida! Que seus {$age} anos sejam marcados por saúde, sucesso e muitas conquistas.\n\n";
                $message .= "Obrigado por fazer parte da nossa história e confiar no nosso trabalho.\n\n";
                $message .= "*{$companyName}*\n";
                $message .= "*Feliz aniversário!* 🎂✨";
            }

            // Enviar mensagem
            $result = $evolutionApi->sendTextMessage($phone, $message);

            if ($result['success']) {
                Log::info("Mensagem de aniversário enviada para {$customer->name} ({$phone})");
                return back()->with('success', "Mensagem de parabéns enviada com sucesso para {$customer->name}!");
            } else {
                Log::error("Erro ao enviar mensagem de aniversário para {$customer->name}", [
                    'phone' => $phone,
                    'error' => $result['message'] ?? 'Erro desconhecido'
                ]);
                return back()->with('error', "Erro ao enviar mensagem: " . ($result['message'] ?? 'Erro desconhecido'));
            }

        } catch (\Exception $e) {
            Log::error("Exceção ao enviar mensagem de aniversário para {$customer->name}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', "Erro ao enviar mensagem: " . $e->getMessage());
        }
    }

    /**
     * Envia mensagens de parabéns para aniversariantes do mês (com progresso)
     */
    public function sendBirthdayMessages(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $progressKey = 'birthday_messages_progress_' . auth()->id() . '_' . $month;
        
        // Inicializar progresso
        Cache::put($progressKey, [
            'total' => 0,
            'processed' => 0,
            'success' => 0,
            'errors' => 0,
            'current' => null,
            'status' => 'processing',
            'message' => 'Iniciando...'
        ], now()->addMinutes(30));
        
        // Buscar aniversariantes do mês com telefone
        $birthdayCustomers = Customer::whereNotNull('birth_date')
            ->whereNotNull('phone')
            ->whereMonth('birth_date', $month)
            ->where('is_active', true)
            ->get();

        if ($birthdayCustomers->isEmpty()) {
            Cache::forget($progressKey);
            return response()->json([
                'success' => false,
                'message' => 'Nenhum aniversariante encontrado com telefone cadastrado para este mês.'
            ], 400);
        }

        $evolutionApi = new EvolutionApiService();
        
        if (!$evolutionApi->isConfigured()) {
            Cache::forget($progressKey);
            return response()->json([
                'success' => false,
                'message' => 'Evolution API não está configurada. Configure em Parâmetros > Evolution API.'
            ], 400);
        }

        // Atualizar total
        Cache::put($progressKey, [
            'total' => $birthdayCustomers->count(),
            'processed' => 0,
            'success' => 0,
            'errors' => 0,
            'current' => null,
            'status' => 'processing',
            'message' => 'Processando...'
        ], now()->addMinutes(30));

        // Processar em background (simulado com delay)
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $processed = 0;

        foreach ($birthdayCustomers as $customer) {
            try {
                // Atualizar progresso antes de processar
                $processed++;
                Cache::put($progressKey, [
                    'total' => $birthdayCustomers->count(),
                    'processed' => $processed - 1, // Ainda não processou este
                    'success' => $successCount,
                    'errors' => $errorCount,
                    'current' => $customer->name,
                    'status' => 'processing',
                    'message' => "Preparando envio para {$customer->name}..."
                ], now()->addMinutes(30));

                // Formatar telefone
                $phone = preg_replace('/\D/', '', $customer->phone);
                if (!str_starts_with($phone, '55')) {
                    $phone = '55' . $phone;
                }

                // Criar mensagem personalizada
                $age = \Carbon\Carbon::parse($customer->birth_date)->age;
                $birthdayDate = \Carbon\Carbon::parse($customer->birth_date);
                $birthdayFormatted = $birthdayDate->format('d/m');
                
                // Buscar nome da empresa nas configurações
                $companyName = Settings::get('company_name') 
                    ?: Settings::get('smtp_from_name') 
                    ?: config('app.name', 'Nova Rosa MT');
                
                $message = "🎉 *Parabéns, {$customer->name}!* 🎉\n\n";
                $message .= "Sabemos que seu aniversário foi em {$birthdayFormatted}, mas nunca é tarde para celebrar mais um ano de vida! Que seus {$age} anos sejam marcados por saúde, sucesso e muitas conquistas.\n\n";
                $message .= "Obrigado por fazer parte da nossa história e confiar no nosso trabalho.\n\n";
                $message .= "*{$companyName}*\n";
                $message .= "*Feliz aniversário!* 🎂✨";

                // Atualizar progresso: enviando
                Cache::put($progressKey, [
                    'total' => $birthdayCustomers->count(),
                    'processed' => $processed - 1,
                    'success' => $successCount,
                    'errors' => $errorCount,
                    'current' => $customer->name . ' (enviando...)',
                    'status' => 'processing',
                    'message' => "Enviando mensagem para {$customer->name}..."
                ], now()->addMinutes(30));

                // Enviar mensagem
                $result = $evolutionApi->sendTextMessage($phone, $message);

                if ($result['success']) {
                    $successCount++;
                    Log::info("Mensagem de aniversário enviada para {$customer->name} ({$phone})");
                } else {
                    $errorCount++;
                    $errors[] = "{$customer->name}: " . ($result['message'] ?? 'Erro desconhecido');
                    Log::error("Erro ao enviar mensagem de aniversário para {$customer->name}", [
                        'phone' => $phone,
                        'error' => $result['message'] ?? 'Erro desconhecido'
                    ]);
                }

                // Atualizar progresso após envio
                Cache::put($progressKey, [
                    'total' => $birthdayCustomers->count(),
                    'processed' => $processed,
                    'success' => $successCount,
                    'errors' => $errorCount,
                    'current' => $customer->name . ' (enviado ✓)',
                    'status' => 'processing',
                    'message' => "Mensagem enviada para {$customer->name}. Aguardando 20 segundos..."
                ], now()->addMinutes(30));

                // Delay entre mensagens (20 segundos conforme solicitado)
                // Atualizar progresso a cada segundo durante o delay
                for ($i = 1; $i <= 20; $i++) {
                    sleep(1);
                    Cache::put($progressKey, [
                        'total' => $birthdayCustomers->count(),
                        'processed' => $processed,
                        'success' => $successCount,
                        'errors' => $errorCount,
                        'current' => $customer->name . ' (aguardando ' . (20 - $i) . 's...)',
                        'status' => 'processing',
                        'message' => "Aguardando 20 segundos antes da próxima mensagem..."
                    ], now()->addMinutes(30));
                }

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "{$customer->name}: " . $e->getMessage();
                Log::error("Exceção ao enviar mensagem de aniversário para {$customer->name}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Finalizar progresso
        $finalMessage = "Enviadas {$successCount} mensagem(s) com sucesso.";
        if ($errorCount > 0) {
            $finalMessage .= " {$errorCount} erro(s) ocorreram.";
        }

        Cache::put($progressKey, [
            'total' => $birthdayCustomers->count(),
            'processed' => $processed,
            'success' => $successCount,
            'errors' => $errorCount,
            'current' => null,
            'status' => 'completed',
            'message' => $finalMessage,
            'errors_list' => $errors
        ], now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'message' => $finalMessage,
            'progress' => [
                'total' => $birthdayCustomers->count(),
                'processed' => $processed,
                'success' => $successCount,
                'errors' => $errorCount
            ]
        ]);
    }

    /**
     * Retorna o progresso do envio em massa
     */
    public function getBirthdayMessagesProgress(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $progressKey = 'birthday_messages_progress_' . auth()->id() . '_' . $month;
        
        $progress = Cache::get($progressKey);
        
        if (!$progress) {
            return response()->json([
                'status' => 'not_started',
                'message' => 'Nenhum envio em andamento'
            ]);
        }

        return response()->json($progress);
    }
}


