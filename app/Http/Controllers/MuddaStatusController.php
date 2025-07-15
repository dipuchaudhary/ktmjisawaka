<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MuddaStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.MuddaStatus.index');
    }
    /**
     * Display search results
     *
     * @param  Request $request
     */
    public function search(Request $request)
    {
        $tablename = $request->input('table_name');
        $keyword = $request->input('search_keyword');

        $allowedTables = [
        'mudda_dartas',
        'banking_muddas',
        'patra_challanis',
        'aviyog_challanis',
        'punarabedans'
        ];

    if (!in_array($tablename, $allowedTables)) {
        return response()->json(['status' => 'error', 'message' => 'Invalid table selected.'], 400);
    }

    $columns = ['mudda_name', 'mudda_number', 'jaherwala_name', 'pratiwadi_name'];


    $query = DB::table($tablename)->where(function ($q) use ($keyword, $tablename, $columns) {
        $first = true;
        foreach ($columns as $col) {
            if (Schema::hasColumn($tablename, $col)) {
                if ($first) {
                    $q->where($col, 'like', "%$keyword%");
                    $first = false;
                } else {
                    $q->orWhere($col, 'like', "%$keyword%");
                }
            }
        }
    });

    $results = $query->get();
    $results = $results->map(function($item) use ($tablename) {
        $item->table_name = $tablename;
        return $item;
    });

    return response()->json(['status' => 'success', 'data' => $results]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:0,1',
            'table_name' => 'required|string|in:mudda_dartas,banking_muddas,patra_challanis,aviyog_challanis,punarabedans',
        ]);

        $table = $request->table_name;

        $updated = DB::table($table)
            ->where('id', $request->id)
            ->update(['status' => $request->status]);

        if ($updated) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
