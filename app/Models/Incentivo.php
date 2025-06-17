<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incentivo extends Model
{
    protected $fillable = ['categoria'];

        public function proyecto(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }
}
