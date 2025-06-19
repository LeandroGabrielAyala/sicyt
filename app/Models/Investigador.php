<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Investigador extends Model
{

    protected $fillable = ['nombre', 'apellido', 'dni', 'cuil', 'fecha_nac', 'domicilio', 'provincia', 'email', 'telefono', 'nivel_academico_id', 'disciplina_id', 'campo_id', 'objetivo_id', 'titulo', 'titulo_posgrado', 'cargo_id', 'categoria_interna_id', 'incentivo_id'];
    
    // Accessor dinámico para calcular la edad
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nac)->age;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class)
            ->using(InvestigadorProyecto::class)
            ->withPivot([
                'funcion_id', 'inicio', 'fin', 'pdf_disposicion', 'pdf_resolucion', 'vigente',
            ])
            ->withTimestamps();
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

    public function incentivo(): BelongsTo
    {
        return $this->belongsTo(Incentivo::class);
    }

    public function nivelAcademico(): BelongsTo
    {
        return $this->belongsTo(NivelAcademico::class);
    }
}
