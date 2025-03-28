<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    //Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/usuarios', [ProfileController::class, 'index'])->name('usuario.index');
    Route::get('/usuarios/{id}/tareas', [ProfileController::class, 'verTareas'])->name('usuario.tareas');
    Route::patch('/usuarios/{id}/cambiar-tipo', [ProfileController::class, 'cambiarTipoUsuario'])->name('usuario.cambiarTipo');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //proyectos
    Route::get('/proyecto', [ProyectoController::class,'index'])->name('proyecto.index');
    Route::get('/proyecto/create', [ProyectoController::class,'create'])->name('proyecto.create');
    Route::get('/proyecto/{proyecto}/edit', [ProyectoController::class,'edit'])->name('proyecto.edit');
    Route::get('/proyecto/{proyecto}/show', [ProyectoController::class,'show'])->name('proyecto.show');
    Route::get('/proyecto/{id}/exportar-excel', [ProyectoController::class, 'exportarExcel'])->name('proyecto.exportarExcel');
    Route::get('/proyecto/{id}/export/pdf', [ProyectoController::class, 'exportPDF'])->name('proyecto.export.pdf');
    Route::delete('/proyecto/{proyecto}/delete', [ProyectoController::class,'destroy'])->name('proyecto.delete');


    //tareas
    Route::get('/tarea', [TareaController::class, 'index'])->name('tarea.index');
    Route::get('/tarea/create/{proyecto_id}', [TareaController::class, 'create'])->name('tarea.create');
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tarea.show');
    Route::post('/tarea/{tarea}/checklist', [TareaController::class, 'agregarChecklist'])->name('tarea.checklist.agregar');
    Route::patch('/tarea/checklist/{checklist}', [TareaController::class, 'actualizarChecklist'])->name('tarea.checklist.actualizar');
    Route::delete('/tarea/checklist/{checklist}', [TareaController::class, 'eliminarChecklist'])->name('tarea.checklist.eliminar');
    Route::post('/tarea/{tarea}/comentario', [TareaController::class, 'agregarComentario'])->name('tarea.comentario.agregar');
    
    Route::post('/tarea/store', [TareaController::class, 'store'])->name('tarea.store');
    Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tarea.destroy');
    Route::patch('/tareas/{tarea}/estado', [TareaController::class, 'actualizarEstado'])->name('tarea.actualizarEstado');
    Route::patch('/tareas/{tarea}/asignado', [TareaController::class, 'actualizarAsignado'])->name('tarea.actualizarAsignado');
    
});

require __DIR__.'/auth.php';
