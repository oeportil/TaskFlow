<x-app-layout>
    <div class="container mx-auto px-4">
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-800">
                {{ $proyecto->nombre }}
            </h2>
            <p class="mt-2 text-gray-600">{{ $proyecto->descripcion }}</p>
            <div class="mt-4 text-gray-700">
                <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</p>
                <p><strong>Fecha de Finalización:</strong> {{ \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') }}</p>
            </div>
        </x-slot>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 my-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Tareas Asignadas</h2>
                    @if (auth()->user()->tipo === 'admin')
                        <a href="{{ route('tarea.create', ['proyecto_id' => $proyecto->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                            + Crear Tarea
                        </a>
                    @endif
                </div>

                {{-- Progreso del Proyecto --}}
                @php
                    $totalTareas = $proyecto->tareas->count();
                    $progresoTotal = 0;

                    foreach($proyecto->tareas as $tarea) {
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

                <div class="mb-6">
                    <p class="font-semibold text-lg text-gray-700">Progreso del Proyecto: {{ round($promedioProgreso, 2) }}%</p>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 bg-purple-600" style="width: {{ $promedioProgreso }}%"></div>
                    </div>
                </div>

                @if ($proyecto->tareas->isEmpty())
                    <p class="text-gray-500 text-center py-6">No hay tareas asignadas a este proyecto.</p>
                @else
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($proyecto->tareas as $tarea)
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
                            <div class="bg-gray-100 shadow-md rounded-lg p-4">
                                <h5 class="text-lg font-bold text-gray-700">{{ $tarea->titulo }}</h5>
                                <p class="text-gray-600 mt-2">{{ $tarea->descripcion }}</p>
                                <p class="mt-2"><strong>Estado:</strong> <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span></p>
                                <p><strong>Prioridad:</strong> <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span></p>
                                <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>
                                <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>

                                <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 bg-purple-600" style="width: {{ $porcentajeTarea }}%"></div>
                                </div>

                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('tarea.show', $tarea->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded-lg">
                                        Ver
                                    </a>

                                    @if (auth()->user()->tipo === 'admin')
                                        <form id="delete-form-{{$tarea->id}}" action="{{route('tarea.destroy', $tarea->id)}}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{$tarea->id}})" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-lg">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('proyecto.index') }}" class="block mt-6 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg w-full md:w-1/3 mx-auto">
                    Volver a la lista
                </a>
            </div>
        </div>
    </div>

    <div id="confirmation-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="p-6 bg-white rounded-lg">
            <h2 class="mb-4 text-lg font-bold text-gray-800">¿Estás seguro que quieres eliminar esta tarea?</h2>
            <div class="flex justify-end gap-4">
                <button onclick="closeModal()" class="p-2 text-sm text-gray-600 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
                <button id="confirm-delete-button" class="p-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">Eliminar</button>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(formId) {
            const modal = document.getElementById('confirmation-modal');
            modal.style.display = 'flex';

            const confirmButton = document.getElementById('confirm-delete-button');
            confirmButton.onclick = function () {
                document.getElementById(`delete-form-${formId}`).submit();
                closeModal();
            };
        }

        function closeModal() {
            const modal = document.getElementById('confirmation-modal');
            modal.style.display = 'none';
        }
    </script>
</x-app-layout>
