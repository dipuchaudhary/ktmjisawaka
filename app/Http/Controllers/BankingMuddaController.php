<?php

namespace App\Http\Controllers;
use App\Models\BankingMudda;
use Illuminate\Http\Request;
use App\Models\AviyogChallani;
use App\Models\Punarabedan;
use App\Models\Challani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BankingMuddaController extends Controller
{
    protected $format = '';
    protected $ChallaniNumber = '';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax())
        {
            $query = BankingMudda::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','mudda_stithi','mudda_date','sarkariwakil_name','challani_number','status');

            return Datatables::eloquent($query)
                   ->addIndexColumn()
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

                   ->rawColumns(['status','action'])
                   ->make(true);
        }
       return view('frontend.banking_mudda.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        if (!auth()->check()) return '';
        $buttons = '';

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

        return $buttons;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $latest = Challani::orderByDesc('id')->first();
        $challani_format = ChallaniFormat::value('format_prefix');
        $this->format = $challani_format;
        if ($latest && $latest->id) {
        $nextChallaniNumber = $challani_format .'-'. $latest->id+1;
        $this->ChallaniNumber = $nextChallaniNumber;
        } else {
        $nextChallaniNumber = $challani_format .'-'. '1';
        $this->ChallaniNumber = $nextChallaniNumber;
        }
        return view('frontend.banking_mudda.create',compact('nextChallaniNumber'));
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
            'mudda_stithi' => 'required',
            'mudda_date' => 'required',
        ];
        $Messages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_stithi.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $Messages);
        // Store the data in the database
        $Insertdata = [
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => $request->input('mudda_number'),
            'mudda_name' => $request->input('mudda_name'),
            'jaherwala_name' => $request->input('jaherwala_name'),
            'pratiwadi_name' => $request->input('pratiwadi_name'),
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'mudda_stithi' => $request->input('mudda_stithi'),
            'mudda_bibran' => $request->input('mudda_bibran'),
            'pesi_karyala' => $request->input('pesi_karyala'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_myad' => $request->input('mudda_myad'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'mudda_pathayko_date' => $request->input('mudda_pathayko_date'),
            'status' => $request->input('status'),
            'kaifiyat' => $request->input('kaifiyat'),
        ];

        if ($request->input('status') == '1' || $request->status === 1 ||$request->input('status') === true) {

            $Insertdata['challani_number'] = $request->input('challani_number');
            $challaniNumber = $request->input('challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }

        BankingMudda::create($Insertdata);

        $this->createPunarabedan($request);
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
        $bankingmudda = BankingMudda::findOrFail($id);
        $latest = Challani::orderByDesc('id')->first();
        $challani_format = ChallaniFormat::value('format_prefix');
        $this->format = $challani_format;
        if ($latest && $latest->id) {
        $nextChallaniNumber = $challani_format .'-'. $latest->id+1;
        $this->ChallaniNumber = $nextChallaniNumber;
        } else {
        $nextChallaniNumber = $challani_format .'-'. '1';
        $this->ChallaniNumber = $nextChallaniNumber;
        }
        return view('frontend.banking_mudda.edit', compact('bankingmudda','nextChallaniNumber'));
    }

    protected function createPunarabedan($request) {
        Punarabedan::create([
            'mudda_name'               => $request->input('mudda_name'),
            'jaherwala_name'           => $request->input('jaherwala_name'),
            'pratiwadi_name'           => $request->input('pratiwadi_name'),
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
            'mudda_stithi' => 'required',
            'mudda_date' => 'required',
        ];
        $Messages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_stithi.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
        ];
        $bankingmudda = BankingMudda::findOrFail($id);
        $this->validate($request, $rules, $Messages);
        $bankingmudda->update([
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'mudda_number' => $request->input('mudda_number'),
            'mudda_name' => $request->input('mudda_name'),
            'jaherwala_name' => $request->input('jaherwala_name'),
            'pratiwadi_name' => $request->input('pratiwadi_name'),
            'pratiwadi_number' => $request->input('pratiwadi_number'),
            'mudda_stithi' => $request->input('mudda_stithi'),
            'mudda_bibran' => $request->input('mudda_bibran'),
            'pesi_karyala' => $request->input('pesi_karyala'),
            'mudda_date' => $request->input('mudda_date'),
            'mudda_myad' => $request->input('mudda_myad'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'faat_name' => $request->input('faat_name'),
            'mudda_pathayko_date' => $request->input('mudda_pathayko_date'),
            'challani_number' => $request->status ? $request->input('challani_number') : $bankingmudda->challani_number,
            'status' => $request->input('status'),
            'kaifiyat' => $request->input('kaifiyat')
        ]);
        $this->updateAviyogchallani($bankingmudda,$request);
        $this->updatePunarabedan($bankingmudda,$request);

        if ( isset($bankingmudda) && $bankingmudda->status == true ) {
            $challaniNumber = $request->input('challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }
        return redirect()->route('banking_mudda.index')
        ->with('success', 'बैकिङ्ग मुद्दा सफलतापूर्वक अपडेट भयो।');
    }

    protected function updateAviyogchallani($mudda, $request){
        $aviyogchallani = AviyogChallani::where('id', $mudda->id)->first();

        if ($aviyogchallani) {
            $aviyogchallani->update([
                'jaherwala_name'           => $request->input('jaherwala_name'),
                'pratiwadi_name'           => $request->input('pratiwadi_name'),
                'mudda_name'               => $request->input('mudda_name'),
                'mudda_number'             => $request->input('mudda_number'),
                'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
                'faat_name'                => $request->input('faat_name'),
                'anusandhan_garne_nikaye'  => $request->input('anusandhan_garne_nikaye'),
            ]);
        }
    }

    protected function updatePunarabedan($mudda, $request){
        $punarabedan = Punarabedan::where('id', $mudda->id)->first();

        if ($punarabedan) {
            $punarabedan->update([
            'mudda_name'               => $request->input('mudda_name'),
            'jaherwala_name'           => $request->input('jaherwala_name'),
            'pratiwadi_name'           => $request->input('pratiwadi_name'),
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
        $bankingmudda = BankingMudda::findOrFail($id);
        $bankingmudda->delete();
        return redirect()->route('banking_mudda.index')
        ->with('success', 'मुद्दा सफलतापूर्वक मेटियो।');
    }
}
