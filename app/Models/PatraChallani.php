<?php

namespace App\Models;

use App\Models\Concerns\BelongsToFiscalYear;

use Illuminate\Database\Eloquent\Model;

class PatraChallani extends Model
{
    use BelongsToFiscalYear;
    protected $fillable = [
        'fiscal_year_id',
        'karyalaya_name',
        'challani_date',
        'challani_number',
        'mudda_number',
        'challani_subject',
        'jaherwala_name',
        'pratiwadi_name',
        'bodartha',
        'verified_by',
        'kaifiyat',
        'challani_sakha',
        'faat',
        'user_name',
        'status',
        'created_at',
    ];
}
