<?php

namespace App\Models;

use App\Models\Concerns\BelongsToFiscalYear;

use Illuminate\Database\Eloquent\Model;

class MuddaDarta extends Model
{
    use BelongsToFiscalYear;
     protected $fillable = [
        'fiscal_year_id',
        'anusandhan_garne_nikaye',
        'mudda_number',
        'mudda_name',
        'jaherwala_name',
        'pratiwadi_name',
        'pratiwadi_number',
        'mudda_date',
        'mudda_suru_myasd',
        'mudda_myad_thap',
        'jamma_din',
        'sarkariwakil_name',
        'faat_name',
        'mudda_bibran',
        'kaifiyat',
        'user_name',
        'adalat_mudda_number',
        'created_at',
    ];
}
