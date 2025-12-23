<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Throwable $e) {
            // Se não conseguir conectar ao banco, retorna o valor padrão
            return $default;
        }
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $description = null)
    {
        try {
            return static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'description' => $description,
                ]
            );
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        try {
            return static::pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            // Se não conseguir conectar ao banco, retorna array vazio
            return [];
        }
    }
}
