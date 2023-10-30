<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $role = Role::findOrCreate($request->input('role_name'));


        if($employees){
            $employees->name = $request->input('name');
            $employees->last_name = $request->input('last_name');
            $employees->phone = $request->input('phone');
            $employees->email = $request->input('email');
            $employees->roles()->sync([$role->id]);
            // $employees->status = $request->input('status');
            $employees->save();

            $data = [
                        'status' => true,
                        'employees' => $employees,
                    ];

            return response()->json($data);

        }else{
            $data = [
                'status' => false,
                'message' => 'Empleado no encontrado',
            ];

            return response()->json($data, 404);
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
