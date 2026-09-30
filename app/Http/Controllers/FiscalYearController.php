<?php

namespace App\Http\Controllers;

use App\Models\ChallaniFormat;
use App\Models\FiscalYear;
use App\Support\FiscalYearContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::orderByDesc('start_year')->get();
        $selected = FiscalYearContext::current();

        return view('backend.fiscal_year.index', compact('fiscalYears', 'selected'));
    }

    public function switch(FiscalYear $fiscalYear)
    {
        FiscalYearContext::set($fiscalYear->id);

        return back()->with(
            'success',
            'वित्तीय वर्ष ' . $fiscalYear->display_name . ' चयन गरिएको छ।'
        );
    }

    public function current(FiscalYear $fiscalYear)
    {
        if ($fiscalYear->is_current) {
            FiscalYearContext::set($fiscalYear->id);
            return back()->with('info', 'उक्त वित्तीय वर्ष पहिले नै चालु छ।');
        }

        DB::transaction(function () use ($fiscalYear) {
            FiscalYear::query()->update(['is_current' => false]);

            $fiscalYear->update([
                'is_current' => true,
                'is_closed' => false,
            ]);

            $format = ChallaniFormat::withoutGlobalScope('fiscal_year')
                ->where('fiscal_year_id', $fiscalYear->id)
                ->first();

            if (!$format) {
                ChallaniFormat::withoutGlobalScope('fiscal_year')->create([
                    'fiscal_year_id' => $fiscalYear->id,
                    'format_prefix' => $fiscalYear->name,
                    'is_active' => true,
                ]);
            }
        });

        FiscalYearContext::set($fiscalYear->id);

        return back()->with(
            'success',
            'वित्तीय वर्ष ' . $fiscalYear->display_name . ' चालु गरिएको छ।'
        );
    }

    public function startNext(Request $request)
    {
        $request->validate([
            'name' => ['required', 'regex:/^\d{4}\/\d{2}$/'],
        ]);

        [$startYear, $endShort] = explode('/', $request->name);
        $endYear = ((int) $startYear) + 1;

        $existing = FiscalYear::where('name', $request->name)->first();
        if ($existing) {
            FiscalYearContext::set($existing->id);
            return back()->with('info', 'उक्त वित्तीय वर्ष पहिले नै सिर्जना गरिएको छ।');
        }

        $newYear = DB::transaction(function () use ($request, $startYear, $endYear) {
            FiscalYear::query()->update(['is_current' => false]);

            $newYear = FiscalYear::create([
                'name' => $request->name,
                'start_year' => (int) $startYear,
                'end_year' => $endYear,
                'is_current' => true,
                'is_closed' => false,
            ]);

            ChallaniFormat::withoutGlobalScope('fiscal_year')->create([
                'fiscal_year_id' => $newYear->id,
                'format_prefix' => $newYear->name,
                'is_active' => true,
            ]);

            return $newYear;
        });

        FiscalYearContext::set($newYear->id);

        return back()->with(
            'success',
            'नयाँ वित्तीय वर्ष ' . $newYear->display_name . ' सुरु भयो। पुरानो डाटा archive मा सुरक्षित छ र नयाँ वर्षमा खाली डाटा सेट गरिएको छ।'
        );
    }
}