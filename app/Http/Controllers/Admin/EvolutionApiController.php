<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;

class EvolutionApiController extends Controller
{
    public function index()
    {
        $settings = Settings::all()->pluck('value', 'key');
        $evolutionApi = app(EvolutionApiService::class);
        
        // Verificar status da instância se estiver configurada
        $instanceStatus = null;
        if ($evolutionApi->isConfigured()) {
            $instanceStatus = $evolutionApi->getInstanceStatus();
        }
        
        return view('admin.evolution-api.index', compact('settings', 'instanceStatus'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'evolution_api_enabled' => 'nullable|boolean',
            'evolution_api_url' => 'nullable|url|max:255',
            'evolution_api_key' => 'nullable|string|max:255',
            'evolution_instance_name' => 'nullable|string|max:100',
            'evolution_whatsapp_number' => 'nullable|string|max:20',
        ]);

        // Switcher de ativação/desativação
        // Se o checkbox estiver marcado, receberemos '1', senão receberemos '0' do hidden
        // O Laravel pega o último valor quando há múltiplos campos com o mesmo nome
        $enabledValue = $request->input('evolution_api_enabled');
        // Se for array (quando ambos hidden e checkbox são enviados), pega o último
        if (is_array($enabledValue)) {
            $enabledValue = end($enabledValue);
        }
        $enabled = ($enabledValue === '1' || $enabledValue === 1) ? '1' : '0';
        Settings::set('evolution_api_enabled', $enabled, 'boolean', 'Ativar/Desativar uso da Evolution API');

        // Evolution API Settings
        $evolutionFields = [
            'evolution_api_url' => 'URL base da Evolution API',
            'evolution_api_key' => 'Chave de autenticação da Evolution API (API Key)',
            'evolution_instance_name' => 'Nome da instância do WhatsApp na Evolution API',
            'evolution_whatsapp_number' => 'Número do WhatsApp conectado na instância',
        ];

        foreach ($evolutionFields as $field => $description) {
            if ($request->filled($field)) {
                Settings::set($field, $request->input($field), 'string', $description);
            } else {
                Settings::set($field, '', 'string', $description);
            }
        }

        return redirect()->route('admin.evolution-api.index')
            ->with('success', 'Configurações da Evolution API atualizadas com sucesso!');
    }

    public function testConnection(Request $request)
    {
        try {
            $evolutionApi = app(EvolutionApiService::class);
            
            if (!$evolutionApi->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evolution API não está configurada. Preencha todos os campos obrigatórios.'
                ], 400);
            }

            // Tenta verificar o status da instância
            $status = $evolutionApi->getInstanceStatus();
            
            if ($status['success']) {
                $connected = $status['connected'] ?? false;
                $instanceData = $status['instance'] ?? null;
                $state = $status['state'] ?? ($instanceData['instance']['state'] ?? 'unknown');
                $phone = $status['phone'] ?? ($instanceData['instance']['phone'] ?? null);
                $instanceName = $instanceData['instance']['instanceName'] ?? Settings::get('evolution_instance_name', 'N/A');
                
                if ($connected) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Conexão estabelecida com sucesso! A instância está conectada.',
                        'data' => [
                            'instance_name' => $instanceName,
                            'state' => $state,
                            'phone' => $phone ?? 'N/A',
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'A instância existe mas não está conectada. Verifique se o WhatsApp está conectado na Evolution API.',
                        'data' => [
                            'instance_name' => $instanceName,
                            'state' => $state,
                            'phone' => $phone ?? 'N/A',
                        ]
                    ], 400);
                }
            } else {
                $errorMessage = $status['error'] ?? 'Erro ao verificar status da instância.';
                $availableInstances = $status['available_instances'] ?? [];
                
                $responseData = [
                    'success' => false,
                    'message' => $errorMessage
                ];
                
                if (!empty($availableInstances)) {
                    $responseData['available_instances'] = $availableInstances;
                    $responseData['suggestion'] = 'Verifique se o nome da instância está correto. Use um dos nomes listados acima.';
                }
                
                return response()->json($responseData, 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage()
            ], 500);
        }
    }

    public function disconnect(Request $request)
    {
        try {
            $evolutionApi = app(EvolutionApiService::class);
            
            if (!$evolutionApi->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evolution API não está configurada.'
                ], 400);
            }

            $apiUrl = rtrim(Settings::get('evolution_api_url', ''), '/');
            $apiKey = Settings::get('evolution_api_key', '');
            $instanceName = Settings::get('evolution_instance_name', 'default');

            $url = "{$apiUrl}/instance/logout/{$instanceName}";
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'apikey' => $apiKey,
            ])->timeout(10)->delete($url);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Instância desconectada com sucesso!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'Erro ao desconectar instância'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desconectar: ' . $e->getMessage()
            ], 500);
        }
    }
}

