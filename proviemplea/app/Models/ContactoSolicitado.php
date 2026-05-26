<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContactoSolicitado extends Model
{
    protected $table = 'contactos_solicitados';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'empresa_id',
        'persona_id',
        'estado',
        'notas_admin'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    // Relaciones 2

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
