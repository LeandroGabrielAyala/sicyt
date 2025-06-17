<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investigador extends Model
{

    protected $fillable = ['nombre', 'apellido', 'dni', 'cuil', 'fecha_nac', 'domicilio', 'provincia', 'email', 'telefono', 'proyecto_id', 'inicio', 'fin', 'estado', 'disposicion', 'resolucion', 'pdf_disposicion', 'pdf_resolucion', 'funcion_id', 'nivel_academico_id', 'disciplina_id', 'campo_id', 'objetivo_id', 'titulo', 'titulo_posgrado', 'cargo_id', 'categoria_interna_id', 'incentivo_id'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    // Accessor dinámico para calcular la edad
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nac)->age;
    }

    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class);
    }

    public function objetivo(): BelongsTo
    {
        return $this->belongsTo(Objetivo::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function categoriaInterna(): BelongsTo
    {
        return $this->belongsTo(CategoriaInterna::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function funcion(): BelongsTo
    {
        return $this->belongsTo(Funcion::class);
    }

    public function incentivo(): BelongsTo
    {
        return $this->belongsTo(Incentivo::class);
    }

    public function nivelAcademico(): BelongsTo
    {
        return $this->belongsTo(NivelAcademico::class);
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }
}
