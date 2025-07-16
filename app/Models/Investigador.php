<?php

namespace App\Models;

use App\Models\InvestigadorProyecto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investigador extends Model
{

    protected $fillable = ['nombre', 'apellido', 'dni', 'cuil', 'fecha_nac', 'lugar_nac', 'domicilio', 'provincia', 'email', 'telefono', 'nivel_academico_id', 'disciplina_id', 'campo_id', 'objetivo_id', 'titulo', 'titulo_posgrado', 'cargo_id', 'categoria_interna_id', 'incentivo_id'];
    

    // Accessor dinámico para calcular la edad
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nac)->age;
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }
    public function getApellidoNombreAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class)
            ->using(InvestigadorProyecto::class)
            ->withPivot([
                'funcion_id', 'inicio', 'fin', 'plan_trabajo', 'pdf_disposicion', 'pdf_resolucion', 'vigente',
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

    // Becarios donde es director
    public function becariosComoDirector()
    {
        return $this->hasMany(\App\Models\BecarioProyecto::class, 'director_id');
    }

    // Becarios donde es codirector
    public function becariosComoCodirector()
    {
        return $this->hasMany(\App\Models\BecarioProyecto::class, 'codirector_id');
    }

    public function becarios()
    {
        return $this->hasMany(Becario::class);
    }


}