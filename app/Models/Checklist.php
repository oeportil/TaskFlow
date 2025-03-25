<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{

    
    protected $fillable = [
        'item',
        'valor',
        'fecha_creacion',
        'fecha_modificacion',
        'tarea_id',
    ];

    
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}
