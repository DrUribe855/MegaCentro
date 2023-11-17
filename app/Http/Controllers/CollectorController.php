<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
use App\Models\Residue;
use App\Models\Clinic;

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

        $clinicNumber = $request->input('clinic_number');
        $residueName = $request->input('residue_id');
        $weight = $request->input('weight');

        if($residueName &&  $weight){
            $clinicId = Clinic::select('id')
                    ->where('clinic_number', $clinicNumber)
                    ->first();

            $residueId = Residue::select('id')
                        ->where('residue_name', $residueName)
                        ->first();

            $collectionLog = new CollectionLog();
            $collectionLog->clinic_id = $clinicId->id;
            $collectionLog->residue_id = $residueId->id;
            $collectionLog->weight = $request->input('weight');
            $collectionLog->save();

            Clinic::where('id', $clinicId->id)->update(['collection_status' => 'RECOLECTADO']);

            $data = [
                'status' => true,
                'clinics' => $collectionLog,
            ];

            return response()->json($data);
        }else{
            return response()->json('Todos los datos deben ser validados');
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
        // return $clinics;

        $data = [
            'status' => true,
            'clinics' => $clinics
        ];

        return response()->json($data);
    }
}
