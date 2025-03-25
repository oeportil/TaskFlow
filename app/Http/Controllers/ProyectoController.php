<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Exports\ProyectoExport;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $usuarios = User::all();
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

        // Obtener tareas y ordenarlas
        $tareas = $tareas->orderByRaw("
            CASE 
                WHEN estado = 'Pendiente' THEN 1
                WHEN estado = 'En Progreso' THEN 2
                WHEN estado = 'Completada' THEN 3
            END
        ")->orderBy('fecha_limite', 'asc')->get();

        return view('proyecto.show', compact('proyecto', 'tareas', 'usuarios'));
    }
    public function exportarExcel($id)
    {
        $proyecto = Proyecto::with('tareas')->findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Encabezados
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Título');
        $sheet->setCellValue('C1', 'Descripción');
        $sheet->setCellValue('D1', 'Estado');
        $sheet->setCellValue('E1', 'Prioridad');
        $sheet->setCellValue('F1', 'Fecha Límite');
        $sheet->setCellValue('G1', 'Asignado A');

        // Datos de las tareas
        $fila = 2;
        foreach ($proyecto->tareas as $tarea) {
            $sheet->setCellValue('A' . $fila, $tarea->id);
            $sheet->setCellValue('B' . $fila, $tarea->titulo);
            $sheet->setCellValue('C' . $fila, $tarea->descripcion);
            $sheet->setCellValue('D' . $fila, $tarea->estado);
            $sheet->setCellValue('E' . $fila, $tarea->prioridad);
            $sheet->setCellValue('F' . $fila, $tarea->fecha_limite);
            $sheet->setCellValue('G' . $fila, $tarea->user->name ?? 'Sin asignar');
            $fila++;
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="proyecto_'.$proyecto->id.'.xlsx"');

        return $response;
    }

    public function exportPDF($id)
    {
        $proyecto = Proyecto::with('tareas', 'tareas.user')->findOrFail($id);
        $pdf = Pdf::loadView('exports.proyecto_pdf', compact('proyecto'));
        return $pdf->download('proyecto_' . $id . '.pdf');
    }
}
