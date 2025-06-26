<?php

namespace App\Http\Controllers;
use App\DataTables\MuddaDartaDataTable;
use App\Models\MuddaDarta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MuddaDartaController extends Controller
{
    public function index(Request $request)
    {
        $data = MuddaDarta::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','mudda_stithi','mudda_date','sarkariwakil_name','faat_name')->get();
         if(request()->ajax())
        {
            return Datatables::of($data)
                   ->addIndexColumn()
                   ->addColumn('action',function($data){
                        $btn = '';
                        $show = '';
                        $edit = "";
                        $edit = '<a href="'.route('mudda_darta.edit', $data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
                        $btn .= $edit;
                        $show = '<form action="' . route('mudda_darta.destroy', $data->id) . '" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" style="border:none; background:none; color:red; padding:0;">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>';
                        $btn .= $show;
                        return $btn;
                   })

                   ->rawColumns(['action'])
                   ->make(true);
        }
        return view('frontend.mudda_darta.index');

    }

    public function getMudda()
    {
        $data = MuddaDarta::select('id', 'anusandhan_garne_nikaye', 'mudda_number', 'mudda_name', 'jaherwala_name','pratiwadi_name','mudda_stithi','mudda_date','sarkariwakil_name','faat_name')->get();
        return response()->json(['data' => $data]);
    }

    public function create(Request $request)
    {
        return view('frontend.mudda_darta.create');

    }

    public function store(Request $request)
    {
         $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'mudda_stithi' => 'required',
            'mudda_date' => 'required',
            'mudda_pathayko_date' => 'required',
        ];
        $customMessages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दाको किसिम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_stithi.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
           'mudda_pathayko_date.required' => 'मुद्दा पठाएको मिति अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $customMessages);
        // Store the data in the database
        MuddaDarta::create([
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
            'kaifiyat' => $request->input('kaifiyat'),
        ]);
        return redirect()->route('mudda_darta.index')
        ->with('success', 'मुद्दा दर्ता सफल भयो।');
    }
    public function edit($id)
    {
        $mudda = MuddaDarta::findOrFail($id);
        return view('frontend.mudda_darta.edit', compact('mudda'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'mudda_stithi' => 'required',
            'mudda_date' => 'required',
            'mudda_pathayko_date' => 'required',
        ];
        $customMessages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दाको किसिम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_stithi.required' => 'मुद्दा स्थिति अनिवार्य छ।',
           'mudda_date.required' => 'मुद्दा दर्ता मिति अनिवार्य छ।',
           'mudda_pathayko_date.required' => 'मुद्दा पठाएको मिति अनिवार्य छ।',
        ];
        $mudda = MuddaDarta::findOrFail($id);
        $this->validate($request, $rules, $customMessages);
        $mudda->update([
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
            'kaifiyat' => $request->input('kaifiyat'),
        ]);
        return redirect()->route('mudda_darta.index')
        ->with('success', 'मुद्दा सफलतापूर्वक अपडेट भयो।');
    }
    public function destroy($id)
    {
        $mudda = MuddaDarta::findOrFail($id);
        $mudda->delete();
        return redirect()->route('mudda_darta.index')
        ->with('success', 'मुद्दा सफलतापूर्वक मेटियो।');
    }
}
