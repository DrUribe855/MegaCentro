<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\ClinicTower;
use App\Models\User;

class ClinicController extends Controller
{
    public function index(){
        return view('Clinic/clinic');
    }

    public function generalShow(){
        $responsible = User::with('clinic')->get();

        $data = [
                    'status' => true,
                    'responsible' => $responsible,
                ];

        return response()->json($data);
    }

    public function register(Request $request){
        $request->validate([
            'clinic_number' => 'required|unique:clinics',
        ]);

        $clinic = new Clinic();
        $clinic->clinic_number = $request->input('clinic_number');
        $clinic->user_id = $request->input('document_id');
        $clinic->status = 'ACTIVO';
        $clinic->save();

        $records = Clinic::with('user')->get();

        $data = [
            'status' => true,
            'clinic' => $records,
        ];   

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $clinicData = $request->input('clinicData');
        if ($request->input('validateStatus') == true) {
            $validator = Validator::make($clinicData, [
                'clinic_number' => 'required|unique:clinics',
            ]);
            if ($validator->failed()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } 
        }
        $clinic = Clinic::find($id);
        $clinic->status = $clinicData['status'];
        $clinic->clinic_number = $clinicData['clinic_number'];
        $clinic->save();

        $records = Clinic::with('user')->get();
        $data = [
                    'status' => true,
                    'clinic' => $records,
                ];
        return response()->json($data);
    }

    public function search(Request $request){
        $search = $request->input('search');
        $optionSearch = $request->input('optionSearch');
        $records = '';
        if ($optionSearch == 'status') {
            $records = Clinic::where('status', 'LIKE', "$search%")->with('user')->get();
        }else if ($optionSearch == 'documentNumber') {
            $records = Clinic::whereHas('user', function ($query) use ($search) {
                $query->where('document', 'LIKE', "$search%");
            })->with('user')->get();
        }else if ($optionSearch == 'clinicNumber') {
            $records = Clinic::where('clinic_number', 'LIKE', "$search%")->with('user')->get();
        }

        if ($records != '') {
            return response()->json($records);   
        }else {
            $records = '120';
            return $records;
        }
    }
}
