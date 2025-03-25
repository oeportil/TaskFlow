<x-app-layout>
    <div class="container px-4 mx-auto my-6">
        <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-lg">
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
                    <button type="submit" class="px-4 py-2 mt-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Guardar
                    </button>
                </form>
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
                    <button type="submit" class="px-4 py-2 mt-2 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Cambiar Asignado
                    </button>
                </form>
            @endif

            <a href="{{ route('proyecto.show', $tarea->proyecto->id) }}"
               class="block px-4 py-2 mt-6 font-bold text-center text-white bg-gray-600 rounded-lg hover:bg-gray-700">
                Ver Proyecto
            </a>

            <div class="mt-8">
                <h3 class="text-lg font-semibold">Checklist</h3>
                @foreach($tarea->checklists as $item)
                    <div class="flex justify-between items-center py-2">
                        <form action="{{ route('tarea.checklist.actualizar', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" onchange="this.form.submit()" name="completado" value="1" {{ $item->valor ? 'checked' : '' }}>
                            <span>{{ $item->item }}</span>
                        </form>
                        @if($esAdmin || $esAsignado)
                            <form action="{{ route('tarea.checklist.eliminar', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Eliminar</button>
                            </form>
                        @endif
                    </div>
                @endforeach
                <form action="{{ route('tarea.checklist.agregar', $tarea->id) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="text" name="item" class="w-full p-2 border rounded" placeholder="Añadir nuevo ítem">
                    <button type="submit" class="px-4 py-2 mt-2 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Añadir
                    </button>
                </form>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold">Comentarios</h3>
                <form action="{{ route('tarea.comentario.agregar', $tarea->id) }}" method="POST" class="mt-4">
                    @csrf
                    <textarea name="contenido" class="w-full p-2 border rounded" rows="4" placeholder="Escribe un comentario..."></textarea>
                    <button type="submit" class="px-4 py-2 mt-2 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700">Comentar</button>
                </form>

                <div class="mt-4">
                    @foreach($tarea->comentarios as $comentario)
                        <div class="border-b py-2">
                            <p class="font-semibold">{{ $comentario->usuario->name . ' ı ' . $comentario->usuario->email }}</p>
                            <p class="text-gray-600">{{ $comentario->comentario }}</p>
                            <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
