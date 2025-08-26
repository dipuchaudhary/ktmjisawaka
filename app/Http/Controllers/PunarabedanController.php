<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Challani;
use App\Models\Punarabedan;
use Illuminate\Http\Request;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PunarabedanController extends Controller
{
    protected string $format;

    public function __construct()
    {
        $this->format = ChallaniFormat::value('format_prefix') ?? '2082/083';
    }

    protected $rules = [
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'pratiwadi_name.*' => 'required|string|max:255',
            'mudda_sthiti' => 'required|array',
            'mudda_sthiti.*' => 'required|string|in:फरार,पक्राउ,हाजिरि जमानीमा छोडेको,तामेली,नचल्ने,कारागार',
            'faisala_date' => 'required',
            'faisala_pramanikaran_date' => 'required',
            'suchana_date' => 'required',
            'faisala_garne_nikaye' => 'required',
            'punarabedan' => 'nullable',
            'punarabedan_date' => 'nullable',
            'punarabedan_challani_number' => 'required_if:is_punarabedan_challani_number_visible,true',
        ];

    protected $customMessages = [
           'mudda_name.required' => 'मुद्दाको नाम अनिवार्य छ।',
           'mudda_number.required' => 'राय दर्ता नं. अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.0.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'pratiwadi_name.*.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_sthiti.0.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_sthiti.*.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'faisala_date.required' => 'फैसाला मिति अनिवार्य छ।',
           'faisala_pramanikaran_date.required' => 'फैसाला प्रमाणीकरण मिति अनिवार्य छ।',
           'suchana_date.required' => 'सूचना प्राप्त मिति अनिवार्य छ।',
           'faisala_garne_nikaye.required' => 'फैसाला गर्ने निकाय अनिवार्य छ।',
           'punarabedan.required' => 'पुवे/दो.पा अनिवार्य छ।',
           'punarabedan_date.required' => 'चलानी मिति अनिवार्य छ।',
           'punarabedan_challani_number.required' => 'चलानी नं. अनिवार्य छ।',
        ];

    /**
     * Display a listing of the resource.
     */
    public function index() {

        if(request()->ajax())
        {
            $query = Punarabedan::select('id', 'mudda_number', 'jaherwala_name','pratiwadi_name','mudda_name','faisala_date','faisala_pramanikaran_date','punarabedan','punarabedan_date','punarabedan_challani_number','user_name','status','adalat_mudda_number')->orderBy('id', 'desc');
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
                ->addColumn('action', function ($data) {
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
        return view('frontend.punarabedan.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        $buttons = '';
        if (!auth()->check()) return $buttons;
        $buttons = '<div style="display: inline-flex; align-items: center; gap: 8px;">';
        if (auth()->user()->can('punarabedan-edit')) {
            $buttons .= '<a href="'.route('punarabedan.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
        }

        if (auth()->user()->can('punarabedan-delete')) {
            $buttons .= '<form action="'.route('punarabedan.destroy', $data->id).'" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-delete" onclick="return confirm(\'Are you sure?\')" style="border:none; background:none; color:red; padding:0;">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>';
        }
        $buttons .= '</div>';
        return $buttons;
    }

	 /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('punarabedan-create')) {
             abort(403, 'You do not have permissions');
        }

        $latest = Challani::orderByDesc('id')->first();

        $nextId = $latest ? $latest->id + 1 : 1;
        $nextChallaniNumber = $this->format . '-' . $nextId;
        return view('frontend.punarabedan.create', compact('nextChallaniNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->customMessages);

        $jaherwala_name = $request->input('jaherwala_name');

        $pratiwadiList = [];
        $names = is_array($request->input('pratiwadi_name')) ? $request->input('pratiwadi_name') : [];
        $sthiti = is_array($request->input('mudda_sthiti')) ? $request->input('mudda_sthiti') : [];

        foreach ($names as $index => $name) {
            $pratiwadiList[] = [
                'name' => $name,
                'status' => $sthiti[$index] ?? null,
            ];
        }
        $pratiwadi_name = json_encode($pratiwadiList, JSON_UNESCAPED_UNICODE);
        if (empty($request->input('punarabedan')) || $request->input('punarabedan') === 'सफल') {
            $punarabedan_date = '';
            $punarabedan_challani_number = '';
        } else {
            $punarabedan_date = $request->input('punarabedan_date') ?? '';
            $punarabedan_challani_number = $request->input('punarabedan_challani_number') ?? '';
        }

        $status = empty($request->input('punarabedan')) ? false : true;
        $InsertData = [
            'mudda_number' => 'पुनरावेदन-'. $request->input('mudda_number'),
            'adalat_mudda_number' => $request->input('adalat_mudda_number'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'mudda_name' => $request->input('mudda_name'),
            'faisala_date' => $request->input('faisala_date'),
            'faisala_pramanikaran_date' => $request->input('faisala_pramanikaran_date'),
            'suchana_date' => $request->input('suchana_date'),
            'faisala_garne_nikaye' => $request->input('faisala_garne_nikaye'),
            'pra_kaid' => $request->input('pra_kaid'),
            'pra_jariwana' => $request->input('pra_jariwana'),
            'pra_xatipurti' => $request->input('pra_xatipurti'),
            'pra_bigo' => $request->input('pra_bigo'),
            'pra_multabi' => $request->input('pra_multabi'),
            'faisala_kaid' => $request->input('faisala_kaid'),
            'faisala_jariwana' => $request->input('faisala_jariwana'),
            'faisala_xatipurti' => $request->input('faisala_xatipurti'),
            'faisala_bigo' => $request->input('faisala_bigo'),
            'punarabedan' => $request->input('punarabedan'),
            'punarabedan_date' => $punarabedan_date,
            'punarabedan_challani_number' => $punarabedan_challani_number,
            'nirnaye' => $request->input('nirnaye'),
            'nirnaye_date' => $request->input('nirnaye_date'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'kaifiyat' => $request->input('kaifiyat'),
            'status' => $status,
            'user_name' => auth()->user()->name,
        ];

        if ($InsertData['status']== true && (!empty($request->input('punarabedan')) || $request->input('punarabedan') !== 'सफल'  ) ) {
            $challaniNumber = $request->input('punarabedan_challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
                $InsertData['punarabedan_challani_number'] = $challaniNumber;
            }
        }
        Punarabedan::create($InsertData);
        return redirect()->route('punarabedan.index')
        ->with('success', 'पुनरावेदन दर्ता सफल भयो।');
    }

    public function edit($id){

        if (!Gate::allows('punarabedan-edit')) {
             abort(403, 'You do not have permissions');
        }

        $punarabedan = Punarabedan::findOrFail($id);
        $latest = Challani::orderByDesc('id')->first();

        $nextId = $latest ? $latest->id + 1 : 1;
        $nextChallaniNumber = $this->format . '-' . $nextId;
        return view('frontend.punarabedan.edit', compact('punarabedan','nextChallaniNumber'));
    }

    public function update(Request $request, $id){
        $punarabedan = Punarabedan::findOrFail($id);
        $this->validate($request, $this->rules, $this->customMessages);

        $jaherwala_name = is_array($request->input('jaherwala_name'))
                        ? implode(',', $request->input('jaherwala_name'))
                        : $request->input('jaherwala_name');

        $pratiwadiList = [];
        $names = is_array($request->input('pratiwadi_name')) ? $request->input('pratiwadi_name') : [];
        $sthiti = is_array($request->input('mudda_sthiti')) ? $request->input('mudda_sthiti') : [];

        foreach ($names as $index => $name) {
            $pratiwadiList[] = [
                'name' => $name,
                'status' => $sthiti[$index] ?? null,
            ];
        }
        $pratiwadi_name = json_encode($pratiwadiList, JSON_UNESCAPED_UNICODE);
        if (empty($request->input('punarabedan')) || $request->input('punarabedan') === 'सफल' || $punarabedan->status == false) {
            $punarabedan_date = '';
            $punarabedan_challani_number = '';
        } else {
            $punarabedan_date = $request->input('punarabedan_date') ?? '';
            $punarabedan_challani_number = $request->input('punarabedan_challani_number') ?? '';
        }

        if (preg_match('/^पुनरावेदन-/u', $request->input('mudda_number'))) {
            $mudda_number = preg_replace('/^(पुनरावेदन-)+/u', 'पुनरावेदन-', $request->input('mudda_number'));
        } else {
            $mudda_number = $request->input('mudda_number');
        }
        $status = empty($request->input('punarabedan')) ? false : true;
        $punarabedan->update([
            'mudda_number' => $mudda_number,
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'mudda_name' => $request->input('mudda_name'),
            'faisala_date' => $request->input('faisala_date'),
            'faisala_pramanikaran_date' => $request->input('faisala_pramanikaran_date'),
            'suchana_date' => $request->input('suchana_date'),
            'faisala_garne_nikaye' => $request->input('faisala_garne_nikaye'),
            'pra_kaid' => $request->input('pra_kaid'),
            'pra_jariwana' => $request->input('pra_jariwana'),
            'pra_xatipurti' => $request->input('pra_xatipurti'),
            'pra_bigo' => $request->input('pra_bigo'),
            'pra_multabi' => $request->input('pra_multabi'),
            'faisala_kaid' => $request->input('faisala_kaid'),
            'faisala_jariwana' => $request->input('faisala_jariwana'),
            'faisala_xatipurti' => $request->input('faisala_xatipurti'),
            'faisala_bigo' => $request->input('faisala_bigo'),
            'punarabedan' => $request->input('punarabedan'),
            'punarabedan_date' => $punarabedan_date,
            'punarabedan_challani_number' => $punarabedan_challani_number,
            'nirnaye' => $request->input('nirnaye'),
            'nirnaye_date' => $request->input('nirnaye_date'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'kaifiyat' => $request->input('kaifiyat'),
            'status' => $status,
            'user_name' => auth()->user()->name,
        ]);

        if ( isset($punarabedan) && $punarabedan->status == true && $request->input('punarabedan') !== 'सफल' ) {
            $challaniNumber = $request->input('punarabedan_challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }

        return redirect()->route('punarabedan.index')
        ->with('success', 'पुनरावेदन सफलतापूर्वक अपडेट भयो।');
    }

    public function destroy($id){

        if (!Gate::allows('punarabedan-delete')) {
             abort(403, 'You do not have permissions');
        }

        $punarabedan = Punarabedan::findOrFail($id);
        $punarabedan->delete();
        return redirect()->route('punarabedan.index')
        ->with('success', 'पुनरावेदन सफलतापूर्वक मेटियो।');
    }
}
