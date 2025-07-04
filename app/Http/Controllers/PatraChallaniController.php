<?php

namespace App\Http\Controllers;

use App\Models\Challani;
use App\Models\ChallaniFormat;
use App\Models\PatraChallani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PatraChallaniController extends Controller
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
            $query = PatraChallani::select('id', 'karyalaya_name','challani_date','challani_number','mudda_number','challani_subject','bodartha','verified_by','kaifiyat','status');
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
       return view('frontend.challani.patrachallani.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        if (!auth()->check()) return '';
        $buttons = '';

        if (auth()->user()->can('patrachallani-edit')) {
            $buttons .= '<a href="'.route('patra_challani.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
        }

        if (auth()->user()->can('patrachallani-delete')) {
            $buttons .= '<form action="'.route('patra_challani.destroy', $data->id).'" method="POST" style="display:inline;">
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
         if (!empty($request->input('bodartha')) ) {
           $bodartha = implode(',', $request->input('bodartha'));
        } else {
            $bodartha= $request->input('bodartha');
        }
        // Store the data in the database
        $patraChallani->update([
            'karyalaya_name' => $request->input('karyalaya_name'),
            'challani_date' => $request->input('challani_date'),
            'challani_number' => $request->input('challani_number'),
            'mudda_number' => $request->input('mudda_number'),
            'challani_subject' => $request->input('challani_subject'),
            'bodartha' => $bodartha,
            'verified_by' => $request->input('verified_by'),
            'kaifiyat' => $request->input('kaifiyat'),
        ]);

        if ( isset($patraChallani) && $patraChallani->status == true ) {
            $challaniNumber = $request->input('challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }

        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफलतापूर्वक अपडेट भयो।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patrachallani = PatraChallani::findOrFail($id);
        $patrachallani->delete();
        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफलतापूर्वक मेटियो।');
    }
}
