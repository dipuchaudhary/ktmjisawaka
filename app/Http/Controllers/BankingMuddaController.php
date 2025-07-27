<?php

namespace App\Http\Controllers;
use auth;
use App\Models\Challani;
use App\Models\Punarabedan;
use App\Models\BankingMudda;
use Illuminate\Http\Request;
use App\Models\AviyogChallani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BankingMuddaController extends Controller
{
    protected string $format;

    public function __construct()
    {
        $this->format = ChallaniFormat::value('format_prefix') ?? '2082/083';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax())
        {
            $query = BankingMudda::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','pratiwadi_number','mudda_date','mudda_myad','sarkariwakil_name','challani_number','user_name','status');
            $user = auth()->user();
             if ( $user ) {
                $query->where(function ($q) use ($user) {
                    $canShowPending = $user->hasPermissionTo('show-pending');
                    $canShowDone = $user->hasPermissionTo('show-done');

                    if ($canShowPending && !$canShowDone) {
                        $q->where('status', 0);
                    } elseif (!$canShowPending && $canShowDone) {
                        $q->where('status', 1);
                    } else {
                        $q->whereIn('status', [0, 1]);
                    }
                });
            }
            return Datatables::eloquent($query)
                   ->addIndexColumn()
                   ->addColumn('pratiwadi_name', function($row) {
                    if (is_string($row->pratiwadi_name)) {
                        $data = json_decode($row->pratiwadi_name, true);
                    }
                    if (is_array($data)) {
                        $html = '';
                        foreach ($data as $pratiwadi) {
                            $name = e($pratiwadi['name'] ?? '-');
                            $status = e($pratiwadi['status'] ?? '-');
                            $html .= "<small class='badge rounded-pill text-white bg-dark'>{$name} ({$status})</small><br>";
                        }
                        return $html;
                    }
                    return '-';
                })
                   ->addColumn('status', function ($data) {
                    if ($data->status == 0 || $data->status === false) {
                            return '<span class="badge rounded-pill text-white bg-danger">Pending</span>';
                    } else {
                            return '<span class="badge rounded-pill text-white bg-success">Done</span>';
                    }
					})
                   ->addColumn('action',function($data){
                        return $this->getActionButtons($data);
                   })
                   ->filterColumn('pratiwadi_name', function($query, $keyword) {
                        $query->where(function($q) use ($keyword) {
                            $q->whereRaw(
                                "JSON_SEARCH(pratiwadi_name, 'one', ?, NULL, '$[*].name') IS NOT NULL OR
                                    JSON_SEARCH(pratiwadi_name, 'one', ?, NULL, '$[*].status') IS NOT NULL",
                                ["%{$keyword}%", "%{$keyword}%"]
                            );
                        });
                    })
                   ->rawColumns(['pratiwadi_name','status','action'])
                   ->make(true);
        }
       return view('frontend.banking_mudda.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        $buttons = '';
        if (!auth()->check()) return $buttons;
        $buttons = '<div style="display: inline-flex; align-items: center; gap: 8px;">';

        if (auth()->user()->can('bankingdarta-edit')) {
            $buttons .= '<a href="'.route('banking_mudda.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
        }

        if (auth()->user()->can('bankingdarta-delete')) {
            $buttons .= '<form action="'.route('banking_mudda.destroy', $data->id).'" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-delete" onclick="return confirm(\'Are you sure?\')" style="border:none; background:none; color:red; padding:0;">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>';
        }
        $buttons .='</div>';
        return $buttons;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('bankingdarta-create')) {
             abort(403, 'You do not have permissions');
        }

        $count = BankingMudda::whereNotNull('challani_number')->count();

        $nextId = $count + 1;

        $ChallaniNumber = $this->format . '-' . $nextId;

        return view('frontend.banking_mudda.create',compact('ChallaniNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'pratiwadi_name.*' => 'required|string|max:255',
            'mudda_sthiti' => 'required|array',
            'mudda_sthiti.*' => 'required|string|in:फरार,पक्राउ,हाजिरि जमानीमा छोडेको,तामेली,नचल्ने',
            'mudda_date' => 'required',
            'status' => 'required|boolean',
        ];
        $Messages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.0.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'pratiwadi_name.*.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_sthiti.0.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_sthiti.*.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $Messages);

        $jaherwala_name = $request->input('jaherwala_name');

        $names = (array) $request->input('pratiwadi_name');
        $sthiti = (array) $request->input('mudda_sthiti');

        $pratiwadiList = [];

        foreach ($names as $index => $name) {
            $pratiwadiList[] = [
                'name' => $name,
                'status' => $sthiti[$index] ?? null,
            ];
        }

        $pratiwadi_name = json_encode($pratiwadiList, JSON_UNESCAPED_UNICODE);
        // Store the data in the database
        $Insertdata = [
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => $request->input('mudda_number'),
            'mudda_name' => 'बैकिङ्ग-'. $request->input('mudda_name'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'pesi_karyala' => $request->input('pesi_karyala'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_myad' => $request->input('mudda_myad'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'mudda_pathayko_date' => $request->input('mudda_pathayko_date'),
            'status' => $request->input('status'),
            'kaifiyat' => $request->input('kaifiyat'),
            'user_name' => auth()->user()->name,
        ];

        if ($request->input('status') == '1' || $request->input('status') === true) {
            $Insertdata['challani_number'] = $request->input('challani_number');
        }

        BankingMudda::create($Insertdata);

        $this->createPunarabedan($request,$jaherwala_name,$pratiwadi_name);
        return redirect()->route('banking_mudda.index')
        ->with('success', 'बैकिङ्ग मुद्दा दर्ता सफल भयो।');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Gate::allows('bankingdarta-edit')) {
             abort(403, 'You do not have permissions');
        }

        $bankingmudda = BankingMudda::findOrFail($id);
        $count = BankingMudda::whereNotNull('challani_number')->count();

        $nextId = $count + 1;

        $ChallaniNumber = $this->format . '-' . $nextId;


        return view('frontend.banking_mudda.edit', compact('bankingmudda','ChallaniNumber'));
    }


    protected function createPunarabedan($request,$jaherwala_name,$pratiwadi_name) {
        Punarabedan::create([
            'mudda_name'               => 'बैकिङ्ग-'. $request->input('mudda_name'),
            'jaherwala_name'           => $jaherwala_name,
            'pratiwadi_name'           => $pratiwadi_name,
            'mudda_number'             => $request->input('mudda_number'),
            'suchana_date'             => null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'pratiwadi_name.*' => 'required|string|max:255',
            'mudda_sthiti' => 'required|array',
            'mudda_sthiti.*' => 'required|string|in:फरार,पक्राउ,हाजिरि जमानीमा छोडेको,तामेली,नचल्ने',
            'mudda_date' => 'required',
            'status' => 'required|boolean',
        ];
        $Messages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.0.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'pratiwadi_name.*.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_sthiti.0.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_stithi.*.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
        ];
        $bankingmudda = BankingMudda::findOrFail($id);
        $this->validate($request, $rules, $Messages);

        $jaherwala_name = $request->input('jaherwala_name');

        $names = (array) $request->input('pratiwadi_name');
        $sthiti = (array) $request->input('mudda_sthiti');

        $pratiwadiList = [];

        foreach ($names as $index => $name) {
            $pratiwadiList[] = [
                'name' => $name,
                'status' => $sthiti[$index] ?? null,
            ];
        }

        $pratiwadi_name = json_encode($pratiwadiList, JSON_UNESCAPED_UNICODE);

        $challaniNumber = $request->input('challani_number');
        $shouldAssignChallani = $request->status && !BankingMudda::where('challani_number', $challaniNumber)->where('id', '!=', $bankingmudda->id)->exists();
        $updatedata = [
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => 'बैकिङ्ग-'. $request->input('mudda_number'),
            'mudda_name' => $request->input('mudda_name'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'pesi_karyala' => $request->input('pesi_karyala'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_myad' => $request->input('mudda_myad'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'faat_name' => $request->input('faat_name'),
            'mudda_pathayko_date' => $request->input('mudda_pathayko_date'),
            'status' => $request->input('status'),
            'kaifiyat' => $request->input('kaifiyat'),
            'user_name' => auth()->user()->name,
        ];

        $updatedata['challani_number'] = $shouldAssignChallani ? $challaniNumber : $bankingmudda->challani_number;
        $bankingmudda->update($updatedata);

        $this->updatePunarabedan($bankingmudda,$request,$jaherwala_name,$pratiwadi_name);

        return redirect()->route('banking_mudda.index')
        ->with('success', 'बैकिङ्ग मुद्दा सफलतापूर्वक अपडेट भयो।');
    }


    protected function updatePunarabedan($bankingmudda,$request,$jaherwala_name,$pratiwadi_name){
        $punarabedan = Punarabedan::where('id', $bankingmudda->id)->first();

        if ($punarabedan) {
            $punarabedan->update([
            'mudda_name'               => 'बैकिङ्ग-'. $request->input('mudda_name'),
            'jaherwala_name'           => $jaherwala_name,
            'pratiwadi_name'           => $pratiwadi_name,
            'mudda_number'             => $request->input('mudda_number'),
            'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
            ]);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('bankingdarta-delete')) {
             abort(403, 'You do not have permissions');
        }

        $bankingmudda = BankingMudda::findOrFail($id);
        $bankingmudda->delete();
        return redirect()->route('banking_mudda.index')
        ->with('success', 'बैकिङ्ग मुद्दा सफलतापूर्वक मेटियो।');
    }
}
