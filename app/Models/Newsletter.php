<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'fblog_news_letters'; // 👈 el nombre real en tu DB
    protected $fillable = ['email', 'subscribed'];
}
