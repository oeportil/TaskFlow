<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proyecto</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>{{ $proyecto->nombre }}</h2>
    <p><strong>Descripción:</strong> {{ $proyecto->descripcion }}</p>
    <p><strong>Fecha de Inicio:</strong> {{ $proyecto->fecha_inicio }}</p>
    <p><strong>Fecha de Finalización:</strong> {{ $proyecto->fecha_fin }}</p>

    <h3>Tareas:</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha Límite</th>
                <th>Asignado A</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($proyecto->tareas as $tarea)
                <tr>
                    <td>{{ $tarea->id }}</td>
                    <td>{{ $tarea->titulo }}</td>
                    <td>{{ $tarea->estado }}</td>
                    <td>{{ $tarea->prioridad }}</td>
                    <td>{{ $tarea->fecha_limite }}</td>
                    <td>{{ $tarea->user->name ?? 'Sin asignar' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>