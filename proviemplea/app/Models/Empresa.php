<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nombre_empresa',
        'rut_empresa',
        'email',
        'tipo_empresa',
        'rubro',
        'beneficios',
        'validado',
        'activo'
    ];

    protected $casts = [
        'beneficios' => 'array',
        'validado' => 'boolean',
        'activo' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
