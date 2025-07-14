<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Challani;
use Illuminate\Http\Request;
use App\Models\AviyogChallani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AviyogChallaniController extends Controller
{
    protected string $format;

    public function __construct()
    {
        $this->format = ChallaniFormat::value('format_prefix') ?? '2082/083';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

         if(request()->ajax())
        {
            try {
                $query = AviyogChallani::select('id', 'challani_date','challani_number','mudda_number','mudda_name','jaherwala_name','pratiwadi_name','sarkariwakil_name','faat_name','anusandhan_garne_nikaye','user_name','status','upload_date','pesh_karyala');

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

                    ->rawColumns(['pratiwadi_name','status','action'])
                    ->make(true);
            } catch (\Exception $e) {
            \Log::error('DataTables error: ' . $e->getMessage());
        }
        }
        return view('frontend.challani.aviyog challani.index');
    }

    /**
     * Get action buttons for DataTable
     */
    protected function getActionButtons($data)
    {
        $buttons = '';
        // $buttons = '<div style="display: inline-flex; align-items: center; gap: 8px;">';
        // if (isset($data->file) && $data->file) {
        //     $buttons .= '<a href="' . asset('storage/' . $data->file) . '" target="_blank" class="edit p-1"><i class="fa fa-eye fa-lg"></i></a>';
        // }
        if (!auth()->check()) return $buttons;

        if (auth()->user()->can('aviyog-edit')) {
            $buttons .= '<a href="'.route('aviyog_challani.edit',$data->id).'" class="edit p-1"><i class="fas fa-edit fa-lg"></i></a>';
        }

        if (auth()->user()->can('aviyog-delete')) {
            $buttons .= '<form action="'.route('aviyog_challani.destroy', $data->id).'" method="POST" style="display:inline;">
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

    public function edit($id){

        if (!Gate::allows('aviyog-edit')) {
             abort(403, 'You do not have permissions');
        }
        $aviyogchallani = AviyogChallani::findOrFail($id);
        $latest = Challani::orderByDesc('id')->first();

        $nextId = $latest ? $latest->id + 1 : 1;
        $nextChallaniNumber = $this->format . '-' . $nextId;

        return view('frontend.challani.aviyog challani.edit',compact('aviyogchallani','nextChallaniNumber'));
    }

    public function update(Request $request, $id){
        $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'challani_date' => 'required',
            'mudda_name' => 'required',
            'jaherwala_name' => 'required|array',
            'pratiwadi_name' => 'required|array',
            'mudda_name' => 'required',
            'pesh_karyala' => 'required',
        ];
        $customMessages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दाको किसिम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दा किसिम अनिवार्य छ।',
           'challani_date' => 'चलानी मिति अनिवार्य छ।',
           'pesh_karyala' => 'पेश भएको कार्यालय अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $customMessages);
        $aviyogchallani = AviyogChallani::findOrFail($id);

        $genderCounts = $request->input('gender', []);
        $male   = isset($genderCounts['male']) ? (int) $genderCounts['male'] : 0;
        $female = isset($genderCounts['female']) ? (int) $genderCounts['female'] : 0;
        $child  = isset($genderCounts['child']) ? (int) $genderCounts['child'] : 0;
        $other  = isset($genderCounts['other']) ? (int) $genderCounts['other'] : 0;
        $total  = $male + $female + $child + $other;

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

        $aviyogchallani->update([
            'challani_number'         => $request->input('challani_number'),
            'challani_date'           => $request->input('challani_date'),
            'jaherwala_name'          => $jaherwala_name,
            'pratiwadi_name'          => $pratiwadi_name,
            'mudda_name'              => $request->input('mudda_name'),
            'mudda_number'            => $request->input('mudda_number'),
            'gender'                  => json_encode($genderCounts),
            'gender_counts'           => toNepaliNumber($total),
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'pesh_karyala'            => $request->input('pesh_karyala'),
            'sarkariwakil_name'       => $request->input('sarkariwakil_name'),
            'faat_name'               => $request->input('faat_name'),
            'kaifiyat'                => $request->input('kaifiyat'),
            'upload_date'             => $request->input('upload_date'),
            'user_name'               => auth()->user()->name,
            'status'                  => true,
        ]);

        if ( isset($aviyogchallani) && $aviyogchallani->status == true ) {
            $challaniNumber = $request->input('challani_number');
            $exists = Challani::where('challani_number', $challaniNumber)->exists();
            if (!$exists) {
                Challani::create(['challani_number' => $challaniNumber]);
            }
        }


        return redirect()->route('aviyog_challani.index')
        ->with('success', 'अभियोग चलानी सफलतापूर्वक अपडेट भयो।');
    }

    public function destroy($id){
        if (!Gate::allows('aviyog-delete')) {
             abort(403, 'You do not have permissions');
        }
        $aviyogchallani = AviyogChallani::findOrFail($id);
        $aviyogchallani->delete();
        return redirect()->route('aviyog_challani.index')
        ->with('success', 'अभियोग चलानी सफलतापूर्वक मेटियो।');
    }
}
