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
    
    public function index()
    {
        return view('Collector/index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $clinics = $request->datos;
        $general_data = $request->data_general;
        $anioActual = getdate();
        if($anioActual["year"] > $general_data["year"] || $anioActual["year"] < $general_data["year"] || $general_data["year"] == ''){
            $data = [
                'message' => "Año incorrecto",
                'status' => false,
            ];

            return response()->json($data);

        }else if($general_data['month'] > 12 || $general_data['month'] < 1){
            $data = [
                'message' => 'Mes invalido',
                'status' => false,
            ];

            return response()->json($data);

        }else if($general_data['schedule'] == ''){
            $data = [
                'message' => 'Horario no ingresado',
                'status' => false,
            ];

            return response()->json($data);

        }else{
            foreach ($clinics as $key => $clinic) {
                $collection = new CollectionLog();
                $collection->user_id = auth()->user()->id;
                $collection->clinic_id = $clinic["clinic_id"];
                $collection->year = $general_data["year"];
                $collection->month = $general_data["month"];
                $collection->schedule = $general_data["schedule"];
                $collection->save();
                
                foreach ($clinic["data"] as $key => $residue) {
                    if($residue["weight"] > 0){
                        if($residue["bags"] >= 1 ){
                            $residues = new Waste_collection();
                            $residues->id_collection_log = $collection->id;
                            $residues->id_residue = $residue["residue_id"];
                            $residues->weight = $residue["weight"];
                            $residues->garbage_bags = $residue["bags"];
                            $residues->save();    
                        }else{
                            $data = [
                                'message' => "Bolsas vacias",
                                'id_residue' => $residue["residue_id"],
                                'clinic' => $clinic["clinic_id"],
                            ];

                            return response()->json($data);
                        }
                    }  
                }
            }   

        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id){
        //
    }

    public function getClinics(){
        $id = auth()->user()->id;
        $clinics = Clinic_user::whereHas('user', function ($query) use ($id) {
        $query->where('clinic_users.user_id', '=', $id);
        })
        ->with('clinic')
        ->get();
        $residues = Residue::get();

        $data = [
            'status' => true,
            'clinics' => $clinics,
            'residues' => $residues,
        ];

        return response()->json($data);
    }
}
