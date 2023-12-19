<?php

namespace App\Http\Controllers;

use App\Models\Residue;
use Illuminate\Http\Request;

class ResiduePriceController extends Controller
{
    public function view(){
        return view('Manager/residuePrice');
    }

    public function register($id, Request $request){
        $price = str_replace(',', '.', $request->input('data'));
        $update = Residue::find($id);
        $update->price = $price;
        $update->save();
        
        $data = [
            'id' => $id,
            'update' => $update,
        ];

        return response()->json($data);
    }

    public function residuePrice(){
        $data = [
            'status' => true,
            'residues' => Residue::get(),
        ];

        return response()->json($data);
    }
}
