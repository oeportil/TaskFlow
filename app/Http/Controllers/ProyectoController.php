<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
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

    public function show(Proyecto $proyecto)
    {
        return view('proyecto.show', compact('proyecto'));
    }
}
