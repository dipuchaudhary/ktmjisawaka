<?php

namespace App\Http\Controllers;

use App\Models\ChallaniFormat;
use App\Models\FiscalYear;
use App\Support\FiscalYearContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiscalYearController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->hasRole('admin'), 403);
    }

    public function index()
    {
        $this->authorizeAdmin();
        $fiscalYears = FiscalYear::orderByDesc('start_year')->get();
        $selected = FiscalYearContext::current();

        return view('backend.fiscal_year.index', compact('fiscalYears', 'selected'));
    }

    public function switch(FiscalYear $fiscalYear)
    {
        $this->authorizeAdmin();
        FiscalYearContext::set($fiscalYear->id);

        return back()->with(
            'success',
            'वित्तीय वर्ष ' . $fiscalYear->display_name . ' चयन गरिएको छ।'
        );
    }

    public function startNext(Request $request)
    {
        $this->authorizeAdmin();

        $current = FiscalYear::where('is_current', true)->firstOrFail();
        $nextStartYear = (int) $current->end_year;
        $nextEndYear = $nextStartYear + 1;
        $expectedName = $nextStartYear . '/' . substr((string) $nextEndYear, -2);

        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate([
            'name' => ['required', 'regex:/^\d{4}\/\d{2}$/', 'in:' . $expectedName],
        ], [
            'name.in' => 'अर्को वित्तीय वर्ष ' . $expectedName . ' मात्र सुरु गर्न सकिन्छ।',
        ]);

        $startYear = $nextStartYear;
        $endYear = $nextEndYear;

        $existing = FiscalYear::where('name', $expectedName)->first();
        if ($existing) {
            FiscalYearContext::set($existing->id);
            return back()->with('info', 'उक्त वित्तीय वर्ष पहिले नै सिर्जना गरिएको छ।');
        }

        $newYear = DB::transaction(function () use ($expectedName, $startYear, $endYear) {
            ChallaniFormat::query()->where('is_active', true)->update(['is_active' => false]);

            FiscalYear::query()->where('is_current', true)->update([
                'is_current' => false,
                'is_closed' => true,
            ]);

            $newYear = FiscalYear::create([
                'name' => $expectedName,
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