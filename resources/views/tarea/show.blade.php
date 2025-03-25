<x-app-layout>
    <div class="container mx-auto px-4 my-6">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $tarea->titulo }}</h2>
            <p class="mt-2 text-gray-600">{{ $tarea->descripcion }}</p>

            <div class="mt-4 text-gray-700">
                <p><strong>Estado:</strong> 
                    <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span>
                </p>
                <p><strong>Prioridad:</strong> 
                    <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span>
                </p>
                <p><strong>Fecha límite:</strong> 
                    {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}
                </p>
                <p><strong>Proyecto:</strong> {{ $tarea->proyecto->nombre }}</p>
                <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>
            </div>

            @php
                $esAdmin = auth()->user()->tipo === 'admin';
                $esAsignado = auth()->user()->id === optional($tarea->user)->id;
                $fechaVencida = \Carbon\Carbon::now()->greaterThan($tarea->fecha_limite);
                $esCompletada = $tarea->estado === 'Completada';
            @endphp

            @if(($esAdmin || $esAsignado) && !$esCompletada && !$fechaVencida)
                <form action="{{ route('tarea.actualizarEstado', $tarea->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <label for="estado" class="block font-bold text-gray-700">Actualizar Estado:</label>
                    <select name="estado" id="estado" class="block w-full p-2 mt-1 border rounded">
                        <option value="Pendiente" {{ $tarea->estado === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="En Progreso" {{ $tarea->estado === 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                        <option value="Completada" {{ $tarea->estado === 'Completada' ? 'selected' : '' }}>Completada</option>
                    </select>
                    <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Guardar
                    </button>
                </form>
            @else
                <p class="mt-4 text-sm text-gray-500">
                    No puedes cambiar el estado porque la tarea ya está completada o la fecha límite ha vencido.
                </p>
            @endif

            @if($esAdmin)
                <form action="{{ route('tarea.actualizarAsignado', $tarea->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <label for="asignado" class="block font-bold text-gray-700">Asignar a:</label>
                    <select name="asignado" id="asignado" class="block w-full p-2 mt-1 border rounded">
                        <option value="">Sin asignar</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ optional($tarea->user)->id === $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                        Cambiar Asignado
                    </button>
                </form>
            @endif

            <a href="{{ route('proyecto.show', $tarea->proyecto->id) }}" 
               class="block mt-6 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                Ver Proyecto
            </a>
        </div>
    </div>
</x-app-layout>
