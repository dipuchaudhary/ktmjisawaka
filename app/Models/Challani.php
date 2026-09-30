<?php

namespace App\Models;

use App\Models\Concerns\BelongsToFiscalYear;

use Illuminate\Database\Eloquent\Model;

class Challani extends Model
{
    use BelongsToFiscalYear;
    protected $fillable = [
        'fiscal_year_id','challani_number'];
}
