<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clinic_user;

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
        //
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
