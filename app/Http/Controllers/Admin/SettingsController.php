<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_message' => 'nullable|string|max:500',
            
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

        // WhatsApp settings
        if ($request->filled('whatsapp_number')) {
            Settings::set('whatsapp_number', $request->whatsapp_number, 'string', 'Número do WhatsApp');
        }
        
        if ($request->filled('whatsapp_message')) {
            Settings::set('whatsapp_message', $request->whatsapp_message, 'text', 'Mensagem padrão do WhatsApp');
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
}
