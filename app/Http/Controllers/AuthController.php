<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('email', 'password');


        

        if (Auth::attempt($credentials)) {

            return response()->json([
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => false,
                
            ]);
            session()->flash('message', 'Email or password incorrect');
            return back()->withInput();
        }
    }
}
