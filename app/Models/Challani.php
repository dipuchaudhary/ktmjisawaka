<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challani extends Model
{
    public function format()
    {
        return $this->belongsTo(ChallaniFormat::class, 'challani_format_id');
    }
}
