<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waste_collection;
use App\Models\ResidueType;
use Illuminate\Support\Facades\DB;

class ResidueController extends Controller
{
    public function generalShow($date){
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, id_residue, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('year', 'month', 'day', 'id_residue')
        ->get();

        $data = [
            'status' => true,
            'residues' => $residues,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
        ];

        return response()->json($data);
    }

    public function showContinuation($date){
        $residues = Waste_collection::selectRaw('HOUR (created_at) as hour, MONTH(created_at) as month, created_at, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('month', 'created_at')
        ->get();

        $data = [
            'status' => true,
            'residues' => $residues,
        ];

        return response()->json($data);
    }
}
