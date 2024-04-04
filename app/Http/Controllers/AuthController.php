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

        if ($user->status === 'Activo') {
            if(Auth::attempt($credentials)){
                return response()->json([
                    'status' => true,
                    'message' => 'Los datos ingresados son correctos',
                ]);  
            }else{

                $data = [
                    'status' => false,
                    'message' => 'Datos incorrectos',
                ];
                return response()->json($data);
            }
        }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta desactivada',
                ]);
            return back()->withInput();
        }

    }

    public function logout(){
        Auth::logout(); // Cerrar sesión del usuario
        return redirect('/login');
    }
}
