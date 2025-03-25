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
    public function index(Request $request)
    {
        $query = Tarea::query();
    
        if ($request->filled('search')) {
            $query->where('titulo', 'like', '%' . $request->search . '%')
                  ->orWhereHas('proyecto', function ($q) use ($request) {
                      $q->where('nombre', 'like', '%' . $request->search . '%');
                  });
        }
    
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
    
        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }
    
        if ($request->filled('usuario')) {
            $query->where('user_id', $request->usuario);
        }
    
        $tareas = $query->with(['proyecto', 'user'])
            ->orderByRaw("
                CASE 
                    WHEN estado = 'Pendiente' THEN 1
                    WHEN estado = 'En Progreso' THEN 2
                    WHEN estado = 'Completada' THEN 3
                END
            ")
            ->orderBy('fecha_limite', 'asc')
            ->paginate(10);
    
        $usuarios = User::all();
    
        return view('tarea.index', compact('tareas', 'usuarios'));
    }    
    
    public function create($proyecto_id)
    {
        $proyecto = Proyecto::findOrFail($proyecto_id);
        $usuarios = User::all();
        return view('tarea.create', compact('proyecto', 'usuarios'));
    }

    public function store(Request $request)
    {
        if ($this->proyectoVencido($request->proyecto_id)) {
            session()->flash('error', 'No se pueden agregar tareas a un proyecto vencido.');
            return back()->with('error', 'No se pueden agregar tareas a un proyecto vencido.');
        }
        $proyecto = Proyecto::findOrFail($request->proyecto_id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => 'required|in:baja,media,alta',
            'fecha_limite' => 'required|date|before_or_equal:' . $proyecto->fecha_fin . '|after_or_equal:' . $proyecto->fecha_inicio,
            'proyecto_id' => 'required|exists:proyectos,id',
            'user_id' => 'required|exists:users,id' 
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
        $usuarios = User::all();
        return view('tarea.show', compact('tarea', 'usuarios'));
    }
    public function destroy(Tarea $tarea)
    {
        if ($this->proyectoVencido($tarea->proyecto_id)) {
            session()->flash('error', 'No se pueden eliminar tareas a un proyecto vencido.');
            return back()->with('error', 'No se pueden eliminar tareas a un proyecto vencido.');
        }
        if (auth()->user()->tipo !== 'admin') {
            session()->flash('error', 'No tienes permiso para eliminar esta tarea.');
            abort(403, 'No tienes permiso para eliminar esta tarea.');
        }

        $tarea->delete();

        return redirect()->route('proyecto.show', $tarea->proyecto_id)
            ->with('mensaje', 'Tarea eliminada con éxito.');
    }

    public function actualizarEstado(Request $request, Tarea $tarea)
    {
        if ($this->proyectoVencido($tarea->proyecto_id)) {
            session()->flash('error', 'No se pueden actualizar tareas a un proyecto vencido.');
            return back()->with('error', 'No se pueden actualizar tareas a un proyecto vencido.');
        }
        if ($this->tareaVencida($tarea)) {
            session()->flash('error', 'No se pueden actualizar tareas vencidas.');
            return back()->with('error', 'No se pueden actualizar tareas vencidas.');
        }
        $request->validate([
            'estado' => 'required|in:Pendiente,En Progreso,Completada',
        ]);

        $esAdmin = auth()->user()->tipo === 'admin';
        $esAsignado = auth()->user()->id === optional($tarea->user)->id;
        $fechaVencida = \Carbon\Carbon::now()->greaterThan($tarea->fecha_limite);
        $esCompletada = $tarea->estado === 'Completada';

        if (($esAdmin || $esAsignado) && !$esCompletada && !$fechaVencida) {
            $tarea->update(['estado' => $request->estado]);
            return back()->with('mensaje', 'Estado actualizado correctamente.');
        }

        return back()->with('error', 'No tienes permiso para cambiar el estado de esta tarea.');
    }
    public function actualizarAsignado(Request $request, Tarea $tarea)
    {
        if ($this->proyectoVencido($tarea->proyecto_id)) {
            session()->flash('error', 'No se pueden actualizar tareas a un proyecto vencido.');
            return back()->with('error', 'No se pueden agregar tareas a un proyecto vencido.');
        }
        if ($this->tareaVencida($tarea)) {
            session()->flash('error', 'No se pueden actualizar tareas vencidas.');
            return back()->with('error', 'No se pueden eliminar tareas vencidas.');
        }
        $request->validate([
            'asignado' => 'nullable|exists:users,id',
        ]);
    
        if (auth()->user()->tipo !== 'admin') {
            return back()->with('error', 'No tienes permiso para asignar tareas.');
        }
    
        $tarea->update(['user_id' => $request->asignado]);

        if ($request->user_id) {
            $usuario = User::find($request->user_id);
            Mail::to($usuario->email)->send(new TareaAsignada($tarea));
        }

        return back()->with('mensaje', 'Usuario asignado correctamente.');
        
    }
    private function proyectoVencido($proyecto_id)
    {
        $proyecto = Proyecto::find($proyecto_id);
        return $proyecto && \Carbon\Carbon::now()->greaterThan($proyecto->fecha_fin);
    }
    private function tareaVencida(Tarea $tarea)
    {
        return \Carbon\Carbon::now()->greaterThan($tarea->fecha_limite);
    }
}
