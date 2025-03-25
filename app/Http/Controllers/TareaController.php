<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TareaAsignada;

class TareaController extends Controller
{
    public function create($proyecto_id)
    {
        $proyecto = Proyecto::findOrFail($proyecto_id);
        $usuarios = User::all();
        return view('tarea.create', compact('proyecto', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_limite' => 'required|date',
            'proyecto_id' => 'required|exists:proyectos,id',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $tarea = Tarea::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'estado' => 'Pendiente', 
            'prioridad' => $request->prioridad,
            'fecha_limite' => $request->fecha_limite,
            'proyecto_id' => $request->proyecto_id,
            'user_id' => $request->user_id
        ]);

        if ($request->user_id) {
            $usuario = User::find($request->user_id);
            Mail::to($usuario->email)->send(new TareaAsignada($tarea));
        }

        return redirect()->route('proyecto.show', $request->proyecto_id)
            ->with('mensaje', 'Tarea creada con éxito.');
    }
    public function show(Tarea $tarea)
    {
        return view('tarea.show', compact('tarea'));
    }
}
