<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
use Illuminate\Http\Request;
use App\Models\Waste_collection;
use App\Models\ResidueType;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class ResidueController extends Controller
{
    // Reportes 
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
        ->whereHas('collection_logs')
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
        ->whereHas('collection_logs')
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

    public function showClinic(){
        $records = Clinic::whereHas('collection_log')->get();

        $data = [
            'status' => true,
            'clinic' => $records,
        ];

        return response()->json($data);
    }

    public function showClinicSelected($date, $id){
        $residuesClinic = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, id_residue, SUM(weight) as total_weight')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('year', 'month', 'day', 'id_residue')
        ->get();

        $totalClinic = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('id_residue')
        ->get();

        $data = [
            'status' => true,
            'totalClinic' => $totalClinic,
            'clinicResidue' => $residuesClinic,
        ];

        return response()->json($data);
    }

    public function showClinicConstinuation($date, $id){
        $residuesClinic = Waste_collection::selectRaw('DAY (created_at) as day ,HOUR (created_at) as hour, MONTH(created_at) as month, created_at, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $date. '%')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->groupBy('month', 'created_at')
        ->get();

        $totalClinic = Waste_collection::selectRaw('MONTH(created_at) as month, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $date. '%')
        ->groupBy('month')
        ->get();

        $collectionLog = CollectionLog::selectRaw('DAY (created_at) as day, collection_date, created_at')
        ->whereHas('waste_collection')
        ->where('clinic_id', $id)
        ->where('created_at', 'LIKE',  $date. '%')
        ->where('stored_stated', 'RECOLECTADO')
        ->groupBy('day', 'collection_date', 'created_at')
        ->get();

        $collectionLog->transform(function ($collectionLog) {
            $collectionLog->date = $collectionLog->created_at->format('Y-m-d');
            unset($collectionLog->created_at);
            return $collectionLog;
        }); 

        $data = [
            'status' => true,
            'clinicResidue' => $residuesClinic,
            'totalClinic' => $totalClinic,  
            'collectionLog' => $collectionLog, 
        ];

        return response()->json($data);
    }
    // fin reportes

    // Recolecciones almacenadas
    public function showCollectorResidue($typeTable){
        if ($typeTable == 'true') {
            $records = CollectionLog::whereHas('user', function($query){
                $query->role('Recolector');
                $query->whereHas('clinic_user', function($query){
                    $query->whereHas('clinic');
                });
            })
            ->where('stored_stated', 'ALMACENADO')
            ->whereHas('clinic')
            ->with('user', 'clinic')
            ->get();
    
            $records->transform(function ($record) {
                $record->date = $record->created_at->format('Y-m-d');
                $record->dateTemp = $record->created_at->format('Y-m-d');
                unset($record->created_at);
                return $record;
            });            
        }else{
            $records = CollectionLog::whereHas('user', function($query){
                $query->role('Recolector');
                $query->whereHas('clinic_user', function($query){
                    $query->whereHas('clinic');
                });
            })
            ->where('stored_stated', 'RECOLECTADO')
            ->whereHas('clinic')
            ->with('user', 'clinic')
            ->get();
        
            $records->transform(function ($record) {
                $record->date = $record->created_at->format('Y-m-d');
                $record->dateTemp = $record->created_at->format('Ymd');
                unset($record->created_at);
                return $record;
            });
        }

        $data = [
            'type' => $typeTable,
            'status' => true,
            'records' => $records,
        ];

        return response()->json($data);
    }

    public function registerCollector($id ,$date){
        $update = CollectionLog::find($id);
        $update->collection_date = $date;
        $update->stored_stated = 'RECOLECTADO';
        $update->save();


        $data = [
            'status' => true,
        ];

        return response()->json($data);
    }
}
