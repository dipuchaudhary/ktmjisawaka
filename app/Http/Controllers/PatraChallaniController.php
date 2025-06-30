<?php

namespace App\Http\Controllers;

use App\Models\PatraChallani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PatraChallaniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PatraChallani::select('id', 'karyalaya_name','challani_date','challani_number','mudda_number','challani_subject','bodartha','verified_by','kaifiyat','status')->get();
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
                        $edit = "";
                        $edit = '<a href="'.route('patra_challani.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
                        $btn .= $edit;
                        return $btn;
                   })

                    ->rawColumns(['status','action'])
                   ->make(true);
        }
       return view('frontend.challani.patrachallani.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $latest = PatraChallani::orderByDesc('id')->first();
        if ($latest && $latest->id) {
        $nextChallaniNumber = toNepaliNumber($latest->id+1);
        } else {
        $nextChallaniNumber = toNepaliNumber('1');
        }
        return view('frontend.challani.patrachallani.create',compact('nextChallaniNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'karyalaya_name' => 'required',
            'challani_date' => 'required',
            'challani_subject' => 'required',
        ];
        $Messages = [
           'karyalaya_name.required' => 'पत्र चलान भएको कार्यालय अनिवार्य छ।',
           'challani_date.required' => 'चलानी मिति अनिवार्य छ।',
           'challani_subject.required' => 'चलानी विषय अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $Messages);
        if (!empty($request->input('bodartha')) ) {
           $bodartha = implode(',', $request->input('bodartha'));
        } else {
            $bodartha= $request->input('bodartha');
        }
        // Store the data in the database
        PatraChallani::create([
            'karyalaya_name' => $request->input('karyalaya_name'),
            'challani_date' => $request->input('challani_date'),
            'challani_number' => $request->input('challani_number'),
            'mudda_number' => $request->input('mudda_number'),
            'challani_subject' => $request->input('challani_subject'),
            'bodartha' => $bodartha,
            'verified_by' => $request->input('verified_by'),
            'kaifiyat' => $request->input('kaifiyat'),
            'status' => true,
        ]);

        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफल भयो।');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $patrachallani = PatraChallani::findOrFail($id);
        return view('frontend.challani.patrachallani.edit', compact('patrachallani'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'karyalaya_name' => 'required',
            'challani_date' => 'required',
            'challani_subject' => 'required',
        ];
        $Messages = [
           'karyalaya_name.required' => 'पत्र चलान भएको कार्यालय अनिवार्य छ।',
           'challani_date.required' => 'चलानी मिति अनिवार्य छ।',
           'challani_subject.required' => 'चलानी विषय अनिवार्य छ।',
        ];

        $patraChallani = PatraChallani::findOrFail($id);
        $this->validate($request, $rules, $Messages);
        // Store the data in the database
        $patraChallani->update([
            'karyalaya_name' => $request->input('karyalaya_name'),
            'challani_date' => $request->input('challani_date'),
            'challani_number' => $request->input('challani_number'),
            'mudda_number' => $request->input('mudda_number'),
            'challani_subject' => $request->input('challani_subject'),
            'bodartha' => implode(',', $request->input('bodartha')),
            'verified_by' => $request->input('verified_by'),
            'kaifiyat' => $request->input('kaifiyat'),
        ]);

        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफलतापूर्वक अपडेट भयो।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
