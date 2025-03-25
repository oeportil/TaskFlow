<x-app-layout>
    <div class="container mx-auto px-4 my-6">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $tarea->titulo }}</h2>
            <p class="mt-2 text-gray-600">{{ $tarea->descripcion }}</p>

            <div class="mt-4 text-gray-700">
                <p><strong>Estado:</strong> <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span></p>
                <p><strong>Prioridad:</strong> <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span></p>
                <p><strong>Fecha límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>
                <p><strong>Proyecto:</strong> {{ $tarea->proyecto->nombre }}</p>
                <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>
            </div>

            <a href="{{ route('proyecto.show', $tarea->proyecto->id) }}" 
               class="block mt-6 text-center bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                Volver al Proyecto
            </a>
        </div>
    </div>
</x-app-layout>
