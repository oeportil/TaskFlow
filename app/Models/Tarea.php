<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_limite',
        'user_id',
        'proyecto_id'
    ];

    public function proyecto() {
        return $this->belongsTo(Proyecto::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
