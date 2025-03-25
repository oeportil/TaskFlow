<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProyectoController extends Controller
{
    //

    public function index(){
        $proyectos = Proyecto::paginate(5);
        return view('proyecto.index', [
            'proyectos' => $proyectos
        ]);
    }

    public function create(){
        return view('proyecto.create');
    }

    public function edit(Proyecto $proyecto){
        return view('proyecto.edit', [
            'proyecto' => $proyecto
        ]);
    }

    public function destroy(Proyecto $proyecto){
        $proyecto->delete();
        return redirect()->route('proyecto.index')->with('mensaje', 'Proyecto eliminado con éxito.');
    }

    public function show(Proyecto $proyecto, Request $request)
    {
        $usuarios = User::all();  // Obtén los usuarios para el filtro
        $tareas = $proyecto->tareas();

        // Filtros
        if ($request->has('estado') && $request->estado != '') {
            $tareas = $tareas->where('estado', $request->estado);
        }

        if ($request->has('prioridad') && $request->prioridad != '') {
            $tareas = $tareas->where('prioridad', $request->prioridad);
        }

        if ($request->has('usuario') && $request->usuario != '') {
            $tareas = $tareas->where('user_id', $request->usuario);
        }

        $tareas = $tareas->get();

        return view('proyecto.show', compact('proyecto', 'tareas', 'usuarios'));
    }
}
