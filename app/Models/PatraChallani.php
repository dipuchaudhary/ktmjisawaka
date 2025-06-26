<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatraChallani extends Model
{
    protected $fillable = [
        'karyalaya_name',
        'challani_date',
        'challani_number',
        'mudda_number',
        'challani_subject',
        'bodartha',
        'verified_by',
        'kaifiyat',
        'created_at',
    ];
}
