<?php

namespace App\Livewire;

use App\Models\Proyecto;
use Livewire\Component;

class CrearProyecto extends Component
{
    public $nombre;
    public $descripcion;
    public $fecha_inicio;
    public $fecha_fin;

    protected $rules = [
        'nombre' => 'required|string',
        'descripcion' => 'required|string|max:2500',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date'
    ];
    public function render()
    {
        return view('livewire.crear-proyecto');
    }

    public function crearProyecto(){
        $datos = $this->validate();
        Proyecto::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin']
        ]);
        session()->flash('mensaje', 'Proyecto Creado Con Exito');
        return redirect()->route('proyecto.index');
    }

}
