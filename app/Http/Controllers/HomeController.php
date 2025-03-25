<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $tareas = Tarea::paginate(6);
        $usuarios = User::all();
        return view('dashboard', [
            'tareas' => $tareas,
            'usuarios' => $usuarios
        ]);
    }
}
