<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConvocatoriaAdscripto extends Model
{
    protected $fillable = [
    'anio', 'estado', 'inicio', 'fin', 'disposicion', 'resolucion', 'pdf_resolucion', 'pdf_disposicion'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    // public function getDescripcionAttribute()
    // {
    //     return 'Convocatoria ' . $this->anio;
    // }

    // public function adscriptos()
    // {
    //     return $this->belongsToMany(Adscripto::class, 'adscripto_proyecto')
    //         ->withPivot(['vigente', 'director_id', 'codirector_id'])
    //         ->withTimestamps();
    // }
}
