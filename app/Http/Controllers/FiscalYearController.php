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
        $nextStartYear = (int) $selected->end_year;
        $nextEndYear = $nextStartYear + 1;
        $nextFiscalYear = $nextStartYear . '/' . substr((string) $nextEndYear, -3);

        return view('backend.fiscal_year.index', compact('fiscalYears', 'selected', 'nextFiscalYear'));
    }

    public function store(Request $request)
    {
        $data = $this->validateFiscalYear($request);

        $year = DB::transaction(function () use ($data) {
            if (!empty($data['is_current'])) {
                FiscalYear::where('is_current', true)->update([
                    'is_current' => false,
                    'is_closed' => true,
                ]);
            }

            $year = FiscalYear::create([
                'name' => $data['name'],
                'start_year' => $data['start_year'],
                'end_year' => $data['end_year'],
                'is_current' => (bool) ($data['is_current'] ?? false),
                'is_closed' => !($data['is_current'] ?? false),
            ]);

            if ($year->is_current) {
                ChallaniFormat::withoutGlobalScope('fiscal_year')
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                ChallaniFormat::withoutGlobalScope('fiscal_year')->create([
                    'fiscal_year_id' => $year->id,
                    'format_prefix' => $year->name,
                    'is_active' => true,
                ]);
            }

            return $year;
        });

        if ($year->is_current) {
            FiscalYearContext::set($year->id);
        }

        return back()->with('success', 'वित्तीय वर्ष सफलतापूर्वक थपियो।');
    }

    public function update(Request $request, FiscalYear $fiscalYear)
    {
        $data = $this->validateFiscalYear($request, $fiscalYear->id);
        $makeCurrent = $request->boolean('is_current');

        DB::transaction(function () use ($data, $fiscalYear, $makeCurrent) {
            if ($makeCurrent && !$fiscalYear->is_current) {
                FiscalYear::withoutGlobalScopes()->where('is_current', true)->update([
                    'is_current' => false,
                    'is_closed' => true,
                ]);

                ChallaniFormat::withoutGlobalScope('fiscal_year')
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $fiscalYear->update([
                'name' => $data['name'],
                'start_year' => $data['start_year'],
                'end_year' => $data['end_year'],
                'is_current' => $makeCurrent ? true : $fiscalYear->is_current,
                'is_closed' => $makeCurrent ? false : $fiscalYear->is_closed,
            ]);

            ChallaniFormat::withoutGlobalScope('fiscal_year')
                ->where('fiscal_year_id', $fiscalYear->id)
                ->update(['format_prefix' => $fiscalYear->name]);

            if ($makeCurrent) {
                $format = ChallaniFormat::withoutGlobalScope('fiscal_year')
                    ->where('fiscal_year_id', $fiscalYear->id)
                    ->first();

                if ($format) {
                    $format->update(['is_active' => true, 'format_prefix' => $fiscalYear->name]);
                } else {
                    ChallaniFormat::withoutGlobalScope('fiscal_year')->create([
                        'fiscal_year_id' => $fiscalYear->id,
                        'format_prefix' => $fiscalYear->name,
                        'is_active' => true,
                    ]);
                }
            }
        });

        if ($makeCurrent) {
            FiscalYearContext::set($fiscalYear->id);
        }

        return back()->with('success', 'वित्तीय वर्ष अद्यावधिक भयो।');
    }

    public function destroy(FiscalYear $fiscalYear)
    {
        abort_if($fiscalYear->is_current, 422, 'चालु वित्तीय वर्ष मेटाउन मिल्दैन।');

        $tables = [
            'mudda_dartas',
            'banking_muddas',
            'patra_challanis',
            'aviyog_challanis',
            'punarabedans',
            'challanis',
            'challani_formats',
        ];

        foreach ($tables as $table) {
            if (DB::table($table)->where('fiscal_year_id', $fiscalYear->id)->exists()) {
                return back()->with('error', 'यस वित्तीय वर्षसँग सम्बन्धित डाटा भएकाले मेटाउन मिल्दैन।');
            }
        }

        $fiscalYear->delete();

        if (FiscalYearContext::current()->id === $fiscalYear->id) {
            FiscalYearContext::resetToCurrent();
        }

        return back()->with('success', 'वित्तीय वर्ष मेटाइयो।');
    }

    private function validateFiscalYear(Request $request, ?int $ignoreId = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'start_year' => (int) $request->input('start_year'),
            'end_year' => (int) $request->input('end_year'),
        ]);

        $unique = 'unique:fiscal_years,name' . ($ignoreId ? ',' . $ignoreId : '');

        return $request->validate([
            'name' => ['required', 'regex:/^\\d{4}\\/\\d{3}$/', $unique],
            'start_year' => ['required', 'integer', 'min:1900', 'max:2500'],
            'end_year' => ['required', 'integer', 'gt:start_year', 'max:2501'],
            'is_current' => ['nullable', 'boolean'],
        ], [
            'name.regex' => 'वित्तीय वर्ष 2083/084 जस्तो ४ अंक/३ अंक ढाँचामा हुनुपर्छ।',
            'end_year.gt' => 'समाप्ति वर्ष सुरु वर्षभन्दा ठूलो हुनुपर्छ।',
        ]);
    }

    public function switch(FiscalYear $fiscalYear)
    {
FiscalYearContext::set($fiscalYear->id);

        return back()->with(
            'success',
            'वित्तीय वर्ष ' . $fiscalYear->display_name . ' चयन गरिएको छ।'
        );
    }

    public function startNext(Request $request)
    {
$current = FiscalYear::where('is_current', true)->firstOrFail();
        $nextStartYear = (int) $current->end_year;
        $nextEndYear = $nextStartYear + 1;
        $expectedName = $nextStartYear . '/' . substr((string) $nextEndYear, -3);

        $request->merge(['name' => trim((string) $request->input('name'))]);
        $request->validate([
            'name' => ['required', 'regex:/^\d{4}\/\d{3}$/', 'in:' . $expectedName],
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
            ChallaniFormat::withoutGlobalScope('fiscal_year')
                ->where('is_active', true)
                ->update(['is_active' => false]);

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