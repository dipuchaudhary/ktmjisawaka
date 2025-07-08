<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankingMudda extends Model
{
    protected $fillable = [
        'anusandhan_garne_nikaye',
        'mudda_number',
        'mudda_name',
        'jaherwala_name',
        'pratiwadi_name',
        'pratiwadi_number',
        'mudda_stithi',
        'mudda_bibran',
        'pesi_karyala',
        'mudda_date',
        'mudda_myad',
        'sarkariwakil_name',
        'mudda_pathayko_date',
        'challani_number',
        'status',
        'kaifiyat',
        'user_name',
        'created_at',
    ];
}
