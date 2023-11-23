<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionLog;
use App\Models\ResidueType;
use Illuminate\Support\Facades\DB;

class ResidueController extends Controller
{
    public function generalShow($date){
        $residues = CollectionLog::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, residue_id, SUM(weight) as total_weight')
        ->groupBy('year', 'month', 'day', 'residue_id')
        ->get();

        $data = [
            'status' => true,
            'residues' => $residues,
            'date' => $date,
        ];

        return response()->json($data);
    }
}
