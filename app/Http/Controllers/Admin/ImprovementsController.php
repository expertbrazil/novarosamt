<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImprovementsController extends Controller
{
    public function index()
    {
        $improvements = $this->getImprovements();
        
        return view('admin.improvements.index', compact('improvements'));
    }
    
    /**
     * Retorna lista de melhorias e atualizações do sistema
     */
    protected function getImprovements(): array
    {
        return [
            [
                'version' => '1.0.0',
                'date' => '2025-12-23',
                'title' => 'Módulo de Banners',
                'description' => 'Sistema completo de gerenciamento de banners para a página inicial do site.',
                'features' => [
                    'Cadastro completo de banners com título e imagens',
                    'Suporte para imagens desktop (1920x600) e mobile separadas',
                    'Ativação/desativação de banners individualmente',
                    'Carrossel automático na página inicial com transições suaves',
                    'Exibição inteligente: imagem mobile apenas em dispositivos móveis',
                    'Sistema de navegação com setas e indicadores de posição',
                    'Troca automática a cada 10 segundos',
                    'Interface administrativa completa para gerenciar todos os banners'
                ],
                'icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6.75v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
                'category' => 'Funcionalidade',
                'status' => 'active'
            ],
            [
                'version' => '1.0.0',
                'date' => '2025-12-23',
                'title' => 'Integração com WhatsApp via Evolution API',
                'description' => 'Sistema agora envia notificações automáticas via WhatsApp quando pedidos são criados.',
                'features' => [
                    'Notificações automáticas para administrador quando novos pedidos são criados',
                    'Mensagem de confirmação completa para clientes com lista de produtos',
                    'Informações de pagamento PIX incluídas automaticamente na mensagem',
                    'Solicitação automática de envio de comprovante de pagamento',
                    'Sistema funciona normalmente mesmo se WhatsApp não estiver configurado'
                ],
                'icon' => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z',
                'category' => 'Integração',
                'status' => 'active'
            ],
            [
                'version' => '1.0.0',
                'date' => '2025-12-23',
                'title' => 'Sistema de Backup e Restauração',
                'description' => 'Sistema completo de backup e restauração de dados e arquivos.',
                'features' => [
                    'Backup completo do banco de dados',
                    'Backup de todas as imagens e arquivos de upload',
                    'Restauração completa em um único clique',
                    'Upload de arquivos de backup',
                    'Download de backups criados'
                ],
                'icon' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5',
                'category' => 'Segurança',
                'status' => 'active'
            ],
            [
                'version' => '1.0.0',
                'date' => '2025-12-23',
                'title' => 'Melhorias na Interface',
                'description' => 'Melhorias visuais e de usabilidade na interface administrativa.',
                'features' => [
                    'Menu lateral com submenus animados',
                    'Modo escuro aprimorado',
                    'Gráficos customizados em HTML/CSS',
                    'Interface mais responsiva e moderna'
                ],
                'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z',
                'category' => 'Interface',
                'status' => 'active'
            ],
            [
                'version' => '1.0.0',
                'date' => '2025-12-23',
                'title' => 'Tradução Completa',
                'description' => 'Sistema totalmente traduzido para português brasileiro.',
                'features' => [
                    'Todas as páginas administrativas traduzidas',
                    'Páginas públicas traduzidas',
                    'Pagination traduzida (Anterior/Próximo)',
                    'Mensagens de sistema traduzidas'
                ],
                'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'category' => 'Localização',
                'status' => 'active'
            ]
        ];
    }
}

