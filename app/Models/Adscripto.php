<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adscripto extends Model
{

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'cuil',
        'fecha_nac',
        'lugar_nac',
        'domicilio',
        'provincia',
        'codigo',
        'email',
        'telefono',
        'carrera_id',
        'titulo_id',
    ];

    /**
     * Relación con la carrera cursada (carrera_id).
     */
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    /**
     * Relación con el título obtenido (también desde la tabla carreras).
     */
    public function titulo()
    {
        return $this->belongsTo(Carrera::class, 'titulo_id');
    }

    public function proyectos()
    {
        return $this->belongsToMany(\App\Models\Proyecto::class)
            ->using(\App\Models\AdscriptoProyecto::class) // si tenés un modelo intermedio
            ->withPivot([
                'convocatoria_adscripto_id',
                'vigente',
                'director_id',
                'codirector_id',
            ])
            ->withTimestamps();
    }


    public function convocatorias()
    {
        return $this->proyectos->map(fn ($proyecto) => $proyecto->pivot->convocatoriaAdscripto)->filter()->unique('id');
    }


    public function getNombreCompletoAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

}
