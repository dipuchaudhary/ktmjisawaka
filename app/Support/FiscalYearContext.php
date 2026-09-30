<?php

namespace App\Support;

use App\Models\FiscalYear;
use RuntimeException;

class FiscalYearContext
{
    public const SESSION_KEY = 'selected_fiscal_year_id';

    public static function current(): FiscalYear
    {
        $selectedId = session(self::SESSION_KEY);

        if ($selectedId) {
            $selected = FiscalYear::find($selectedId);
            if ($selected) {
                return $selected;
            }
        }

        $current = FiscalYear::where('is_current', true)->first();

        if (!$current) {
            throw new RuntimeException('No active fiscal year has been configured.');
        }

        return $current;
    }

    public static function id(): int
    {
        return (int) self::current()->id;
    }

    public static function set(?int $id): void
    {
        if ($id === null) {
            session()->forget(self::SESSION_KEY);
            return;
        }

        FiscalYear::findOrFail($id);
        session([self::SESSION_KEY => $id]);
    }

    public static function resetToCurrent(): void
    {
        $current = FiscalYear::where('is_current', true)->firstOrFail();
        session([self::SESSION_KEY => $current->id]);
    }

    public static function isCurrent(): bool
    {
        return self::current()->is_current;
    }
}