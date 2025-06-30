<?php

namespace App\Models;
use App\Models\AviyogChallani;

use Illuminate\Database\Eloquent\Model;

class AviyogChallani extends Model
{
    protected $fillable = [
        'challani_date',
        'challani_number',
        'jaherwala_name',
        'pratiwadi_name',
        'mudda_name',
        'gender',
        'gender_counts',
        'mudda_number',
        'sarkariwakil_name',
        'anusandhan_garne_nikaye',
        'faat_name',
        'file',
        'kaifiyat',
        'status',
        'created_at',
    ];

}
