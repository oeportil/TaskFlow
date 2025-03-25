<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                @if (auth()->user()->tipo === 'admin')
                    <div class="flex justify-end p-5">
                        <a class="p-2 text-sm font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700" href="{{route('proyecto.create')}}">
                            Crear Proyecto
                        </a>
                    </div>
                @endif
                @if (session()->has('mensaje'))
                    <div class="p-2 m-3 text-sm font-bold text-green-600 uppercase bg-green-100 border border-green-600">
                        {{session('mensaje')}}
                    </div>
                @endif
                <div class="p-6 space-y-2 text-gray-900">
                    @forelse ($proyectos as $proyecto)
                        <div class="flex items-center justify-between p-5 rounded-md shadow shadow-slate-300 bg-slate-100">
                            <div>
                                <a class="text-xl font-bold cursor-pointer hover:underline" href="{{route('proyecto.show', $proyecto)}}">{{$proyecto->nombre}}</a>
                                <p> {{$proyecto->descripcion}}</p>
                                <div class="flex justify-start">
                                    <p class="text-xs me-5"><span class="font-bold">Inicio: </span>{{$proyecto->fecha_inicio}}</p>
                                    <p class="text-xs ms-5"><span class="font-bold">Fin: </span> {{$proyecto->fecha_fin}}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                @if (auth()->user()->tipo === 'admin')
                                    <a href="{{route('proyecto.edit', $proyecto)}}" class="w-full p-2 text-sm text-center text-white rounded-md bg-sky-700 hover:bg-sky-800">Editar</a>
                                @endif
                                <a href="{{route('proyecto.show', $proyecto)}}" class="w-full p-2 text-sm text-center text-white bg-indigo-700 rounded-md hover:bg-indigo-800">Ver Tareas</a>
                                @if (auth()->user()->tipo === 'admin')
                                    <form id="delete-form-{{$proyecto->id}}" action="{{route('proyecto.delete', $proyecto)}}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{$proyecto->id}})" class="w-full p-2 text-sm text-center text-white bg-red-700 rounded-md hover:bg-red-800">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                    <p class="p-3 text-sm text-center text-gray-600">No hay Proyectos Para Mostrar</p>
                    @endforelse
                </div>
                <div class="m-10">
                    {{$proyectos->links()}}
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->tipo === 'admin')
        <div id="confirmation-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="p-6 bg-white rounded-lg">
                <h2 class="mb-4 text-lg font-bold text-gray-800">¿Estás seguro que quieres eliminar este proyecto?</h2>
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
    @endif
</x-app-layout>
