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
        return $this->belongsToMany(Proyecto::class, 'adscripto_proyecto')
            ->using(AdscriptoProyecto::class)
            ->withPivot([
                'director_id',
                'codirector_id',
                'convocatoria_adscripto_id',
                'vigente',
                'pdf_disposicion',
                'pdf_resolucion'
            ])
            ->withTimestamps();
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

}
