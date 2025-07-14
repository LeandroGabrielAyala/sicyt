<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdscriptoProyecto extends Pivot
{
    protected $fillable = [
            'adscripto_id',
            'proyecto_id',
            'director_id',
            'codirector_id',
            'convocatoria_adscripto_id',
            'vigente',
            'disposicion', 
            'resolucion',
            'pdf_disposicion',
            'pdf_resolucion',
        ];

        protected $casts = [
            'vigente' => 'boolean',
            'pdf_disposicion' => 'array',
            'pdf_resolucion' => 'array',
        ];

        public function convocatoria()
        {
            return $this->belongsTo(\App\Models\ConvocatoriaAdscripto::class, 'convocatoria_adscripto_id');
        }

        public function director()
        {
            return $this->belongsTo(\App\Models\Investigador::class, 'director_id');
        }

        public function codirector()
        {
            return $this->belongsTo(\App\Models\Investigador::class, 'codirector_id');
        }


        public function adscripto()
        {
            return $this->belongsTo(Adscripto::class);
        }

        public function proyecto()
        {
            return $this->belongsTo(Proyecto::class);
        }
}
