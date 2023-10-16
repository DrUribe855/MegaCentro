<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $employees = User::get();

        $data = [
                'status' => true,
                'employees' => $employees,
            ];

        return response()->json( $data );
    }
}
