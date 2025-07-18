<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{

    protected $fillable = ['nombre', 'titulo'];

    public function becarios()
    {
        return $this->hasMany(Becario::class);
    }
}
