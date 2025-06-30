<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallaniFormat extends Model
{
    protected $fillable = ['format_prefix', 'is_active'];

    public function challanis()
    {
        return $this->hasMany(Challani::class);
    }
}
