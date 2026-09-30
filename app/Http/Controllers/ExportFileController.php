<?php

namespace App\Http\Controllers;

use App\Models\BankingMudda;
use App\Models\MuddaDarta;
use App\Models\PatraChallani;
use App\Models\Punarabedan;
use Illuminate\Http\Request;

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

    public function index(Request $request, string $module)
    {
        abort_unless(isset($this->modules[$module]), 404);

        $definition = $this->modules[$module];
        $model = $definition['model'];

        $records = $model::query()
            ->orderByDesc('id')
            ->get(array_keys($definition['columns']));

        return view('backend.export_file.index', [
            'module' => $module,
            'title' => $definition['title'],
            'columns' => $definition['columns'],
            'records' => $records,
        ]);
    }
}
