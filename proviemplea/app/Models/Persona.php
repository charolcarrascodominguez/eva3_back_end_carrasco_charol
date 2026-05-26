<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Persona extends Model
{
    protected $table = 'personas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'telefono',
        'codigo_talento',
        'nivel_educacional',
        'titulo_carrera',
        'anios_experiencia',
        'competencias',
        'tipo_jornada',
        'modalidad',
        'validado',
        'activo'
    ];

    protected $casts = [
        'competencias' => 'array',
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
