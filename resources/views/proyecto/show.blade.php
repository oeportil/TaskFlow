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
                    <a href="{{ route('tarea.create', ['proyecto_id' => $proyecto->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                        + Crear Tarea
                    </a>
                </div>

                @if ($proyecto->tareas->isEmpty())
                    <p class="text-gray-500 text-center py-6">No hay tareas asignadas a este proyecto.</p>
                @else
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($proyecto->tareas as $tarea)
                            <div class="bg-gray-100 shadow-md rounded-lg p-4">
                                <h5 class="text-lg font-bold text-gray-700">{{ $tarea->titulo }}</h5>
                                <p class="text-gray-600 mt-2">{{ $tarea->descripcion }}</p>
                                <p class="mt-2"><strong>Estado:</strong> <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span></p>
                                <p><strong>Prioridad:</strong> <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span></p>
                                <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>
                                <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>
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
</x-app-layout>
