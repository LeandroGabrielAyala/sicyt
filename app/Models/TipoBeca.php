<?php

namespace App\Models;

use Becario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBeca extends Model
{
    protected $fillable = ['nombre'];

    public function convocatoria_beca(): HasMany
    {
        return $this->hasMany(ConvocatoriaBeca::class);
    }

    public function becario(): HasMany
    {
        return $this->hasMany(Becario::class);
    }
}
