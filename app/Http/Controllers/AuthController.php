<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'No registrado',
            ]);
        }

        if (Auth::attempt($credentials)) {

            if($user->status === 'Activo'){
                return response()->json([
                    'status' => true,
                    'message' => 'Los datos ingresados son correctos',
                ]);  
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta desactivada',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Datos incorrectos',  
            ]);
            return back()->withInput();
        }
    }
}
