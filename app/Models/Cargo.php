<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $fillable = ['nombre'];

        public function proyecto(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }
}
