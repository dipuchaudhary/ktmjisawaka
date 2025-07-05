<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AviyogChallani;
use App\Models\Challani;
use App\Models\ChallaniFormat;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class AviyogChallaniController extends Controller
{
    protected $format = '';
    protected $ChallaniNumber = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

         if(request()->ajax())
        {
            try {
                $query = AviyogChallani::select('id', 'challani_date','challani_number','mudda_number','mudda_name','jaherwala_name','pratiwadi_name','sarkariwakil_name','faat_name','anusandhan_garne_nikaye','status');

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
        if (!auth()->check()) return '';
        $buttons = '';

        if (auth()->user()->can('aviyog-edit')) {
            $buttons .= '<a href="'.route('aviyog_challani.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
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

        return $buttons;
    }

    public function edit($id){
        $aviyogchallani = AviyogChallani::findorfail($id);
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
        return view('frontend.challani.aviyog challani.edit',compact('aviyogchallani','nextChallaniNumber'));
    }

    public function update(Request $request, $id){
        $rules = [
            'anusandhan_garne_nikaye' => 'required',
            'challani_date' => 'required',
            'mudda_name' => 'required',
            'jaherwala_name' => 'required',
            'pratiwadi_name' => 'required',
            'mudda_name' => 'required',
            'upload_file' => 'nullable|file|mimes:pdf|max:10240',
        ];
        $customMessages = [
           'anusandhan_garne_nikaye.required' => 'अनुसन्धान गर्ने निकाय अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दाको किसिम अनिवार्य छ।',
           'jaherwala_name.required' => 'जाहेरवालाको नाम अनिवार्य छ।',
           'pratiwadi_name.required' => 'प्रतिवादीको नाम अनिवार्य छ।',
           'mudda_name.required' => 'मुद्दा किसिम अनिवार्य छ।',
           'upload_file' => 'pdf फाइल मात्र अपलोड गर्नुहोस्।',
           'challani_date' => 'चलानी मिति अनिवार्य छ।',
        ];

        $this->validate($request, $rules, $customMessages);
        $aviyogchallani = AviyogChallani::findOrFail($id);
        $fileurl = $request->input('existing_file');
        if ($request->hasFile('upload_file')) {

            if ($aviyogchallani->file && Storage::disk('public')->exists($aviyogchallani->file)) {
            Storage::disk('public')->delete($aviyogchallani->file);
            }

            $file = $request->file('upload_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = $originalName .'.'. $extension;
            $fileurl = $file->storeAs('aviyogfile', $filename, 'public');
        }

        $genderCounts = $request->input('gender', []);
        $male   = isset($genderCounts['male']) ? (int) $genderCounts['male'] : 0;
        $female = isset($genderCounts['female']) ? (int) $genderCounts['female'] : 0;
        $child  = isset($genderCounts['child']) ? (int) $genderCounts['child'] : 0;
        $other  = isset($genderCounts['other']) ? (int) $genderCounts['other'] : 0;
        $total  = $male + $female + $child + $other;

        $aviyogchallani->update([
            'challani_number'         => $request->input('challani_number'),
            'challani_date'           => $request->input('challani_date'),
            'jaherwala_name'          => $request->input('jaherwala_name'),
            'pratiwadi_name'          => $request->input('pratiwadi_name'),
            'mudda_name'              => $request->input('mudda_name'),
            'mudda_number'            => $request->input('mudda_number'),
            'gender'                  => json_encode($genderCounts),
            'gender_counts'           => toNepaliNumber($total),
            'anusandhan_garne_nikaye' => $request->input('anusandhan_garne_nikaye'),
            'sarkariwakil_name'       => $request->input('sarkariwakil_name'),
            'faat_name'               => $request->input('faat_name'),
            'kaifiyat'                => $request->input('kaifiyat'),
            'file'                    => $fileurl,
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
        $aviyogchallani = AviyogChallani::findOrFail($id);
        $aviyogchallani->delete();
        return redirect()->route('aviyog_challani.index')
        ->with('success', 'अभियोग चलानी सफलतापूर्वक मेटियो।');
    }
}
