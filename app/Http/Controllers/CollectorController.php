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

        // return response()->json($clinics[0]["clinic_id"]);
        foreach ($clinics as $key => $clinic) {
            $collection = new CollectionLog();
            $collection->user_id = auth()->user()->id;
            $collection->clinic_id = $clinic["clinic_id"];
            $collection->year = $general_data["year"];
            $collection->month = $general_data["month"];
            $collection->schedule = $general_data["schedule"];
            $collection->save();
            
            foreach ($clinic["data"] as $key => $residue) {
                $residues = new Waste_collection();
                $residues->id_collection_log = $collection->id;
                $residues->id_residue = $residue["residue_id"];
                $residues->weight = $residue["weight"];
                $residues->garbage_bags = $residue["bags"];
                $residues->save();
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
