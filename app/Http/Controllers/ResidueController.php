<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
use Illuminate\Http\Request;
use App\Models\Waste_collection;
use App\Models\ResidueType;
use DateTime;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class ResidueController extends Controller
{
    // Reportes
    public function generalShow($date){
        $dateTime = DateTime::createFromFormat('Y-n', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, id_residue, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('year', 'month', 'day', 'id_residue')
        ->get();

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('id_residue')
        ->get();

        $dateParts = explode("-", $formattedDate);
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
        $dateTime = DateTime::createFromFormat('Y-n', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residues = Waste_collection::selectRaw('DAY(created_at) as day, MONTH(created_at) as month, YEAR(created_at) as year, SUM(weight) as total_weight, garbage_bags, hour, yesOrNot, staffing')
        ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") LIKE ?', [$formattedDate . '%'])
        ->whereHas('collection_logs')
        ->groupBy('day', 'month', 'year', 'hour', 'yesOrNot', 'garbage_bags', 'staffing')
        ->get();

        $total = Waste_collection::selectRaw('MONTH(created_at) as month, SUM(weight) as total_weight')
        ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") LIKE ?', [$formattedDate . '%'])
        ->groupBy('month')
        ->get();

        
        $dateParts = explode("-", $formattedDate);
        $year = $dateParts[0];
        $month = $dateParts[1];
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $totalGarbageBags = 0;

        $consultaGarbageBags = Waste_collection::selectRaw('DAY(created_at) as day, SUM(DISTINCT garbage_bags) as totalGarbageBags')
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") LIKE ?', [$formattedDate . '%'])
            ->groupBy('day')
            ->get();

        foreach ($consultaGarbageBags as $consulta) {
            $totalGarbageBags += $consulta->totalGarbageBags;
        }

        $data = [
            'status' => true,
            'residues' => $residues,
            'date' => $numberDay,
            'total' => $total,
            'totalGarbageBags' => $totalGarbageBags,
            'consulta' => $consultaGarbageBags,
        ];

        return response()->json($data);
    }

    public function showUnified($date){
        $dateTime = DateTime::createFromFormat('Y', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, id_residue, SUM(weight) as total_weight')
        ->whereHas('collection_logs')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('year', 'month', 'id_residue')
        ->get();

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('id_residue')
        ->get();

        $bigTotal = Waste_collection::selectRaw('SUM(weight) as weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->get(); 

        $data = [
            'status' => true,
            'total' => $total,
            'residues' => $residues,
            'bigTotal' => $bigTotal,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
        ];

        return response()->json($data);
    }

    public function showUnifiedContinuation($date){
        $dateTime = DateTime::createFromFormat('Y', $date);
        $formattedDate = $dateTime->format('Y');
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(weight) as total_weight, SUM(DISTINCT garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('year', 'month')
        ->get();

        $total = Waste_collection::selectRaw('YEAR(created_at) as year, SUM(weight) as total_weight, SUM(DISTINCT garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
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
        $dateTime = DateTime::createFromFormat('Y-n', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residuesClinic = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, id_residue, SUM(weight) as total_weight')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('year', 'month', 'day', 'id_residue')
        ->get();

        $totalClinic = Waste_collection::selectRaw('id_residue, SUM(weight) as weight')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $formattedDate. '%')
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
        $dateTime = DateTime::createFromFormat('Y-n', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residuesClinic = Waste_collection::selectRaw('DAY (created_at) as day ,HOUR (created_at) as hour, MONTH(created_at) as month, created_at, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->groupBy('month', 'created_at')
        ->get();

        $totalClinic = Waste_collection::selectRaw('MONTH(created_at) as month, SUM(weight) as total_weight, SUM(garbage_bags) as garbage_bags')
        ->whereHas('collection_logs', function($query) use($id){
            $query->where('clinic_id', $id);
        })
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('month')
        ->get();

        $collectionLog = CollectionLog::selectRaw('DAY (created_at) as day, collection_date, created_at')
        ->whereHas('waste_collection')
        ->where('clinic_id', $id)
        ->where('created_at', 'LIKE',  $formattedDate. '%')
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
            $records = CollectionLog::where('stored_stated', 'ALMACENADO')
            ->whereNull('collection_date')
            ->with('clinic')
            ->get();

            $records->transform(function ($record) {
                $record->date = $record->created_at->format('Y-m-d');
                $record->dateTemp = $record->created_at->format('Y-m-d');
                unset($record->created_at);
                return $record;
            });
        }else{
            $records = CollectionLog::where('stored_stated', 'RECOLECTADO')
            ->whereNotNull('collection_date')
            ->with('clinic')
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

    public function registerDateCollector(Request $request, $day){
        Waste_collection::whereDay('created_at', $day)
        ->update([
            'garbage_bags' => $request->input('garbage_bags'),
            'yesOrNot' => $request->input('yesOrNot'),
            'hour' => $request->input('hour'),
            'staffing' => $request->input('staffing'),
        ]);
        
        $idCollectionLog = Waste_collection::whereHas('collection_logs')
        ->whereNotNull('hour')
        ->whereDay('created_at', $day)
        ->pluck('collection_logs_id');

        for ($i=0; $i < count($idCollectionLog) ; $i++) { 
            $update = CollectionLog::find($idCollectionLog[$i]);
            $update->collection_date = $request->input('date');
            $update->stored_stated = 'RECOLECTADO';
            $update->save();
        };

        $data = [
            'status' => true,
            'data' => $idCollectionLog,
            'yesOrNot' => $request->input('yesOrNot'),
            'hour' => $request->input('hour'),
            'date' => $request->input('date'),
            'day' => $day,
        ];   
        

        return response()->json($request);
    }   
}
