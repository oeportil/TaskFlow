<x-app-layout>
    <div class="container mx-auto px-4">
        @php
            $totalTareas = $user->tareas->count();
            $progresoTotal = 0;

            foreach($user->tareas as $tarea) {
                switch ($tarea->estado) {
                    case 'Pendiente':
                        $progresoTotal += 0;
                        break;
                    case 'En Progreso':
                        $progresoTotal += 50;
                        break;
                    case 'Completada':
                        $progresoTotal += 100;
                        break;
                }
            }

            $promedioProgreso = $totalTareas ? $progresoTotal / $totalTareas : 0;
        @endphp
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-800">Tareas de {{ $user->name }}</h2>
            <br>
            <div class="mb-6">
                <p class="font-semibold text-lg text-gray-700">Progreso Total: {{ round($promedioProgreso, 2) }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 bg-purple-600" style="width: {{ $promedioProgreso }}%"></div>
                </div>
            </div>

            <div class="mb-6">
                <form action="{{ route('usuario.tareas', $user->id) }}" method="GET" class="flex flex-wrap gap-4">
                    <select name="estado" class="p-2 border rounded">
                        <option value="">Estado</option>
                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Progreso" {{ request('estado') == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                        <option value="Completada" {{ request('estado') == 'Completada' ? 'selected' : '' }}>Completada</option>
                    </select>

                    <select name="prioridad" class="p-2 border rounded px-8 text-start ps-4">
                        <option value="">Prioridad</option>
                        <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                        <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                        <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    </select>

                    <input type="text" name="proyecto" value="{{ request('proyecto') }}" placeholder="Nombre del proyecto" class="p-2 border rounded">

                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded">Filtrar</button>
                </form>
            </div>
        </x-slot>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 my-6">
            @if($tareas->isEmpty())
                <p class="text-gray-600">Este usuario no tiene tareas asignadas.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($tareas as $tarea)
                        @php
                            $porcentajeTarea = 0;
                            switch ($tarea->estado) {
                                case 'Pendiente':
                                    $porcentajeTarea = 0;
                                    break;
                                case 'En Progreso':
                                    $porcentajeTarea = 50;
                                    break;
                                case 'Completada':
                                    $porcentajeTarea = 100;
                                    break;
                            }
                        @endphp
                        <div class="bg-white shadow-sm rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-700">{{ $tarea->titulo }}</h3>
                            <p class="text-gray-600">{{ $tarea->descripcion }}</p>
                            <p class="text-gray-600"><strong>Estado:</strong> {{ $tarea->estado }}</p>
                            <p class="text-gray-600"><strong>Prioridad:</strong> {{ ucfirst($tarea->prioridad) }}</p>
                            <p class="text-gray-600"><strong>Proyecto:</strong> {{ $tarea->proyecto->nombre }}</p>
                            <p class="text-gray-600"><strong>Fecha Límite:</strong> {{ $tarea->fecha_limite }}</p>

                            {{-- Barra de progreso individual --}}
                            <div class="mt-2">
                                <p class="text-sm font-semibold text-gray-700">Progreso: {{ $porcentajeTarea }}%</p>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 bg-purple-600" style="width: {{ $porcentajeTarea }}%"></div>
                                </div>
                            </div>

                            <a href="{{ route('tarea.show', $tarea->id) }}" class="text-blue-600 hover:underline mt-2 block">Ver detalles</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
