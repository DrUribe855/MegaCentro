<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $request->validate([
            'documento' => 'required',
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        $newEmployee = new User;

        $newEmployee->document = $request->input('documento');
        $newEmployee->name = $request->input('name');
        $newEmployee->last_name = $request->input('lastname');
        $newEmployee->phone = $request->input('phone');
        $newEmployee->email = $request->input('email');
        $newEmployee->role = $request->input('role');
        $newEmployee->password = Hash::make($request->input('password'));

        $newEmployee->save();

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

        if($employees){
            $employees->name = $request->input('name');
            $employees->last_name = $request->input('last_name');
            $employees->phone = $request->input('phone');
            $employees->email = $request->input('email');
            $employees->status = $request->input('status');
            $employees->role = $request->input('role');
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
        $employees = User::paginate(10);

        $data = [
                'status' => true,
                'employees' => $employees->items(),
            ];

        return response()->json( $data );
    }
}
