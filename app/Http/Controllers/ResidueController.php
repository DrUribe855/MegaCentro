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
        ->where(function($query){
            $query->whereNot('id_residue', 8)
            ->whereNot('id_residue', 9)
            ->whereNot('id_residue', 10)
            ->whereNot('id_residue', 11)
            ->whereNot('id_residue', 12)
            ->whereNot('id_residue', 13)
            ->whereNot('id_residue', 15)
            ->whereNot('id_residue', 16);
        })
        ->groupBy('year', 'month', 'day', 'id_residue')
        ->get();
        // Suma de los metales pesados
        $heavyMetals = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 8)
            ->orWhere('id_residue', 9)
            ->orWhere('id_residue', 10)
            ->orWhere('id_residue', 11)
            ->orWhere('id_residue', 15)
            ->orWhere('id_residue', 16);
        })
        ->groupBy('year', 'month', 'day')
        ->get();

        // Suma de los reactivos
        $reactive = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 12)
            ->orWhere('id_residue', 13);
        })
        ->groupBy('year', 'month', 'day')
        ->get();

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->whereNot('id_residue', 8)
            ->whereNot('id_residue', 9)
            ->whereNot('id_residue', 10)
            ->whereNot('id_residue', 11)
            ->whereNot('id_residue', 12)
            ->whereNot('id_residue', 13)
            ->whereNot('id_residue', 15)
            ->whereNot('id_residue', 16);
        })
        ->groupBy('id_residue')
        ->get();

        // Suma total de los metales pesados
        $totalHeavyMetals = Waste_collection::selectRaw('SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 8)
            ->orWhere('id_residue', 9)
            ->orWhere('id_residue', 10)
            ->orWhere('id_residue', 11)
            ->orWhere('id_residue', 15)
            ->orWhere('id_residue', 16);
        })
        ->get();

        // Suma total de los reactivos
        $totalReactive = Waste_collection::selectRaw('SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 12)
            ->orWhere('id_residue', 13);
        })
        ->get();

        $dateParts = explode("-", $formattedDate);
        $year = $dateParts[0];
        $month = $dateParts[1];
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $residuesList = [];

        foreach ($residues as $residue) {
            $day = $residue['day'];
            $id_residue = $residue['id_residue'];
            $residuesList[$day][$id_residue] = $residue;
        }

        $object = array(
            'total_weight' => 0
        );

        $pos = 0;

        foreach ($residuesList as $day => $data) {
            $residuesTemps = array_fill(0, 15, 0);

            if (!empty($data[1])) {
                $residuesTemps[1] = $data[1];
            }
            if (!empty($data[2])) {
                $residuesTemps[3] = $data[2];
            }
            if (!empty($data[3])) {
                $residuesTemps[4] = $data[3];
            }
            if (!empty($data[4])) {
                $residuesTemps[5] = $data[4];
            }
            if (!empty($data[5])) {
                $residuesTemps[6] = $data[5];
            }
            if (!empty($data[6])) {
                $residuesTemps[7] = $data[6];
            }
            if (!empty($data[7])) {
                $residuesTemps[8] = $data[7];
            }
            if (!empty($data[14])) {
                $residuesTemps[13] = $data[14];
            }
            if (!empty($data[17])) {
                $residuesTemps[14] = $data[17];
            }
            if (!empty($data[18])) {
                $residuesTemps[15] = $data[18];
            }
            
            if (count($heavyMetals) != 0 && count($heavyMetals) > $pos) {
                $residuesTemps[10] = $heavyMetals[$pos];
            }

            foreach ($heavyMetals as $heavyMet) {
                if ($heavyMet['day'] == $day) {
                    $residuesTemps[10] = $heavyMet;
                    break;
                }
            }

            foreach ($reactive as $react) {
                if ($react['day'] == $day) {
                    $residuesTemps[11] = $react;
                    break;
                }
            }

            $residuesList[$day] = $residuesTemps;
        }

        $totalTemp = array_fill(0, 15, 0);
        
        foreach ($total as $data) {
            if ($data['id_residue'] == 1) {
                $totalTemp[1] = $data;
            } else if ($data['id_residue'] == 2) {
                $totalTemp[3] = $data;
            } else if ($data['id_residue'] == 3) {
                $totalTemp[4] = $data;
            } else if ($data['id_residue'] == 4) {
                $totalTemp[5] = $data;
            } else if ($data['id_residue'] == 5) {
                $totalTemp[6] = $data;
            } else if ($data['id_residue'] == 6) {
                $totalTemp[7] = $data;
            } else if ($data['id_residue'] == 7){
                $totalTemp[8] = $data;
            } else if ($data['id_residue'] == 14) {
                $totalTemp[13] = $data;
            } else if ($data['id_residue'] == 17) {
                $totalTemp[14] = $data;
            } else if ($data['id_residue'] == 18) {
                $totalTemp[15] = $data;
            }
        }

        $totalTemp[10] = $totalHeavyMetals[0];
        $totalTemp[11] = $totalReactive[0];

        $residues = $residuesList;
        $total = $totalTemp;

        $data = [
            'status' => true,
            'total' => $total,
            'residues' => $residues,
            'heavyMetals' => $heavyMetals,
            'reactive' => $reactive,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
            'date' => $numberDay,
        ];

        return response()->json($data);
    }

    public function showContinuation($date){
        $dateTime = DateTime::createFromFormat('Y-n', $date);
        $formattedDate = $dateTime->format('Y-m');
        $residues = Waste_collection::selectRaw('DAY(created_at) as day, MONTH(created_at) as month, YEAR(created_at) as year, SUM(weight) as total_weight, garbage_bags, hour, yesOrNot, staffing, treatmentType, collection_date')
        ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") LIKE ?', [$formattedDate . '%'])
        ->whereHas('collection_logs')
        ->groupBy('day', 'month', 'year', 'hour', 'yesOrNot', 'garbage_bags', 'staffing', 'treatmentType', 'collection_date')
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
        $formattedDate = $dateTime->format('Y');
        $residues = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, id_residue, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->whereNot('id_residue', 8)
            ->whereNot('id_residue', 9)
            ->whereNot('id_residue', 10)
            ->whereNot('id_residue', 11)
            ->whereNot('id_residue', 12)
            ->whereNot('id_residue', 13)
            ->whereNot('id_residue', 15)
            ->whereNot('id_residue', 16);
        })
        ->groupBy('year', 'month', 'id_residue')
        ->get();

        // Suma de los metales pesados
        $heavyMetals = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 8)
            ->orWhere('id_residue', 9)
            ->orWhere('id_residue', 10)
            ->orWhere('id_residue', 11)
            ->orWhere('id_residue', 15)
            ->orWhere('id_residue', 16);
        })
        ->groupBy('year', 'month')
        ->get();

        // Suma de los reactivos
        $reactive = Waste_collection::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 12)
            ->orWhere('id_residue', 13);
        })
        ->groupBy('year', 'month')
        ->get();

        $total = Waste_collection::selectRaw('id_residue, SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->groupBy('id_residue')
        ->get();

        // Suma total de los metales pesados
        $totalHeavyMetals = Waste_collection::selectRaw('SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 8)
            ->orWhere('id_residue', 9)
            ->orWhere('id_residue', 10)
            ->orWhere('id_residue', 11)
            ->orWhere('id_residue', 15)
            ->orWhere('id_residue', 16);
        })
        ->get();

        // Suma total de los reactivos
        $totalReactive = Waste_collection::selectRaw('SUM(weight) as total_weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->where(function($query){
            $query->where('id_residue', 12)
            ->orWhere('id_residue', 13);
        })
        ->get();

        $bigTotal = Waste_collection::selectRaw('SUM(weight) as weight')
        ->where('created_at', 'LIKE',  $formattedDate. '%')
        ->get(); 

        $residuesList = [];

        foreach ($residues as $residue) {
            $month = $residue['month'];
            $id_residue = $residue['id_residue'];
            $residuesList[$month][$id_residue] = $residue;
        }

        $object = array(
            'total_weight' => 0
        );

        $pos = 0;

        foreach ($residuesList as $month => $data) {
            $residuesTemps = array_fill(0, 15, 0);

            if (!empty($data[1])) {
                $residuesTemps[1] = $data[1];
            }
            if (!empty($data[2])) {
                $residuesTemps[3] = $data[2];
            }
            if (!empty($data[3])) {
                $residuesTemps[4] = $data[3];
            }
            if (!empty($data[4])) {
                $residuesTemps[5] = $data[4];
            }
            if (!empty($data[5])) {
                $residuesTemps[6] = $data[5];
            }
            if (!empty($data[6])) {
                $residuesTemps[7] = $data[6];
            }
            if (!empty($data[7])) {
                $residuesTemps[8] = $data[7];
            }
            if (!empty($data[14])) {
                $residuesTemps[13] = $data[14];
            }
            if (!empty($data[17])) {
                $residuesTemps[14] = $data[17];
            }
            if (!empty($data[18])) {
                $residuesTemps[15] = $data[18];
            }
            
            if (count($heavyMetals) != 0 && count($heavyMetals) > $pos) {
                $residuesTemps[10] = $heavyMetals[$pos];
            }

            foreach ($heavyMetals as $heavyMet) {
                if ($heavyMet['month'] == $month) {
                    $residuesTemps[10] = $heavyMet;
                    break;
                }
            }

            foreach ($reactive as $react) {
                if ($react['month'] == $month) {
                    $residuesTemps[11] = $react;
                    break;
                }
            }

            $residuesList[$month] = $residuesTemps;
        }

        $totalTemp = array_fill(0, 15, 0);
        
        foreach ($total as $data) {
            if ($data['id_residue'] == 1) {
                $totalTemp[1] = $data;
            } else if ($data['id_residue'] == 2) {
                $totalTemp[3] = $data;
            } else if ($data['id_residue'] == 3) {
                $totalTemp[4] = $data;
            } else if ($data['id_residue'] == 4) {
                $totalTemp[5] = $data;
            } else if ($data['id_residue'] == 5) {
                $totalTemp[6] = $data;
            } else if ($data['id_residue'] == 6) {
                $totalTemp[7] = $data;
            } else if ($data['id_residue'] == 7){
                $totalTemp[8] = $data;
            } else if ($data['id_residue'] == 14) {
                $totalTemp[13] = $data;
            } else if ($data['id_residue'] == 17) {
                $totalTemp[14] = $data;
            } else if ($data['id_residue'] == 18) {
                $totalTemp[15] = $data;
            }
        }

        $totalTemp[10] = $totalHeavyMetals[0];
        $totalTemp[11] = $totalReactive[0];

        $residues = $residuesList;
        $total = $totalTemp;

        $data = [
            'status' => true,
            'total' => $total,
            'residues' => $residues,
            'bigTotal' => $bigTotal,
            'user' => auth()->user(),
            'role' => auth()->user()->roles->first()->name,
            'reactive' => $reactive,
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

        $totalGarbageBags = 0;

        $consultaGarbageBags = Waste_collection::selectRaw('DAY(created_at) as day, MONTH(created_at) as month, SUM(DISTINCT garbage_bags) as totalGarbageBags')
        ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") LIKE ?', [$formattedDate . '%'])
        ->groupBy('day', 'month')
        ->get();

        $totalGarbageBagsMonth = [];
        
        if (count($total) != 0) {
            foreach ($consultaGarbageBags as $consulta) {
                $totalGarbageBags += $consulta->totalGarbageBags;
            }
    
            $total[0]['garbage_bags'] = $totalGarbageBags;
            
            
            foreach ($consultaGarbageBags as $item) {
                if (!isset($totalGarbageBagsMonth[$item['month']])) {
                    $totalGarbageBagsMonth[$item['month']] = 0;
                }
                $totalGarbageBagsMonth[$item['month']] += intval($item['totalGarbageBags']);;
            }
    
            for ($i=0; $i < count($residues); $i++) { 
                $residues[$i]['garbage_bags'] = $totalGarbageBagsMonth[$residues[$i]['month']];
            }
        }


        $data = [
            'status' => true,
            'residues' => $residues,
            'total' => $total,
            'totalAño' => $consultaGarbageBags,
            'totalMes' => $totalGarbageBagsMonth,
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
            'treatmentType' => $request->input('treatmentType'),
            'collection_date' => $request->input('date'),
        ]);
        
        if ($request->input('hour') != null) {
            $idCollectionLog = Waste_collection::whereHas('collection_logs')
            ->whereNotNull('hour')
            ->whereDay('created_at', $day)
            ->pluck('collection_logs_id');
    
            for ($i=0; $i < count($idCollectionLog) ; $i++) { 
                $update = CollectionLog::find($idCollectionLog[$i]);
                $update->collection_date = $request->input('date');
                $update->stored_stated = 'RECOLECTADO';
                $update->save();
            }
        };

        $data = [
            'status' => true,
            'yesOrNot' => $request->input('yesOrNot'),
            'hour' => $request->input('hour'),
            'date' => $request->input('date'),
            'day' => $day,
        ];   
        

        return response()->json($request);
    }   
}
