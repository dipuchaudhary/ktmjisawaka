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
        $data = BankingMudda::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','mudda_stithi','mudda_date','sarkariwakil_name','challani_number','status')->get();
         if(request()->ajax())
        {
            return Datatables::of($data)
                   ->addIndexColumn()
                   ->addColumn('status', function ($data) {
                    if ($data->status == 1 || $data->status === true) {
                            return '<span class="badge rounded-pill text-white bg-success">Done</span>';
                    } else {
                            return '<span class="badge rounded-pill text-white bg-danger">Pending</span>';
                    }
					})
                   ->addColumn('action',function($data){
                        $btn = '';
                        $show = '';
                        $edit = "";
                        $edit = '<a href="'.route('banking_mudda.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
                        $btn .= $edit;
                        $show = '<form action="'.route('banking_mudda.destroy', $data->id).'" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" style="border:none; background:none; color:red; padding:0;">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>';
                        $btn .= $show;
                        return $btn;
                   })

                   ->rawColumns(['status','action'])
                   ->make(true);
        }
       return view('frontend.banking_mudda.index');
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

        $this->createAviyogChallani($request);
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

    protected function createAviyogChallani($request) {
        AviyogChallani::create([
            'challani_date'            => null,
            'challani_number'          => null,
            'jaherwala_name'           => $request->input('jaherwala_name'),
            'pratiwadi_name'           => $request->input('pratiwadi_name'),
            'mudda_name'               => $request->input('mudda_name'),
            'gender'                   => null,
            'mudda_number'             => $request->input('mudda_number'),
            'sarkariwakil_name'        => $request->input('sarkariwakil_name'),
            'faat_name'                => $request->input('faat_name'),
            'anusandhan_garne_nikaye'  => $request->input('anusandhan_garne_nikaye'),
            'kaifiyat'                 => '',
        ]);
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
