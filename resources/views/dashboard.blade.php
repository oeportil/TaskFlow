<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   <h3 class="text-xl font-bold text-center">Tareas</h3>

                   <form class="flex gap-2 ">
                    @csrf
                        <div class="w-full mb-4">
                            <label for="prioridad" class="block mb-2 font-bold text-gray-700">Prioridad</label>
                            <select name="prioridad" id="prioridad" class="block w-full p-2 mt-1 border rounded">
                                <option value="todos">Todas las Prioridades</option>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>

                        <div class="w-full mb-4">
                            <label for="estado" class="block mb-2 font-bold text-gray-700">Estado</label>
                            <select name="estado" id="estado" class="block w-full p-2 mt-1 border rounded">
                                <option value="Todos">Todos los Estados</option>
                                <option value="Pendiente" >Pendiente</option>
                                <option value="En Progreso" >En Progreso</option>
                                <option value="Completada">Completada</option>
                            </select>
                        </div>

                        <div class="w-full mb-4">
                            <label for="estado" class="block mb-2 font-bold text-gray-700">Usuarios</label>
                            <select name="estado" id="estado" class="block w-full p-2 mt-1 border rounded">
                                <option value="0">Todos los usuarios</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>

                   </form>

                    <div class="mt-5 space-y-2">
                        @forelse ($tareas as $tarea)
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
                    <div class="p-4 my-5 bg-gray-100 rounded-lg shadow-md">
                        <h5 class="text-lg font-bold text-gray-700">{{ $tarea->titulo }}</h5>
                        <p class="mt-2 text-gray-600">{{ $tarea->descripcion }}</p>
                        <p class="mt-2"><strong>Estado:</strong> <span class="text-blue-600">{{ ucfirst($tarea->estado) }}</span></p>
                        <p><strong>Prioridad:</strong> <span class="text-red-600">{{ ucfirst($tarea->prioridad) }}</span></p>
                        <p><strong>Fecha Límite:</strong> {{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</p>
                        <p><strong>Asignado a:</strong> {{ $tarea->user->name ?? 'Sin asignar' }}</p>

                        <div class="w-full h-2 mt-4 bg-gray-200 rounded-full">
                            <div class="h-2 bg-purple-600" style="width: {{ $porcentajeTarea }}%"></div>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('tarea.show', $tarea->id) }}" class="px-3 py-1 font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                Ver
                            </a>

                            @if (auth()->user()->tipo === 'admin')
                                <form id="delete-form-{{$tarea->id}}" action="{{route('tarea.destroy', $tarea->id)}}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{$tarea->id}})" class="px-3 py-1 font-bold text-white bg-red-600 rounded-lg hover:bg-red-700">Eliminar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    </div>
                   @empty
                   <p class="p-3 text-sm text-center text-gray-600">No hay Tareas Para Mostrar</p>
                   @endforelse
                </div>
            </div>
            <div>
                {{$tareas->links()}}
            </div>
        </div>
    </div>
</x-app-layout>
