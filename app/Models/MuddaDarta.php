<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuddaDarta extends Model
{
    // protected $table = 'mudda_darta';

     protected $fillable = [
        'anusandhan_garne_nikaye',
        'mudda_number',
        'mudda_name',
        'jaherwala_name',
        'pratiwadi_name',
        'pratiwadi_number',
        'mudda_stithi',
        'mudda_date',
        'mudda_suru_myad',
        'mudda_myad_thap',
        'jamma_din',
        'sarkariwakil_name',
        'faat_name',
        'mudda_pathayko_date',
        'kaifiyat',
        'created_at',
    ];
}
