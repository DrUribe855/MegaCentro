<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
use App\Models\Residue;
use App\Models\Clinic;
use App\Models\Waste_collection;

class CollectorController extends Controller
{
    
    public function index(){
        return view('Collector/index');
    }

    public function create(){
    
    }

    public function store(Request $request)
    {
        $clinics = $request->datos;
        $general_data = $request->data_general;
        $anioActual = getdate();
        $validationStatus = true;

        if($this->dateValidations($general_data, $anioActual)){
            if($this->collectionValidate($clinics)){
                foreach ($clinics as $key => $clinic) {
                    if($this->clinicValidate($clinic)){
                        $collection = new CollectionLog();
                        $collection->user_id = auth()->user()->id;
                        $collection->clinic_id = $clinic["clinic_id"];
                        $collection->year = $general_data["year"];
                        $collection->month = $general_data["month"];
                        $collection->schedule = $general_data["schedule"];
                        $collection->save();

                        foreach ($clinic["data"] as $key => $residue) {
                            if($residue["weight"] != 0 && $residue["weight"] != 0){
                                $residues = new Waste_collection();
                                $residues->id_collection_log = $collection->id;
                                $residues->id_residue = $residue["residue_id"];
                                $residues->weight = $residue["weight"];
                                $residues->garbage_bags = $residue["bags"];
                                $residues->save();
                            }
                        }

                        $data = [
                            'message' => 'Recolección registrada',
                            'status' => true,
                        ];

                        return response()->json($data);
                    }
                }
            }else{
                $data = [
                    'message' => "Datos incompletos",
                    'status' => false,
                ];

                return response()->json($data);
            }
        }else{
            $data = [
                'message' => "Datos incorrectos en la fecha",
                'status' => false,
            ];

            return response()->json($data);
        }
                
        
            
    }

    public function dateValidations($general_data, $anioActual){


        if($anioActual["year"] > $general_data["year"] || $anioActual["year"] < $general_data["year"] || $general_data["year"] == ''){     
            return false;
        } 

        if($general_data['month'] > 12 || $general_data['month'] < 1){
            return false;
        }

        if($general_data['schedule'] == ''){
            return false;
        }

        return true;
        
    }

    public function collectionValidate($clinics){
        foreach ($clinics as $key => $clinic) { 
            foreach ($clinic["data"] as $key => $residue) {
                if($residue["weight"] > 0){
                    if($residue["bags"] < 1){
                        return false;

                    }
                }else if($residue["bags"] >= 1){
                    if($residue["weight"] <= 0){
                        return false;                      
                        
                    }
                }
            }   
        }
        return true;
    }

    public function clinicValidate($clinic){
        foreach ($clinic["data"] as $key => $residue) {
            if($residue["weight"] != 0 && $residue["bags"] != 0){
                return true;
            }
        }  

        return false; 
    }

    public function show($id){
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }

    public function getClinics(Request $request){

        $clinicNumber = $request->clinicNumber;
        $towerNumber = $request->towerNumber;

        $id = auth()->user()->id;
        $clinics = Clinic_user::whereHas('user', function ($query) use ($id) {
            $query->where('clinic_users.user_id', '=', $id);
        })
        ->with('clinic')
        ->whereHas('clinic', function ($query) use ($clinicNumber) {
            $query->where('clinic_number', "LIKE", "%$clinicNumber%"); 
        })
        ->whereHas('clinic', function ($query) use ($towerNumber) {
            $query->where('tower_id', "LIKE", "%$towerNumber%"); 
        })
        ->get();

        $residues = Residue::get();
        $currentDate = date('Y-m');
        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [
            'status' => true,
            'clinics' => $clinics,
            'residues' => $residues,
            'year' => $currentYear,
            'month' => $currentMonth,
        ];

        return response()->json($data);
    }
}
