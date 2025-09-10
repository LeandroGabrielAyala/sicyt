<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoProyecto extends Model
{
    protected $fillable = ['nombre'];

    public function convocatoria_proyecto(): HasMany
    {
        return $this->hasMany(ConvocatoriaProyecto::class);
    }

    public function proyecto(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }
}
