<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AviyogChallani;

class AviyogChallaniController extends Controller
{
    public function index(){
        $data = AviyogChallani::select('id', 'challani_date','challani_number','mudda_number','mudda_name','jaherwala_name','pratiwadi_name','sarkariwakil_name','faat_name','anusandhan_garne_nikaye')->get();
         if(request()->ajax())
        {
            return Datatables::of($data)
                   ->addIndexColumn()
                   ->addColumn('action',function($data){
                        $btn = '';
                        $edit = "";
                        $edit = '<a href="'.route('patra_challani.edit',$data->id).'" class="edit p-2"><i class="fas fa-edit fa-lg"></i></a>';
                        $btn .= $edit;
                        return $btn;
                   })

                   ->rawColumns(['action'])
                   ->make(true);
        }
        return view('frontend.challani.aviyog challani.index',compact('data'));
    }
}
