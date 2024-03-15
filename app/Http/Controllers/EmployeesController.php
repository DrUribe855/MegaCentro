<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
 

class EmployeesController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function create()
    {
        //
    }
 
    public function store(Request $request)
    {   


        $validatedData = $request->validate([
            'document' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|',
            'role_name' => 'required',
            'status' => 'required',
            'password' => 'required',
        ]);


        $document = $request->input('document');
        $email = $request->input('email');

        $userDocument = DB::table('users')->where('document', $document)->first();
        $userEmail = DB::table('users')->where('email', $email)->first();
        if($userDocument){
             
            return response()->json([
                'status' => false,
                'message' => 'Documento registrado', 
            ]);

        }else if($userEmail){

            return response()->json([
                'status' => false,
                'message' => 'Email registrado', 
            ]);

        }else{
            $newEmployee = new User;
            $newEmployee->document = $request->input('document');
            $newEmployee->name = $request->input('name');
            $newEmployee->last_name = $request->input('last_name');
            $newEmployee->phone = $request->input('phone');
            $newEmployee->email = $request->input('email');
            $newEmployee->status = $request->input('status');
            $newEmployee->password = Hash::make($request->input('password'));
    
            $newEmployee->save();
    
            $role = Role::findByName($validatedData['role_name']);
            $newEmployee->assignRole($role);
    
            $data = [
                'status' => true,
                'Employee' => $newEmployee,
            ];
    
            return response()->json([
                    'message' => 'Registro guardado correctamente',
                    'data' => $data,
                ]);

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
        $employees = User::find($id);
        $originalDocument = $employees->document;
        $originalEmail = $employees->email;
        $role = Role::findOrCreate($request->input('role_name'));
        $password = $request->input('password');

        // return response()->json($request);


        $validatedData = $request->validate([   
            'document' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|',
            'role_name' => 'required',
            'status' => 'required',
        ]);

        $document = $request->input('document');
        $email = $request->input('email');

        $userDocument = DB::table('users')->where('document', $document)->first();
        $userEmail = DB::table('users')->where('email', $email)->first();

        $edit = true;

        if($originalEmail === $email ){
            $edit = true;
        }else{
            if($userEmail){
                $edit = false;
                return response()->json([
                    'status' => false,
                    'message' => 'Email registrado', 
                ]);
            }
        }

        if($originalDocument === $document){
            $edit = true;
        }else{
            if($userDocument){
                $edit = false;
                return response()->json([
                    'status' => false,
                    'message' => 'Documento registrado', 
                ]);

            }
        }

        if($edit){
            if($password == ''){
                $employees->document = $document;
                $employees->name = $request->input('name');
                $employees->last_name = $request->input('last_name');
                $employees->phone = $request->input('phone');
                $employees->email = $request->input('email');
                $employees->roles()->sync([$role->id]);
                $employees->status = $request->input('status');
                $employees->save();
                $data = [
                            'status' => true,
                            'employees' => $employees,
                            'message' => 'Usuario modificado',
                        ];
                return response()->json($data);
            }else{
                $employees->document = $document;
                $employees->name = $request->input('name');
                $employees->last_name = $request->input('last_name');
                $employees->phone = $request->input('phone');
                $employees->email = $request->input('email');
                $employees->roles()->sync([$role->id]);
                $employees->password = Hash::make($request->input('password'));
                $employees->status = $request->input('status');
                $employees->save();
                $data = [
                            'status' => true,
                            'employees' => $employees,
                            'message' => 'Usuario modificado',
                        ];
                return response()->json($data);
            }
            
        }

    }

    

   
    public function destroy($id)
    {
        //
    }

    public function generalShow(){
        $employees = User::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.name as role_name')
                    ->get();

        $data = [
                'status' => true,
                'employees' => $employees,
            ];

        return response()->json( $data );
    }
}



