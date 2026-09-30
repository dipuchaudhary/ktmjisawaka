<?php

namespace App\Models;

use App\Models\Concerns\BelongsToFiscalYear;

use Illuminate\Database\Eloquent\Model;

class ChallaniFormat extends Model
{
    use BelongsToFiscalYear;
    protected $fillable = [
        'fiscal_year_id','format_prefix', 'is_active'];

    public function challanis()
    {
        return $this->hasMany(Challani::class);
    }
}
