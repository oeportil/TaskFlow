<?php

namespace App\Livewire;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class GraficasDashboard extends Component
{
    public $proyectosCompletados;
    public $tareasCompletadas;
    public $usuariosConMasTareas;

    public function mount(){
        $this->proyectosCompletados = $this->CalculoProyectCompletados();
        $this->tareasCompletadas = $this->CalculoTareasCompletadas();
        $this->usuariosConMasTareas = $this->CalculoUsuariosConMasTareas();
    }


    public function render()
    {
        return view('livewire.graficas-dashboard');
    }

    private function CalculoUsuariosConMasTareas(){
        $users = User::select('users.name', DB::raw('COUNT(tareas.id) as numTareas'))
                ->join('tareas', 'users.id', '=', 'tareas.user_id')
                ->groupBy('users.name')
                ->orderBy('numTareas', 'desc')
                ->limit(10)
                ->get();
        return $users;
    }

    private function CalculoTareasCompletadas(){
        $tareasCompletadas = [];
        foreach(Proyecto::all() as $proyecto):
            $totalTareas = $proyecto->tareas->count();
            $tareasCompletadas[$proyecto->id] = ["totalPerProject" => $proyecto->tareas->where('estado', 'Completada')->count(),
            "nombre" => $proyecto->nombre , "id" => $proyecto->id];
        endforeach;
        return $tareasCompletadas;
    }

    private function CalculoProyectCompletados(){
        $projCompt = [];
        foreach(Proyecto::all() as $proyecto):
            $totalTareas = $proyecto->tareas->count();
            $progresoTotal = 0;

            foreach($proyecto->tareas as $tarea) {
                if ($tarea->estado == 'Pendiente') {
                    $progresoTotal += 0;
                } else if($tarea->estado == 'En Progreso') {
                    $progresoTotal += 50;
                } else {
                    $progresoTotal += 100;
                }
            }
            $progresoTotal = $totalTareas ? $progresoTotal / $totalTareas : 0;
            $projCompt[$proyecto->id] = [$proyecto->nombre => $progresoTotal, "id" => $proyecto->id];
        endforeach;

        return $projCompt;
    }
}
