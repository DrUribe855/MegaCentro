<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionLog;
use App\Models\ResidueType;
use Illuminate\Support\Facades\DB;

class ResidueController extends Controller
{
    public function generalShow($date){
        $residues = CollectionLog::select(
            'created_at',
            'residue_id',
            DB::raw('DAY(created_at) as day_of_month'),
            DB::raw('SUM(weight) as total_weight')
        )
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('created_at', 'residue_id')
        ->get();
        
        // $residues = ResidueType::with('residues.collection_log')->get();

        $data = [
            'status' => true,
            'residues' => $residues,
            'date' => $date,
        ];

        return response()->json($data);
    }
}
