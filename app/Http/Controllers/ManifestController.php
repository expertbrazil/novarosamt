<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function index()
    {
        $manifest = [
            'name' => 'Nova Rosa MT',
            'short_name' => 'Nova Rosa',
            'description' => 'Produtos de Limpeza - Nova Rosa MT',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#4f46e5',
            'orientation' => 'portrait',
            'icons' => [
                [
                    'src' => asset('favicon-192x192.png'),
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => asset('favicon-512x512.png'),
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ],
            'categories' => ['shopping', 'business'],
            'screenshots' => [],
            'shortcuts' => [
                [
                    'name' => 'Fazer Pedido',
                    'short_name' => 'Pedido',
                    'description' => 'Criar um novo pedido',
                    'url' => '/pedido',
                    'icons' => [
                        [
                            'src' => asset('favicon-192x192.png'),
                            'sizes' => '192x192'
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}

