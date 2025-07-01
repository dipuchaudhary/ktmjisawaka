<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChallaniFormat;
use App\Models\Challani;

class ChallaniController extends Controller
{
    public function index() {
        $format = ChallaniFormat::first();
        if($format) {
            return view('backend.challaniForm',compact('format'));
        } else {
             return view('backend.challaniForm');
        }

    }

    public function storeOrUpdate(Request $request){

        $validated = $request->validate([
        'format_prefix' => 'required',
        ]);

        try {
            $existingFormat = ChallaniFormat::first();
            if ($existingFormat) {
            $existingFormat->update([
                'format_prefix' => $validated['format_prefix'],
            ]);
        } else {
            ChallaniFormat::create([
                'format_prefix' => $validated['format_prefix'],
            ]);
        }
        $message = 'Challani format saved successfully';
        return redirect()->route('challani.index')
            ->with('success', $message);

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Error saving format: ' . $e->getMessage());
    }

        return redirect()->route('challani.index')
        ->with('success', 'अपडेट भयो।');

    }
}
