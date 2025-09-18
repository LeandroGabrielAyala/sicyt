<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    protected $table = 'postulaciones'; // 👈 forzamos el nombre correcto

    protected $fillable = [
        'convocatoria_id',
        'investigador_id',
        'archivo_pdf',
        'estado',
        'observaciones',
    ];

    public function convocatoria()
    {
        // Modelo ConvocatoriaProyecto
        return $this->belongsTo(ConvocatoriaProyecto::class, 'convocatoria_id');
    }

    public function investigador()
    {
        // Modelo Investigador
        return $this->belongsTo(Investigador::class, 'investigador_id');
    }
}

