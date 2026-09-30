<?php

namespace App\Http\Controllers;

use App\Models\BankingMudda;
use App\Models\MuddaDarta;
use App\Models\PatraChallani;
use App\Models\Punarabedan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Support\FiscalYearContext;

class ExportFileController extends Controller
{
    private array $modules = [
        'mudda-darta' => [
            'title' => 'मुद्दा दर्ता',
            'model' => MuddaDarta::class,
            'columns' => [
                'anusandhan_garne_nikaye' => 'अनुसन्धान गर्ने निकाय',
                'mudda_number' => 'राय दर्ता नं.',
                'mudda_name' => 'मुद्दाको किसिम',
                'jaherwala_name' => 'जाहेरवालाको नाम',
                'pratiwadi_name' => 'प्रतिवादीको नाम',
                'pratiwadi_number' => 'प्रतिवादी संख्या',
                'mudda_date' => 'राय दर्ता मिति',
                'adalat_mudda_number' => 'अदालत मुद्दा नं.',
                'sarkariwakil_name' => 'सरकारी वकील',
                'faat_name' => 'फाँट',
                'user_name' => 'प्रविष्टकर्ता',
            ],
        ],
        'banking-darta' => [
            'title' => 'बैंकिङ्ग मुद्दा दर्ता',
            'model' => BankingMudda::class,
            'columns' => [
                'anusandhan_garne_nikaye' => 'अनुसन्धान गर्ने निकाय',
                'mudda_number' => 'राय दर्ता नं.',
                'mudda_name' => 'मुद्दाको किसिम',
                'jaherwala_name' => 'जाहेरवालाको नाम',
                'pratiwadi_name' => 'प्रतिवादीको नाम',
                'pratiwadi_number' => 'प्रतिवादी संख्या',
                'adalat_mudda_number' => 'अदालत मुद्दा नं.',
                'mudda_date' => 'मुद्दा दर्ता मिति',
                'mudda_myad' => 'म्याद मिति',
                'sarkariwakil_name' => 'सरकारी वकील',
                'challani_number' => 'चलानी नं.',
                'user_name' => 'प्रविष्टकर्ता',
                'status' => 'स्थिति',
            ],
        ],
        'challani' => [
            'title' => 'पत्र चलानी',
            'model' => PatraChallani::class,
            'columns' => [
                'karyalaya_name' => 'कार्यालयको नाम',
                'challani_date' => 'चलानी मिति',
                'challani_number' => 'चलानी नं.',
                'mudda_number' => 'राय/मुद्दा नं.',
                'challani_subject' => 'चलानी विषय',
                'jaherwala_name' => 'जाहेरवालाको नाम',
                'pratiwadi_name' => 'प्रतिवादीको नाम',
                'bodartha' => 'बोधार्थ',
                'verified_by' => 'प्रमाणित गर्ने',
                'challani_sakha' => 'चलानी शाखा',
                'faat' => 'फाँट',
                'user_name' => 'प्रविष्टकर्ता',
                'status' => 'स्थिति',
            ],
        ],
        'punarabedan' => [
            'title' => 'पुनरावेदन',
            'model' => Punarabedan::class,
            'columns' => [
                'mudda_number' => 'मुद्दा नं.',
                'adalat_mudda_number' => 'अदालत मुद्दा नं.',
                'jaherwala_name' => 'जाहेरवालाको नाम',
                'pratiwadi_name' => 'प्रतिवादीको नाम',
                'mudda_name' => 'मुद्दाको किसिम',
                'faisala_date' => 'फैसला मिति',
                'faisala_pramanikaran_date' => 'फैसला प्रमाणीकरण मिति',
                'suchana_date' => 'सूचना प्राप्त मिति',
                'faisala_garne_nikaye' => 'फैसला गर्ने निकाय',
                'punarabedan' => 'पुनरावेदन',
                'punarabedan_date' => 'चलानी मिति',
                'punarabedan_challani_number' => 'चलानी नं.',
                'nirnaye_date' => 'निर्णय मिति',
                'sarkariwakil_name' => 'सरकारी वकील',
                'user_name' => 'प्रविष्टकर्ता',
                'status' => 'स्थिति',
            ],
        ],
    ];

    public function index(Request $request, ?string $module = null)
    {
        if ($module !== null) {
            abort_unless(isset($this->modules[$module]), 404);
        }

        $definition = $module !== null ? $this->modules[$module] : null;
        $records = $definition ? $this->recordsFor($definition) : collect();

        return view('backend.export_file.index', [
            'module' => $module,
            'fiscalYear' => FiscalYearContext::current(),
            'title' => $definition['title'] ?? 'Export File',
            'columns' => $definition['columns'] ?? [],
            'records' => $records,
            'modules' => collect($this->modules)->mapWithKeys(fn ($item, $key) => [$key => $item['title']])->all(),
        ]);
    }

    /**
     * Export the currently selected module as an Excel-compatible workbook.
     *
     * The HTML workbook format keeps Nepali/Devanagari text intact without
     * requiring an additional server-side spreadsheet package.
     */
    public function excel(Request $request, string $module): StreamedResponse
    {
        abort_unless(isset($this->modules[$module]), 404);

        $definition = $this->modules[$module];
        $records = $this->recordsFor($definition);
        $title = $definition['title'];
        $filename = $this->filename($title, 'xls');

        return response()->streamDownload(function () use ($definition, $records, $title) {
            echo "\xEF\xBB\xBF";
            echo '<html><head><meta charset="UTF-8">';
            echo '<style>body{font-family:Arial,sans-serif}table{border-collapse:collapse}th,td{border:1px solid #999;padding:6px}th{font-weight:bold}</style>';
            echo '</head><body>';
            echo '<h3>' . e($title . ' - आ.व. ' . \App\Support\FiscalYearContext::current()->display_name) . '</h3>';
            echo '<table><thead><tr>';

            foreach ($definition['columns'] as $label) {
                echo '<th>' . e($label) . '</th>';
            }

            echo '</tr></thead><tbody>';

            foreach ($records as $record) {
                echo '<tr>';
                foreach ($definition['columns'] as $field => $label) {
                    echo '<td>' . e($this->displayValue($record->{$field}, $field)) . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /**
     * Print-ready HTML report. Browser print dialog is used so the office
     * can select printer, paper size, orientation and save as PDF if needed.
     */
    public function print(Request $request, string $module)
    {
        abort_unless(isset($this->modules[$module]), 404);

        $definition = $this->modules[$module];

        return view('backend.export_file.print', [
            'title' => $definition['title'],
            'fiscalYear' => FiscalYearContext::current(),
            'columns' => $definition['columns'],
            'records' => $this->recordsFor($definition),
        ]);
    }

    private function recordsFor(array $definition)
    {
        return $definition['model']::query()
            ->where('fiscal_year_id', FiscalYearContext::id())
            ->orderByDesc('id')
            ->get(array_keys($definition['columns']));
    }

    private function displayValue($value, string $field): string
    {
        if ($field === 'pratiwadi_name' && is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return collect($decoded)->map(function ($item) {
                    $name = $item['name'] ?? '';
                    $status = $item['status'] ?? '';
                    return trim($name . ($status ? ' (' . $status . ')' : ''));
                })->filter()->implode(', ');
            }
        }

        if (is_array($value)) {
            return collect($value)->map(function ($item) {
                return is_array($item) ? implode(' ', $item) : $item;
            })->implode(', ');
        }

        if ($field === 'status') {
            return ($value === true || $value === 1 || $value === '1') ? 'Done' : 'Pending';
        }

        return $value === null || $value === '' ? '-' : (string) $value;
    }

    private function filename(string $title, string $extension): string
    {
        return preg_replace('/[^A-Za-z0-9_-]+/', '-', strtolower($title))
            . '-export.' . $extension;
    }
}
