<?php

namespace App\Mail;

use App\Models\Tarea;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TareaAsignada extends Mailable
{
    use Queueable, SerializesModels;

    public $tarea;

    public function __construct(Tarea $tarea)
    {
        $this->tarea = $tarea;
    }

    public function build()
    {
        return $this->subject('Nueva Tarea Asignada')
            ->view('emails.tarea-asignada')
            ->with([
                'url' => route('tarea.show', $this->tarea->id)
            ]);
    }
}