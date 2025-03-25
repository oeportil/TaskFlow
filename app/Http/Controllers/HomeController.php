<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $tareas = Tarea::Where('user_id', Auth::user()->id)->paginate(6);
        return view('dashboard', [
            'tareas' => $tareas
        ]);
    }
}
