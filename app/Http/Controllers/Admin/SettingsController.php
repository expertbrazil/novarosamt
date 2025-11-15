<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\EstadoMunicipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::all()->pluck('value', 'key');
        $estados = EstadoMunicipio::getEstados();
        
        // Carregar cidades de entrega selecionadas
        $deliveryCities = [];
        $deliveryCitiesJson = Settings::get('delivery_cities', '[]');
        if ($deliveryCitiesJson) {
            $decoded = json_decode($deliveryCitiesJson, true) ?? [];
            // Garantir que seja um array indexado numericamente
            if (is_array($decoded)) {
                $deliveryCities = array_values($decoded);
            }
        }
        
        return view('admin.settings.index', compact('settings', 'estados', 'deliveryCities'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'orders_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_message' => 'nullable|string|max:500',
            
            // Delivery Settings
            'company_address' => 'nullable|string|max:500',
            'delivery_info' => 'nullable|string|max:2000',
            'delivery_cities' => 'nullable|array',
            'delivery_cities.*.estado' => 'required|string|size:2',
            'delivery_cities.*.municipio_id' => 'required|exists:estados_municipios,id',
            
            // SMTP Settings
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = Settings::get('logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $logoPath = $request->file('logo')->store('settings', 'public');
            Settings::set('logo', $logoPath, 'file', 'Logomarca da empresa');
        }

        // Handle orders logo upload
        if ($request->hasFile('orders_logo')) {
            // Delete old orders logo if exists
            $oldOrdersLogo = Settings::get('orders_logo');
            if ($oldOrdersLogo && Storage::disk('public')->exists($oldOrdersLogo)) {
                Storage::disk('public')->delete($oldOrdersLogo);
            }
            
            $ordersLogoPath = $request->file('orders_logo')->store('settings', 'public');
            Settings::set('orders_logo', $ordersLogoPath, 'file', 'Logomarca para pedidos e relatórios');
        }

        // WhatsApp settings
        if ($request->filled('whatsapp_number')) {
            Settings::set('whatsapp_number', $request->whatsapp_number, 'string', 'Número do WhatsApp');
        }
        
        if ($request->filled('whatsapp_message')) {
            Settings::set('whatsapp_message', $request->whatsapp_message, 'text', 'Mensagem padrão do WhatsApp');
        }

        // Delivery Settings
        if ($request->filled('company_address')) {
            Settings::set('company_address', $request->company_address, 'text', 'Endereço da sede da empresa');
        }
        
        if ($request->filled('delivery_info')) {
            Settings::set('delivery_info', $request->delivery_info, 'text', 'Informações sobre entrega para o cliente');
        }
        
        if ($request->has('delivery_cities')) {
            $deliveryCities = $request->input('delivery_cities', []);
            // Filtrar apenas os que têm municipio_id válido
            $validCities = array_filter($deliveryCities, function($city) {
                return !empty($city['municipio_id']);
            });
            Settings::set('delivery_cities', json_encode(array_values($validCities)), 'json', 'Cidades onde é feita a entrega');
        }

        // SMTP Settings
        $smtpFields = [
            'smtp_host' => 'Host do servidor SMTP',
            'smtp_port' => 'Porta do servidor SMTP',
            'smtp_username' => 'Usuário do servidor SMTP',
            'smtp_password' => 'Senha do servidor SMTP',
            'smtp_encryption' => 'Criptografia SMTP',
            'smtp_from_address' => 'Email remetente',
            'smtp_from_name' => 'Nome remetente',
        ];

        foreach ($smtpFields as $field => $description) {
            if ($request->filled($field)) {
                $type = ($field === 'smtp_port') ? 'integer' : 'string';
                Settings::set($field, $request->input($field), $type, $description);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Parâmetros atualizados com sucesso!');
    }

    /**
     * Retorna municípios de um estado via AJAX
     */
    public function getMunicipios($estado)
    {
        $municipios = EstadoMunicipio::getByEstado($estado);
        
        return response()->json(
            $municipios->map(function($municipio) {
                return [
                    'id' => $municipio->id,
                    'municipio' => $municipio->municipio,
                ];
            })
        );
    }
}
