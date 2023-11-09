<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Clinic_user;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Auth\Events\Validated;
use Psy\CodeCleaner\FunctionContextPass;
use Spatie\Permission\Models\Role;

class ClinicController extends Controller
{
    public function index(){
        return view('Clinic/clinic');
    }

    public function generalShow(){
        $responsibles = User::role('Encargado')->with('clinic_user.clinic')->get();

        $data = [
                    'status' => true,
                    'responsible' => $responsibles,
                ];

        return response()->json($data);
    }

    public function consultationUser(){
        $consultationOwner = User::role(['Dueño'])
            ->leftJoin('clinic_users', 'users.id', '=', 'clinic_users.user_id')
            ->get('users.*');

        $consultationCollector = User::role(['Recolector'])
            ->leftJoin('clinic_users', 'users.id', '=', 'clinic_users.user_id')
            ->get('users.*');

        $data = [
            'status' => true,
            'infoClinicOwner' => $consultationOwner,
            'infoClinicCollector' => $consultationCollector,
        ];

        return response()->json($data);
    }

    public function generalShowClinic(){
        $records = Clinic::select('Clinics.*')
            ->LEFTJOIN ('Clinic_users', 'Clinics.id', '=', 'Clinic_users.clinic_id')
            ->where('Clinic_users.clinic_id')
            ->get();

        $data = [
                    'status' => true,
                    'clinics' => $records, 
                ];

        return response()->json($data);
    }

    public function showClinicResponsible(){
        $clinicResponsible = User::role('Encargado')
        ->join('clinic_users', 'users.id', '=', 'clinic_users.user_id')
        ->join('clinics', 'clinic_users.clinic_id', '=', 'clinics.id')
        ->get();

        $data = [
            'status' => true,
            'clinic' => $clinicResponsible,
        ];

        return response()->json($data);
    }

    public function showTower($tower){
        $consultation = Clinic::select('clinics.*')
        ->where('Clinics.tower_id', '=', $tower)
        ->get();
        
        $data = [
                    'status' => true,
                    'tower' => $consultation,
                ];

        return response()->json($data);
    }

    public function register(Request $request){
        $request->validate([
            'clinic_number' => 'required',
            'tower_id' => 'required',
            'status' => 'required',
        ]);

        $clinic = new Clinic();
        $clinic->clinic_number = $request->input('clinic_number');
        $clinic->tower_id = $request->input('tower_id');
        $clinic->status = $request->input('status');
        $clinic->save(); 

        $clinicResponsible = User::role('Encargado')
        ->join('clinic_users', 'users.id', '=', 'clinic_users.user_id')
        ->join('clinics', 'clinic_users.clinic_id', '=', 'clinics.id')
        ->get();

        $data = [
                    'status' => true,
                    'clinic' => $clinic,
                    'clinicResponsible' => $clinicResponsible,
                ];

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $dataEdit = $request->input('data');
        $clinic = Clinic::find($id);
        $clinic->tower_id = $dataEdit['tower_id'];
        $clinic->status = $dataEdit['status'];
        $clinic->clinic_number = $dataEdit['clinic_number'];
        $clinic->save();

        $data = [
                    'status' => true,
                ];
        return response()->json($data);
    }

    public function consultation($id){
        $consultation = Clinic_user::select('Users.*', 'Clinics.*', 'model_has_roles.role_id')
        ->join('Users', 'Clinic_users.user_id', '=', 'Users.id')
        ->join('Clinics', 'Clinic_users.clinic_id', '=', 'clinics.id')
        ->join('model_has_roles', 'Users.id', '=', 'model_has_roles.model_id')
        ->where('Clinics.id', '=', $id)
        ->where('model_has_roles.role_id', '=', 3)
        ->orWhere('model_has_roles.role_id', '=', 5)
        ->get();

        $data = [
            'status' => true,
            'infoClinic' => $consultation,
        ];

        return response()->json($data);
    }

    public function infoClinic($id){ 
        $clinic = Clinic::SELECT('Clinics.*')
        ->JOIN('Clinic_users', 'Clinics.id', '=', 'Clinic_users.clinic_id')
        ->WHERE('Clinic_users.user_id', '=', $id)
        ->GET();    

        $data = [
                    'status' => true,
                    'clinic' => $clinic,
                ];

        return response()->json($data);
    }

    public function addClinic(Request $request){
        $request->validate([
            'clinic' => 'required'
        ]);

        $id = $request->input('clinic');
        $clinic_user = new Clinic_user();
        $clinic_user->user_id = $request->input('user');
        $clinic_user->clinic_id = $id;
        $clinic_user->save();

        $clinic = Clinic::find($id);
        $clinic->status = 'OCUPADO';
        $clinic->save();

        $records = User::role('Recolector')->with('clinic_user.clinic')->get();

        $data = [
                    'status' => true,
                    'responsible' => $records,
                    'clinic' => $clinic
                ];   

        return response()->json($data);
    }

    public function addUser(Request $request){
        $request->validate([
            'clinic' => 'required',
            'user' => 'required',
        ]);

        $clinic_id = $request->input('clinic');
        $user_id = $request->input('user');
        $search = Clinic_user::where('user_id', '=', $user_id)
        ->where('clinic_id', '=', $clinic_id)
        ->count();

        $status = false;
        if ($search == 0) {
            $clinic_user = new Clinic_user();
            $clinic_user->user_id = $user_id;
            $clinic_user->clinic_id = $clinic_id;
            $clinic_user->save(); 
            $status = true;
        }else{
            $status = false;
        }   

        $records = Clinic_user::select('Users.*', 'Clinics.*', 'model_has_roles.role_id')
        ->join('Users', 'Clinic_users.user_id', '=', 'Users.id')
        ->join('Clinics', 'Clinic_users.clinic_id', '=', 'clinics.id')
        ->join('model_has_roles', 'Users.id', '=', 'model_has_roles.model_id')
        ->where('Clinics.id', '=', $clinic_id)
        ->where('model_has_roles.role_id', '=', 3)
        ->orWhere('model_has_roles.role_id', '=', 5)
        ->get();

        $data = [
                    'status' => $status,
                    'user' => $records
                ];   

        return response()->json($data);
    }

    public function deleteUser(Request $request){
        $user = User::where('document', $request->input('document'))->get('id');
        $user_id = $user->first()->id;
        $clinic_id = $request->input('id');
        
        $clinic_user = Clinic_user::where('clinic_id', '=', $clinic_id)
        ->where('user_id', '=', $user_id)
        ->delete();

        $records = Clinic_user::select('Users.*', 'Clinics.*', 'model_has_roles.role_id')
        ->join('Users', 'Clinic_users.user_id', '=', 'Users.id')
        ->join('Clinics', 'Clinic_users.clinic_id', '=', 'clinics.id')
        ->join('model_has_roles', 'Users.id', '=', 'model_has_roles.model_id')
        ->where('Clinics.id', '=', $clinic_id)
        ->where('model_has_roles.role_id', '=', 3)
        ->orWhere('model_has_roles.role_id', '=', 5)
        ->get();

        $data = [
                    'status' => true,
                    'user_id' => $user_id,
                    'clinic_id' => $clinic_id, 
                    'response' => $clinic_user,
                    'users' => $records,
                ];

        return response()->json($data);
    }
}
