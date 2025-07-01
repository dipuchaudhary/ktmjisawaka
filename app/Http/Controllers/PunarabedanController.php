<?php

namespace App\Http\Controllers;

use App\Models\Punarabedan;
use Illuminate\Http\Request;
use App\Models\Challani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PunarabedanController extends Controller
{
    protected $format = '';
    protected $ChallaniNumber = '';
    protected $rules = [
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'faisala_date' => 'required',
            'faisala_pramanikaran_date' => 'required',
            'suchana_date' => 'required',
            'faisala_garne_nikaye' => 'required',
            'punarabedan' => 'required',
            'punarabedan_date' => 'required',
            'punarabedan_challani_number' => 'required',
        ];
    protected $customMessages = [
           'mudda_name.required' => 'मुद्दाको नाम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'faisala_date.required' => 'फैसाला मिति अनिवार्य छ।',
           'faisala_pramanikaran_date.required' => 'फैसाला प्रमाणीकरण मिति अनिवार्य छ।',
           'suchana_date.required' => 'सूचना प्राप्त मिति अनिवार्य छ।',
           'faisala_garne_nikaye.required' => 'फैसाला गर्ने निकाय अनिवार्य छ।',
           'punarabedan.required' => 'पुवे/दो.पा अनिवार्य छ।',
           'punarabedan_date.required' => 'चलानी नं. अनिवार्य छ।',
           'punarabedan_challani_number.required' => 'चलानी मिति अनिवार्य छ।',
        ];

    public function index() {
        $data = Punarabedan::select('id', 'mudda_number', 'jaherwala_name','pratiwadi_name','mudda_name','faisala_date','faisala_pramanikaran_date','punarabedan','punarabedan_date','punarabedan_challani_number','status')->get();
        // dd($data->status);
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
                ->addColumn('action', function ($data) {
                    $editBtn = '<a href="' . route('punarabedan.edit', $data->id) . '" class="edit p-2">
                                    <i class="fas fa-edit fa-lg"></i>
                                </a>';
                    return $editBtn;
                })
                ->rawColumns(['status','action'])
                ->make(true);

        }
        return view('frontend.punarabedan.index');
    }

    public function edit($id){
        $punarabedan = Punarabedan::findOrFail($id);
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
        return view('frontend.punarabedan.edit', compact('punarabedan','nextChallaniNumber'));
    }

    public function update(Request $request, $id){
        $punarabedan = Punarabedan::findOrFail($id);

        $this->validate($request, $this->rules, $this->customMessages);
        // dd($punarabedan->toArray());
        $punarabedan->update([
            'mudda_number' => $request->input('mudda_number'),
            'jaherwala_name' => $request->input('jaherwala_name'),
            'pratiwadi_name' => $request->input('pratiwadi_name'),
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
            'punarabedan_date' => $request->input('punarabedan_date'),
            'punarabedan_challani_number' => $request->input('punarabedan_challani_number'),
            'nirnaye' => $request->input('nirnaye'),
            'nirnaye_date' => $request->input('nirnaye_date'),
            'sarkariwakil_name' => $request->input('sarkariwakil_name'),
            'kaifiyat' => $request->input('kaifiyat'),
            'status' => true,
        ]);

        if ( isset($punarabedan) && $punarabedan->status == true ) {
            $challaniNumber = $request->input('punarabedan_challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }

        return redirect()->route('punarabedan.index')
        ->with('success', 'पुनरावेदन सफलतापूर्वक अपडेट भयो।');
    }
}
