<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
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
        $responsibles = User::role('Responsable')
        ->with(['clinic_user.clinic.collection_log' => function($query){
            $query->where('invoice_status', 'Debe')
            ->with('waste_collection.residues');
        }])
        ->get();

        $data = [
            'status' => true,
            'responsible' => $responsibles,
        ];

        return response()->json($data);
    }

    public function showInvoince($id, $status){
        $responsibles = User::role('Responsable')
        ->where('id', $id)
        ->with(['clinic_user' => function($query) use ($status) {
            $query->whereHas('clinic.collection_log', function($query) use($status){
                $query->where('invoice_status', $status);
            })
            ->with(['clinic.collection_log' => function($query) use($status){
                $query->where('invoice_status', $status)
                ->with('waste_collection.residues');
            }]);
        }])
        ->get();

        $data = [
            'status' => $status,
            'responsible' => $responsibles,
        ];

        return response()->json($data);
    }

    public function consultationUser(){
        $consultationOwner = User::role(['Dueño'])->get();

        $consultationCollector = User::role(['Recolector'])->get();

        $data = [
            'status' => true,
            'infoClinicOwner' => $consultationOwner,
            'infoClinicCollector' => $consultationCollector,
        ];

        return response()->json($data);
    }

    public function generalShowClinic(){
        $records = Clinic::doesnthave('clinic_user')->get();

        $data = [
                    'status' => true,
                    'clinics' => $records, 
                ];

        return response()->json($data);
    }

    public function showClinicResponsible(){
        $clinicResponsible = Clinic_user::whereHas('user', function($query){
            $query->role('Responsable');
        })->with('clinic', 'user')->get();

        $data = [
            'status' => true,
            'clinic' => $clinicResponsible,
        ];

        return response()->json($data);
    }

    public function showTower($tower){
        $consultation = Clinic::where('tower_id', $tower)->get();
        
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
            'floor' => 'required',
        ]);

        // $clinic = new Clinic($request->all()); => me da error con esta line en el campo de floor
        $clinic = new Clinic($request->all());
        $clinic->clinic_number = $request->input('clinic_number');
        $clinic->floor = $request->input('floor');
        $clinic->tower_id = $request->input('tower_id');
        $clinic->status = $request->input('status');
        $clinic->save(); 

        $data = [
                    'status' => true,
                    'clinic' => $clinic,
                ];

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $dataEdit = $request->input('data');
        $clinic = Clinic::find($id);
        $clinic->tower_id = $dataEdit['tower_id'];
        $clinic->floor = $dataEdit['floor'];
        $clinic->status = $dataEdit['status'];
        $clinic->clinic_number = $dataEdit['clinic_number'];
        $clinic->save();

        $data = [
                    'status' => true,
                ];
        return response()->json($data);
    }

    public function consultation($id, $status){
        if ($status == 1) {
            $consultation = Clinic_user::whereHas('user', function($query){
                $query->role('Dueño');
            })->whereHas('clinic', function($query) use($id){
                $query->where('clinic_id', $id);
            })
            ->with('user')
            ->get();
        }else{
            $consultation = Clinic_user::whereHas('user', function($query){
                $query->role('Recolector');
            })->whereHas('clinic', function($query) use($id){
                $query->where('clinic_id', $id);
            })
            ->with('user')
            ->get();
        }

        $data = [
            'status' => true,
            'infoClinic' => $consultation,
            'id' => $id,
            'role' => $status,
        ];

        return response()->json($data);
    }

    public function infoClinic($id){    
        $clinic = Clinic::whereHas('clinic_user', function($query) use($id){
            $query->where('user_id', $id);
        })->get();

        $data = [
                    'status' => true,
                    'clinic' => $clinic,
                ];

        return response()->json($data);
    }

    public function addClinic(Request $request){
        $request->validate([
            'clinic_id' => 'required'
        ]);

        $id = $request->input('clinic_id');
        $clinic_user = new Clinic_user($request->all());
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
            'clinic_id' => 'required',
            'user_id' => 'required',
        ]);

        $clinic_id = $request->input('clinic_id');
        $user_id = $request->input('user_id');
        $search = Clinic_user::where('user_id', '=', $user_id)
        ->where('clinic_id', '=', $clinic_id)
        ->count();

        $status = false;
        if ($search == 0) {
            $clinic_user = new Clinic_user($request->all());
            $clinic_user->save(); 
            $status = true;
        }else{
            $status = false;
        }   

        $records = null;
        if ($request->input('status') == 1) {
            $records = Clinic_user::whereHas('user', function($query){
                $query->role('Dueño');
            })->whereHas('clinic', function($query) use($clinic_id){
                $query->where('clinic_id', $clinic_id);
            })
            ->with('user')
            ->get();
        }else if ($request->input('status') == 2) {
            $records = Clinic_user::whereHas('user', function($query){
                $query->role('Recolector');
            })->whereHas('clinic', function($query) use($clinic_id){
                $query->where('clinic_id', $clinic_id);
            })
            ->with('user')
            ->get();
        }

        $data = [
                    'status' => $status,
                    'user' => $records,
                ];   
        return response()->json($data);

    }

    public function deleteUser($role, Request $request){
        $user = User::where('document', $request->input('user.document'))->get('id');
        $user_id = $user->first()->id;

        $clinic_id = $request->input('clinic_id');
        
        Clinic_user::where('clinic_id', $clinic_id)
        ->where('user_id', $user_id)
        ->delete();

        $records = null;
        if ($role == 'dueños') {
            $records = Clinic_user::whereHas('user', function($query){
                $query->role('Dueño');
            })->whereHas('clinic', function($query) use($clinic_id){
                $query->where('clinic_id', $clinic_id);
            })->with('user')->get();
        }else if ($role == 'recolectores') {
            $records = Clinic_user::whereHas('user', function($query){
                $query->role('Recolector');
            })->whereHas('clinic', function($query) use($clinic_id){
                $query->where('clinic_id', $clinic_id);
            })->with('user')->get();
        }


        $data = [
                    'status' => true,
                    'user_id' => $user_id,
                    'clinic_id' => $clinic_id, 
                    'response' => $role,
                    'users' => $records,
                ];

        return response()->json($data);
    }

    public function payBill($id){
        $pay = CollectionLog::find($id);
        $pay->invoice_status = 'Pago';
        $pay->save();

        $data = [
                    'status' => true,
                ];
        return response()->json($data);
    }
}
