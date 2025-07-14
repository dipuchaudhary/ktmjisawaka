<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MuddaReportController extends Controller
{
    public function overallStatus(Request $request)
    {
        if ($request->ajax()) {
            // Base unified structure
            $muddaDarta = DB::table('mudda_dartas')
                ->select([
                    'mudda_dartas.id',
                    'mudda_number',
                    'jaherwala_name',
                    'pratiwadi_name',
                    'mudda_name',
                    DB::raw('NULL as sarkariwakil_name'),
                    DB::raw('NULL as challani_number'),
                    DB::raw('NULL as upload_date'),
                    DB::raw('NULL as user_name'),
                    DB::raw('NULL as status'),
                    DB::raw('NULL as dopa'),
                    DB::raw("'mudda_darta' as source")
                ]);

            $bankingMudda = DB::table('banking_muddas')
                ->select([
                    'id',
                    'mudda_number',
                    'jaherwala_name',
                    'pratiwadi_name',
                    'mudda_name',
                    'sarkariwakil_name',
                    'challani_number',
                    DB::raw('NULL as upload_date'),
                    'user_name',
                    'status',
                    DB::raw('NULL as dopa'),
                    DB::raw("'banking_mudda' as source")
                ]);

            $aviyog = DB::table('aviyog_challanis')
                ->select([
                    'id',
                    'mudda_number',
                    'jaherwala_name',
                    'pratiwadi_name',
                    'mudda_name',
                    DB::raw('NULL as sarkariwakil_name'),
                    'challani_number',
                    'upload_date',
                    'user_name',
                    'status',
                    DB::raw('NULL as dopa'),
                    DB::raw("'aviyog_challani' as source")
                ]);

            $punarabedan = DB::table('punarabedans')
                ->select([
                    'id',
                    'mudda_number',
                    'jaherwala_name',
                    'pratiwadi_name',
                    'mudda_name',
                    DB::raw('NULL as sarkariwakil_name'),
                    'punarabedan_challani_number as challani_number',
                    'punarabedan_date as upload_date',
                    'user_name',
                    'status',
                    'punarabedan as dopa',
                    DB::raw("'punarabedan' as source")
                ]);

            // Combine all queries
            $unionQuery = $muddaDarta
                ->unionAll($bankingMudda)
                ->unionAll($aviyog)
                ->unionAll($punarabedan);

            // Group by mudda_number
            $records = DB::query()->fromSub($unionQuery, 'unified')
                ->get()
                ->groupBy('mudda_number');

            $final = $records->map(function ($items) {
                $pratiwadiRaw = $items[0]->pratiwadi_name ?? '';
                $pratiwadiHTML = '-';

                // Decode JSON if valid
                if (!empty($pratiwadiRaw) && is_string($pratiwadiRaw)) {
                    $decoded = json_decode($pratiwadiRaw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $html = '';
                        foreach ($decoded as $entry) {
                            $name = e($entry['name'] ?? '-');
                            $status = e($entry['status'] ?? '-');
                            $html .= "<small class='badge rounded-pill bg-dark text-white'>{$name} ({$status})</small><br>";
                        }
                        $pratiwadiHTML = $html;
                    }
                }

                $base = [
                    'mudda_number' => $items[0]->mudda_number,
                    'jaherwala_name' => $items[0]->jaherwala_name ?? '',
                    'pratiwadi_name' => $pratiwadiHTML,
                    'mudda_name' => $items[0]->mudda_name ?? '',
                    'banking_sarkariwakil_name' => '',
                    'banking_challani_number' => '',
                    'banking_user_name' => '',
                    'banking_status' => '',
                    'aviyog_upload_date' => '',
                    'aviyog_challani_number' => '',
                    'aviyog_user_name' => '',
                    'aviyog_status' => '',
                    'punarabedan_dopa' => '',
                    'punarabedan_date' => '',
                    'punarabedan_challani_number' => '',
                    'punarabedan_user_name' => '',
                    'punarabedan_status' => '',
                ];

                foreach ($items as $item) {
                    switch ($item->source) {
                        case 'banking_mudda':
                            $base['banking_sarkariwakil_name'] = $item->sarkariwakil_name;
                            $base['banking_challani_number'] = $item->challani_number;
                            $base['banking_user_name'] = $item->user_name;
                            $base['banking_status'] = $item->status == 1
                                ? "<span class='badge bg-success'>Done</span>"
                                : "<span class='badge bg-danger'>Pending</span>";
                            break;
                        case 'aviyog_challani':
                            $base['aviyog_upload_date'] = $item->upload_date;
                            $base['aviyog_challani_number'] = $item->challani_number;
                            $base['aviyog_user_name'] = $item->user_name;
                            $base['aviyog_status'] = $item->status == 1
                                ? "<span class='badge bg-success'>Done</span>"
                                : "<span class='badge bg-danger'>Pending</span>";
                            break;
                        case 'punarabedan':
                            $base['punarabedan_dopa'] = $item->dopa;
                            $base['punarabedan_date'] = $item->upload_date;
                            $base['punarabedan_challani_number'] = $item->challani_number;
                            $base['punarabedan_user_name'] = $item->user_name;
                            $base['punarabedan_status'] = $item->status == 1
                                ? "<span class='badge bg-success'>Done</span>"
                                : "<span class='badge bg-danger'>Pending</span>";
                            break;
                    }
                }

                return $base;
            })->values();

            return DataTables::of($final)
                ->rawColumns([
                    'pratiwadi_name',
                    'banking_status',
                    'aviyog_status',
                    'punarabedan_status'
                ])
                ->make(true);
        }

        return view('frontend.MuddaReport.index');
    }


}
