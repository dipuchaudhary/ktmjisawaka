<?php

namespace App\Models\Concerns;

use App\Support\FiscalYearContext;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToFiscalYear
{
    protected static function bootBelongsToFiscalYear(): void
    {
        static::addGlobalScope('fiscal_year', function (Builder $builder) {
            $builder->where(
                $builder->getModel()->getTable() . '.fiscal_year_id',
                FiscalYearContext::id()
            );
        });

        static::creating(function ($model) {
            if (empty($model->fiscal_year_id)) {
                $model->fiscal_year_id = FiscalYearContext::id();
            }
        });
    }

    public function fiscalYear()
    {
        return $this->belongsTo(\App\Models\FiscalYear::class);
    }

    public function scopeForFiscalYear(Builder $query, int $fiscalYearId): Builder
    {
        return $query->withoutGlobalScope('fiscal_year')
            ->where($query->getModel()->getTable() . '.fiscal_year_id', $fiscalYearId);
    }
}