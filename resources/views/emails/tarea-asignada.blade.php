<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea Asignada</title>
</head>
<body>
    <h2>Hola {{ $tarea->user->name }},</h2>
    <p>Se te ha asignado una nueva tarea en el proyecto <strong>{{ $tarea->proyecto->nombre }}</strong>.</p>
    <p><strong>Título:</strong> {{ $tarea->titulo }}</p>
    <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
    <p><strong>Prioridad:</strong> {{ ucfirst($tarea->prioridad) }}</p>
    <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>
    
    <br>
    <p><strong>Puedes ver más detalles aquí:</strong></p>
    <a href="{{ $url }}" style="display: inline-block; padding: 10px 15px; background-color: #3490dc; color: #ffffff; text-decoration: none; border-radius: 5px;">
        Ver Tarea
    </a>

    <br><br>
    <p>Saludos,</p>
    <p><strong>El equipo de gestión de proyectos</strong></p>
</body>
</html>
