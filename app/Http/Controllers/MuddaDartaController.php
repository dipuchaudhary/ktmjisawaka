<?php

namespace App\Http\Controllers;
use auth;
use App\Models\MuddaDarta;
use App\Models\Punarabedan;
use Illuminate\Http\Request;
use App\Models\PatraChallani;
use App\Models\AviyogChallani;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\DataTables\MuddaDartaDataTable;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MuddaDartaController extends Controller
{

    protected $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'pratiwadi_name.*' => 'required|string|max:255',
            'mudda_sthiti' => 'required|array',
            'mudda_sthiti.*' => 'required|string|in:फरार,पक्राउ,हाजिरि जमानीमा छोडेको,तामेली,नचल्ने',
            'mudda_date' => 'required',
            'mudda_bibran' => 'required',
        ];
    protected $customMessages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दाको किसिम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'pratiwadi_name.*.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_sthiti.0.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_stithi.*.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
           'mudda_bibran.required' => 'मुद्दा विवरण अनिवार्य छ।',
        ];

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if(request()->ajax())
        {
            $query = MuddaDarta::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','pratiwadi_number','mudda_date','sarkariwakil_name','faat_name','user_name');

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

                ->addColumn('action', function($row) {
                    return $this->getActionButtons($row);
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
                ->rawColumns(['pratiwadi_name', 'action'])
                ->make(true);
        }
        return view('frontend.mudda_darta.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        if (!auth()->check()) return '';
        $buttons = '';
        $buttons = '<div style="display: inline-flex; align-items: center; gap: 8px;">';
        if (auth()->user()->can('mulldarta-edit')) {
            $buttons .= '<a href="'.route('mudda_darta.edit', $data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
        }

        if (auth()->user()->can('mulldarta-delete')) {
            $buttons .= '<form action="' . route('mudda_darta.destroy', $data->id) . '" method="POST" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn-delete" onclick="return confirm(\'Are you sure?\')" style="border:none; background:none; color:red; padding:0;">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </button>
                    </form>';
        }
        $buttons .='</div>';
        return $buttons;
    }

    public function create(Request $request)
    {
        if (!Gate::allows('mulldarta-create')) {
             abort(403, 'You do not have permissions');
        }

        return view('frontend.mudda_darta.create');

    }

    public function store(Request $request)
    {

        $this->validate($request, $this->rules, $this->customMessages);

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
        MuddaDarta::create([
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => $request->input('mudda_number'),
            'mudda_name' => $request->input('mudda_name'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_suru_myad' => $request->input('mudda_suru_myad'),
            'mudda_myad_thap' => $request->input('mudda_myad_thap'),
            'jamma_din' => $request->input('jamma_din'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'faat_name' => $request->input('faat_name'),
            'mudda_bibran' => $request->input('mudda_bibran'),
            'user_name' => auth()->user()->name,
            'kaifiyat' => $request->input('kaifiyat'),
        ]);
        // Create PatraChallani and Punarabedan using common fields
        $this->createAviyogChallani($request,$jaherwala_name,$pratiwadi_name);
        $this->createPunarabedan($request,$jaherwala_name,$pratiwadi_name);

        return redirect()->route('mudda_darta.index')
        ->with('success', 'राय दर्ता सफल भयो।');
    }
    public function edit($id)
    {
        if (!Gate::allows('mulldarta-edit')) {
             abort(403, 'You do not have permissions');
        }

        $mudda = MuddaDarta::findOrFail($id);
        return view('frontend.mudda_darta.edit', compact('mudda'));
    }
    public function update(Request $request, $id)
    {
        $mudda = MuddaDarta::findOrFail($id);
        $this->validate($request, $this->rules, $this->customMessages);

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
        $mudda->update([
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => $request->input('mudda_number'),
            'mudda_name' => $request->input('mudda_name'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_suru_myad' => $request->input('mudda_suru_myad'),
            'mudda_myad_thap' => $request->input('mudda_myad_thap'),
            'jamma_din' => $request->input('jamma_din'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'faat_name' => $request->input('faat_name'),
            'mudda_bibran' => $request->input('mudda_bibran'),
            'user_name' => auth()->user()->name,
            'kaifiyat' => $request->input('kaifiyat'),
        ]);

        return redirect()->route('mudda_darta.index')
        ->with('success', 'राय सफलतापूर्वक अपडेट भयो।');
    }

    protected function createAviyogChallani($request,$jaherwala_name,$pratiwadi_name) {
        AviyogChallani::create([
            'challani_date'            => null,
            'challani_number'          => null,
            'jaherwala_name'           => $jaherwala_name,
            'pratiwadi_name'           => $pratiwadi_name,
            'mudda_name'               => $request->input('mudda_name'),
            'gender'                   => null,
            'mudda_number'             => $request->input('mudda_number'),
            'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
            'faat_name'                => $request->input('faat_name'),
            'anusandhan_garne_nikaye'  => $request->input('anusandhan_garne_nikaye'),
            'kaifiyat'                 => '',
        ]);
    }

    protected function createPunarabedan($request,$jaherwala_name,$pratiwadi_name) {
        Punarabedan::create([
            'mudda_name'               => $request->input('mudda_name'),
            'jaherwala_name'           => $jaherwala_name,
            'pratiwadi_name'           => $pratiwadi_name,
            'mudda_number'             => $request->input('mudda_number'),
            'suchana_date'             => null,
        ]);
    }

    protected function updateAviyogchallani($mudda,$request,$pratiwadi_name,$jaherwala_name){
        $aviyogchallani = AviyogChallani::where('id', $mudda->id)->first();

        if ($aviyogchallani) {
            $aviyogchallani->update([
                'jaherwala_name'           => $jaherwala_name,
                'pratiwadi_name'           => $pratiwadi_name,
                'mudda_name'               => $request->input('mudda_name'),
                'mudda_number'             => $request->input('mudda_number'),
                'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
                'faat_name'                => $request->input('faat_name'),
                'anusandhan_garne_nikaye'  => $request->input('anusandhan_garne_nikaye'),
            ]);
        }
    }

    protected function updatePunarabedan($mudda,$request,$pratiwadi_name,$jaherwala_name){
        $punarabedan = Punarabedan::where('id', $mudda->id)->first();

        if ($punarabedan) {
            $punarabedan->update([
            'mudda_name'               => $request->input('mudda_name'),
            'jaherwala_name'           => $jaherwala_name,
            'pratiwadi_name'           => $pratiwadi_name,
            'mudda_number'             => $request->input('mudda_number'),
            'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
            ]);
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('mulldarta-delete')) {
             abort(403, 'You do not have permissions');
        }

        $mudda = MuddaDarta::findOrFail($id);
        $mudda->delete();
        return redirect()->route('mudda_darta.index')
        ->with('success', 'राय सफलतापूर्वक मेटियो।');
    }
}
