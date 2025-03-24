<?php

namespace App\Livewire;

use App\Models\Proyecto;
use Illuminate\Support\Carbon as Carbonoso;
use Livewire\Component;

class EditarProyecto extends Component
{
    public $proyecto_id;
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

    public function mount(Proyecto $proyecto){
        $this->proyecto_id = $proyecto->id;
        $this->nombre = $proyecto->nombre;
        $this->descripcion = $proyecto->descripcion;
        $this->fecha_inicio = Carbonoso::parse($proyecto->fecha_inicio)->format('Y-m-d');
        $this->fecha_fin = Carbonoso::parse($proyecto->fecha_fin)->format('Y-m-d');
    }
    public function render()
    {
        return view('livewire.editar-proyecto');
    }


    public function editarProyecto(){
        $datos = $this->validate();
        $proyecto = Proyecto::find($this->proyecto_id);
        $proyecto->update([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
        ]);
        session()->flash('mensaje', 'El Proyecto Se Actualizo con exito');
        return redirect()->route('proyecto.index');
    }
}
