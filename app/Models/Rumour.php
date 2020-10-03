<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rumour extends Model
{
    use HasFactory;

    public function illness()
    {
        return $this->belongsTo('App\Models\Illness');
    }
}
