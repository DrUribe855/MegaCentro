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

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('id_residue')
        ->get();

        $dateParts = explode("-", $date);
        $year = $dateParts[0];
        $month = $dateParts[1];
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $data = [
            'status' => true,
            'total' => $total, 
            'residues' => $residues,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
            'date' => $numberDay,
        ];

        return response()->json($data);
    }

    public function showContinuation($date){
        $residues = Waste_collection::selectRaw('DAY (created_at) as day ,HOUR (created_at) as hour, MONTH(created_at) as month, created_at, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('month', 'created_at')
        ->get();

        $total = Waste_collection::selectRaw('MONTH(created_at) as month, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('month')
        ->get();

        $dateParts = explode("-", $date);
        $year = $dateParts[0];
        $month = $dateParts[1];
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $data = [
            'status' => true,
            'residues' => $residues,
            'date' => $numberDay,
            'total' => $total,   
        ];

        return response()->json($data);
    }

    public function showUnified($date){
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, id_residue, SUM(weight) as total_weight')
        ->whereRaw('YEAR(created_at) LIKE ?', [$date . '%'])
        ->groupBy('year', 'month', 'id_residue')
        ->get();

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->whereRaw('YEAR(created_at) LIKE ?', [$date . '%'])
        ->groupBy('id_residue')
        ->get();

        $data = [
            'status' => true,
            'total' => $total, 
            'residues' => $residues,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
        ];

        return response()->json($data);
    }

    public function showUnifiedContinuation($date){
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->whereRaw('YEAR(created_at) LIKE ?', [$date . '%'])
        ->groupBy('year', 'month')
        ->get();

        $total = Waste_collection::selectRaw('YEAR(created_at) as year, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->whereRaw('YEAR(created_at) LIKE ?', [$date . '%'])
        ->groupBy('year')
        ->get();

        $data = [
            'status' => true,
            'residues' => $residues,
            'total' => $total,   
        ];

        return response()->json($data); 
    }
}
