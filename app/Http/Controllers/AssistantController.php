<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index(){
        return view('./Assistant/index');
    }

    public function showContinuation(){
        return view('/Assistant/continuation');
    }

    public function showUnified(){
        return view('/Assistant/unified');
    }

    public function showUnifiedContinuation(){
        return view('/Assistant/unifiedContinuation');
    }
}
