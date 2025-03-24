<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //proyectos
    Route::get('/proyecto', [ProyectoController::class,'index'])->name('proyecto.index');
    Route::get('/proyecto/create', [ProyectoController::class,'create'])->name('proyecto.create');
    Route::get('/proyecto/{proyecto}/edit', [ProyectoController::class,'edit'])->name('proyecto.edit');
    Route::delete('/proyecto/{proyecto}/delete', [ProyectoController::class,'destroy'])->name('proyecto.delete');
});

require __DIR__.'/auth.php';
