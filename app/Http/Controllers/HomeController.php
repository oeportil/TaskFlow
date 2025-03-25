<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $tareas = Tarea::where('user_id', Auth::id())
            ->orderByRaw("
                CASE 
                    WHEN estado = 'Pendiente' THEN 1
                    WHEN estado = 'En Progreso' THEN 2
                    WHEN estado = 'Completada' THEN 3
                END
            ")
            ->orderBy('fecha_limite', 'asc') 
            ->paginate(6);
    
        return view('dashboard', compact('tareas'));
    }
}
