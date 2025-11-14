<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoMunicipio extends Model
{
    protected $table = 'estados_municipios';

    protected $fillable = [
        'estado',
        'estado_nome',
        'municipio',
        'codigo_ibge',
    ];

    /**
     * Buscar municípios por estado
     */
    public static function getByEstado(string $estado)
    {
        return static::where('estado', $estado)
            ->orderBy('municipio')
            ->get();
    }

    /**
     * Buscar todos os estados únicos
     */
    public static function getEstados()
    {
        return static::select('estado', 'estado_nome')
            ->distinct()
            ->orderBy('estado_nome')
            ->get();
    }

    /**
     * Formatar nome completo (Município - Estado)
     */
    public function getNomeCompletoAttribute(): string
    {
        return "{$this->municipio} - {$this->estado}";
    }
}
