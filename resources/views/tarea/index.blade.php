<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Listado de Tareas</h2>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('tarea.index') }}" class="bg-white shadow-md rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Buscar por tarea o proyecto" value="{{ request('search') }}" class="border p-2 rounded w-full">
                <select name="estado" class="border p-2 rounded w-full">
                    <option value="">Todos los estados</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="En Progreso" {{ request('estado') == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                    <option value="Completada" {{ request('estado') == 'Completada' ? 'selected' : '' }}>Completada</option>
                </select>
                <select name="prioridad" class="border p-2 rounded w-full">
                    <option value="">Todas las prioridades</option>
                    <option value="Baja" {{ request('prioridad') == 'Baja' ? 'selected' : '' }}>Baja</option>
                    <option value="Media" {{ request('prioridad') == 'Media' ? 'selected' : '' }}>Media</option>
                    <option value="Alta" {{ request('prioridad') == 'Alta' ? 'selected' : '' }}>Alta</option>
                </select>
                <select name="usuario" class="border p-2 rounded w-full">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('usuario') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                <a href="{{ route('tarea.index') }}" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Restablecer</a>
            </div>
        </form>

        {{-- Lista de Tareas --}}
        @if ($tareas->isEmpty())
            <p class="text-gray-500 text-center">No se encontraron tareas con los filtros aplicados.</p>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 bg-white p-5 shadow-md rounded-lg">
                @foreach($tareas as $tarea)
                    @php
                        $progreso = match ($tarea->estado) {
                            'Pendiente' => 0,
                            'En Progreso' => 50,
                            'Completada' => 100,
                            default => 0
                        };
                    @endphp

                    <div class="bg-gray-100 shadow-md rounded-lg p-4">
                        <h5 class="text-lg font-bold text-gray-700">{{ $tarea->titulo }}</h5>
                        <p class="text-gray-600 mt-2">{{ $tarea->descripcion }}</p>
                        <p><strong>Proyecto:</strong> 
                            <a href="{{ route('proyecto.show', $tarea->proyecto->id) }}" class="text-blue-600 font-semibold">{{ $tarea->proyecto->nombre }}</a>
                        </p>
                        <p><strong>Estado:</strong> <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span></p>
                        <p><strong>Prioridad:</strong> <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span></p>
                        <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>

                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 bg-purple-600" style="width: {{ $progreso }}%"></div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('tarea.show', $tarea->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded-lg">
                                Ver
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $tareas->links() }}
            </div>
        @endif
    </div>
</x-app-layout>