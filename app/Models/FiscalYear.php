<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends Model
{
    protected $fillable = [
        'name',
        'start_year',
        'end_year',
        'is_current',
        'is_closed',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_closed' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return function_exists('toNepaliNumber')
            ? toNepaliNumber($this->name)
            : $this->name;
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function muddaDartas(): HasMany
    {
        return $this->hasMany(MuddaDarta::class);
    }
}