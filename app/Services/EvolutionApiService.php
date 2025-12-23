<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionApiService
{
    protected $apiUrl;
    protected $apiKey;
    protected $instanceName;

    public function __construct()
    {
        $this->apiUrl = rtrim(Settings::get('evolution_api_url', ''), '/');
        $this->apiKey = Settings::get('evolution_api_key', '');
        $this->instanceName = Settings::get('evolution_instance_name', 'default');
    }

    /**
     * Verifica se a Evolution API está configurada e ativada
     */
    public function isConfigured(): bool
    {
        // Verifica se está ativada
        $enabledValue = Settings::get('evolution_api_enabled', '0');
        $enabled = ($enabledValue === '1' || $enabledValue === 1 || $enabledValue === true);
        
        if (!$enabled) {
            return false;
        }
        
        // Verifica se todos os campos obrigatórios estão preenchidos
        return !empty($this->apiUrl) && !empty($this->apiKey) && !empty($this->instanceName);
    }

    /**
     * Envia uma mensagem de texto via WhatsApp
     * 
     * @param string $to Número do destinatário (com código do país, ex: 5511999999999)
     * @param string $message Texto da mensagem
     * @return array
     */
    public function sendTextMessage(string $to, string $message): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Evolution API não está configurada. Configure os parâmetros em Parâmetros > Evolution API.'
            ];
        }

        try {
            // Remove caracteres não numéricos do número
            $to = preg_replace('/\D/', '', $to);
            
            // Garante que o número começa com código do país
            if (!str_starts_with($to, '55') && strlen($to) <= 11) {
                $to = '55' . $to;
            }

            // Evolution API v2 endpoint
            $url = "{$this->apiUrl}/message/sendText/{$this->instanceName}";
            
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, [
                'number' => $to,
                'text' => $message
            ]);

            if ($response->successful()) {
                Log::info('Mensagem enviada via Evolution API', [
                    'to' => $to,
                    'instance' => $this->instanceName,
                    'response' => $response->json()
                ]);

                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $error = $response->json() ?? ['message' => 'Erro desconhecido'];
                
                Log::error('Erro ao enviar mensagem via Evolution API', [
                    'to' => $to,
                    'instance' => $this->instanceName,
                    'status' => $response->status(),
                    'error' => $error
                ]);

                return [
                    'success' => false,
                    'error' => $error['message'] ?? 'Erro ao enviar mensagem',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar mensagem via Evolution API', [
                'to' => $to,
                'instance' => $this->instanceName,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Envia uma mensagem com imagem via WhatsApp
     * 
     * @param string $to Número do destinatário
     * @param string $imageUrl URL da imagem
     * @param string $caption Legenda da imagem (opcional)
     * @return array
     */
    public function sendImageMessage(string $to, string $imageUrl, string $caption = ''): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Evolution API não está configurada.'
            ];
        }

        try {
            $to = preg_replace('/\D/', '', $to);
            
            if (!str_starts_with($to, '55') && strlen($to) <= 11) {
                $to = '55' . $to;
            }

            $url = "{$this->apiUrl}/message/sendMedia/{$this->instanceName}";
            
            $payload = [
                'number' => $to,
                'mediaMessage' => [
                    'mediatype' => 'image',
                    'media' => $imageUrl
                ]
            ];

            if (!empty($caption)) {
                $payload['mediaMessage']['caption'] = $caption;
            }

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->json()['message'] ?? 'Erro ao enviar imagem',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar imagem via Evolution API', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Envia um documento via WhatsApp
     * 
     * @param string $to Número do destinatário
     * @param string $documentUrl URL do documento
     * @param string $filename Nome do arquivo
     * @param string $caption Legenda do documento (opcional)
     * @return array
     */
    public function sendDocumentMessage(string $to, string $documentUrl, string $filename, string $caption = ''): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Evolution API não está configurada.'
            ];
        }

        try {
            $to = preg_replace('/\D/', '', $to);
            
            if (!str_starts_with($to, '55') && strlen($to) <= 11) {
                $to = '55' . $to;
            }

            $url = "{$this->apiUrl}/message/sendMedia/{$this->instanceName}";
            
            $payload = [
                'number' => $to,
                'mediaMessage' => [
                    'mediatype' => 'document',
                    'media' => $documentUrl,
                    'fileName' => $filename
                ]
            ];

            if (!empty($caption)) {
                $payload['mediaMessage']['caption'] = $caption;
            }

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            if ($response->successful()) {
                Log::info('Documento enviado via Evolution API', [
                    'to' => $to,
                    'filename' => $filename,
                    'instance' => $this->instanceName
                ]);

                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                $error = $response->json() ?? ['message' => 'Erro desconhecido'];
                
                Log::error('Erro ao enviar documento via Evolution API', [
                    'to' => $to,
                    'filename' => $filename,
                    'instance' => $this->instanceName,
                    'status' => $response->status(),
                    'error' => $error
                ]);

                return [
                    'success' => false,
                    'error' => $error['message'] ?? 'Erro ao enviar documento',
                    'status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exceção ao enviar documento via Evolution API', [
                'to' => $to,
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica o status da instância
     * 
     * @return array
     */
    public function getInstanceStatus(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'Evolution API não está configurada.'
            ];
        }

        try {
            // Tenta buscar informações da instância específica
            $url = "{$this->apiUrl}/instance/fetchInstances";
            
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(10)->get($url);

            if ($response->successful()) {
                $instances = $response->json();
                
                // Se for um array de instâncias
                if (is_array($instances)) {
                    // Buscar instância específica - a Evolution API retorna com campo 'name' diretamente
                    $instance = collect($instances)->first(function($item) {
                        $instanceName = $item['name'] ?? $item['instance']['instanceName'] ?? null;
                        return $instanceName === $this->instanceName;
                    });
                    
                    if ($instance) {
                        // A Evolution API usa 'connectionStatus' e 'number' diretamente no objeto
                        $state = $instance['connectionStatus'] ?? $instance['instance']['state'] ?? 'unknown';
                        $phone = $instance['number'] ?? $instance['instance']['phone'] ?? null;
                        $instanceName = $instance['name'] ?? $instance['instance']['instanceName'] ?? $this->instanceName;
                        
                        return [
                            'success' => true,
                            'instance' => [
                                'instance' => [
                                    'instanceName' => $instanceName,
                                    'state' => $state,
                                    'phone' => $phone,
                                    'connectionStatus' => $state,
                                    'number' => $phone
                                ]
                            ],
                            'connected' => $state === 'open',
                            'state' => $state,
                            'phone' => $phone
                        ];
                    } else {
                        // Listar instâncias disponíveis
                        $availableInstances = collect($instances)->map(function($item) {
                            return $item['name'] ?? $item['instance']['instanceName'] ?? 'N/A';
                        })->filter()->values()->toArray();
                        
                        $errorMessage = "Instância '{$this->instanceName}' não encontrada na Evolution API.";
                        if (!empty($availableInstances)) {
                            $errorMessage .= " Instâncias disponíveis: " . implode(', ', $availableInstances) . ".";
                        } else {
                            $errorMessage .= " Nenhuma instância encontrada na Evolution API. Verifique se as instâncias foram criadas corretamente.";
                        }
                        
                        return [
                            'success' => false,
                            'error' => $errorMessage,
                            'available_instances' => $availableInstances
                        ];
                    }
                } else {
                    // Se retornar uma única instância
                    $state = $instances['connectionStatus'] ?? $instances['instance']['state'] ?? 'unknown';
                    $phone = $instances['number'] ?? $instances['instance']['phone'] ?? null;
                    $instanceName = $instances['name'] ?? $instances['instance']['instanceName'] ?? $this->instanceName;
                    
                    return [
                        'success' => true,
                        'instance' => [
                            'instance' => [
                                'instanceName' => $instanceName,
                                'state' => $state,
                                'phone' => $phone,
                                'connectionStatus' => $state,
                                'number' => $phone
                            ]
                        ],
                        'connected' => $state === 'open',
                        'state' => $state,
                        'phone' => $phone
                    ];
                }
            } else {
                $errorBody = $response->json();
                return [
                    'success' => false,
                    'error' => $errorBody['message'] ?? 'Erro ao verificar status da instância',
                    'status_code' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status da Evolution API', [
                'error' => $e->getMessage(),
                'url' => $this->apiUrl,
                'instance' => $this->instanceName
            ]);
            
            return [
                'success' => false,
                'error' => 'Erro de conexão: ' . $e->getMessage()
            ];
        }
    }
}

