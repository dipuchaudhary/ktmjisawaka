<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Challani;
use Illuminate\Http\Request;
use App\Models\PatraChallani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PatraChallaniController extends Controller
{
    protected $format = '';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax())
        {
            $query = PatraChallani::select('id', 'karyalaya_name','challani_date','challani_number','mudda_number','challani_subject','verified_by','challani_sakha','faat','user_name','status');
            $user = auth()->user();
            if ( $user ) {
                $query->where(function ($q) use ($user) {
                    $canShowPending = $user->hasPermissionTo('show-pending');
                    $canShowDone = $user->hasPermissionTo('show-done');

                    if ($canShowPending && $canShowDone) {
                        $q->whereIn('status', [0, 1]);
                    } elseif ($canShowPending) {
                        $q->where('status', 0);
                    } elseif ($canShowDone) {
                        $q->where('status', 1);
                    } else {
                        $q->whereRaw('0=1');
                    }
                });
            }
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
        $buttons = '<div style="display: inline-flex; align-items: center; gap: 8px;">';
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
        $buttons .='</div>';
        return $buttons;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('patrachallani-create')) {
             abort(403, 'You do not have permissions');
        }

        $latest = PatraChallani::orderByDesc('id')->first();
        $challani_format = ChallaniFormat::value('format_prefix') ?? '2082/083';

        $this->format = $challani_format;

        if ($latest && !empty($latest->challani_number)) {
            $nextId = $latest->id + 1;
        } else {
            $nextId = 1;
        }

        $ChallaniNumber = $this->format . '-' . $nextId;

        return view('frontend.challani.patrachallani.create', compact('ChallaniNumber'));

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
            'challani_sakha' => 'required',
        ];
         if ($request->challani_sakha === 'मुद्दा') {
             $challani_sakha = $request->input('challani_sakha') . '-'. $request->input('faat');
        } else {
            $challani_sakha = $request->input('challani_sakha');
        }
        $Messages = [
           'karyalaya_name.required' => 'पत्र चलान भएको कार्यालय अनिवार्य छ।',
           'challani_date.required' => 'चलानी मिति अनिवार्य छ।',
           'challani_subject.required' => 'चलानी विषय अनिवार्य छ।',
           'challani_sakha.required' => 'चलानी गर्ने शाखा अनिवार्य छ।',
           'faat.required' => 'फाँट अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $Messages);
        $bodartha = is_array($request->input('bodartha'))
                    ? implode(',', $request->input('bodartha'))
                    : $request->input('bodartha');

        $jaherwala_name = $request->input('jaherwala_name');

        $pratiwadi_name = $request->input('pratiwadi_name');
        // Store the data in the database
        PatraChallani::create([
            'karyalaya_name' => $request->input('karyalaya_name'),
            'challani_date' => $request->input('challani_date'),
            'challani_number' => $request->input('challani_number'),
            'mudda_number' => $request->input('mudda_number'),
            'challani_subject' => $request->input('challani_subject'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'bodartha' => $bodartha,
            'verified_by' => $request->input('verified_by'),
            'kaifiyat' => $request->input('kaifiyat'),
            'challani_sakha' => $challani_sakha,
            'faat' => $request->input('faat'),
            'user_name' => auth()->user()->name,
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
        if (!Gate::allows('patrachallani-edit')) {
             abort(403, 'You do not have permissions');
        }

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
            'challani_sakha' => 'required',
        ];
         if ($request->challani_sakha === 'मुद्दा') {
            $rules['faat'] = 'required';
            $challani_sakha = $request->input('challani_sakha') . '-'. $request->input('faat');
        } else {
            $challani_sakha = $request->input('challani_sakha');
        }
        $Messages = [
           'karyalaya_name.required' => 'पत्र चलान भएको कार्यालय अनिवार्य छ।',
           'challani_date.required' => 'चलानी मिति अनिवार्य छ।',
           'challani_subject.required' => 'चलानी विषय अनिवार्य छ।',
           'challani_sakha.required' => 'चलानी गर्ने शाखा अनिवार्य छ।',
           'faat.required' => 'फाँट अनिवार्य छ।',
        ];

        $patraChallani = PatraChallani::findOrFail($id);
        $this->validate($request, $rules, $Messages);
         if (!empty($request->input('bodartha')) ) {
           $bodartha = implode(',', $request->input('bodartha'));
        } else {
            $bodartha= $request->input('bodartha');
        }

        $jaherwala_name = $request->input('jaherwala_name');

        $pratiwadi_name = $request->input('pratiwadi_name');
        // Store the data in the database
        $patraChallani->update([
            'karyalaya_name' => $request->input('karyalaya_name'),
            'challani_date' => $request->input('challani_date'),
            'mudda_number' => $request->input('mudda_number'),
            'challani_subject' => $request->input('challani_subject'),
            'jaherwala_name' => $jaherwala_name,
            'pratiwadi_name' => $pratiwadi_name,
            'bodartha' => $bodartha,
            'verified_by' => $request->input('verified_by'),
            'kaifiyat' => $request->input('kaifiyat'),
            'challani_sakha' => $challani_sakha,
            'faat' => $request->input('faat'),
            'user_name' => auth()->user()->name,
        ]);

        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफलतापूर्वक अपडेट भयो।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Gate::allows('patrachallani-delete')) {
             abort(403, 'You do not have permissions');
        }
        $patrachallani = PatraChallani::findOrFail($id);
        $patrachallani->delete();
        return redirect()->route('patra_challani.index')
        ->with('success', 'चलानी पत्र सफलतापूर्वक मेटियो।');
    }
}
