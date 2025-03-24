<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex justify-end p-5">
                    <a class="p-2 text-sm font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700" href="{{route('proyecto.create')}}">
                        Crear Proyecto
                    </a>
                </div>
                @if (session()->has('mensaje'))
                    <div class="p-2 m-3 text-sm font-bold text-green-600 uppercase bg-green-100 border border-green-600">
                        {{session('mensaje')}}
                    </div>
                @endif
                <div class="p-6 space-y-2 text-gray-900">
                    @forelse ($proyectos as $proyecto)
                        <div class="flex items-center justify-between p-5 rounded-md shadow shadow-slate-300 bg-slate-100">
                            <div>
                                <a class="text-xl font-bold cursor-pointer hover:underline">{{$proyecto->nombre}}</a>
                                <p> {{$proyecto->descripcion}}</p>
                                <div class="flex justify-start">
                                    <p class="text-xs me-5"><span class="font-bold">Inicio: </span>{{$proyecto->fecha_inicio}}</p>
                                    <p class="text-xs ms-5"><span class="font-bold">Fin: </span> {{$proyecto->fecha_fin}}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <a href="{{route('proyecto.edit', $proyecto)}}" class="w-full p-2 text-sm text-center text-white rounded-md bg-sky-700 hover:bg-sky-800">Editar</a>
                                <a href="" class="w-full p-2 text-sm text-center text-white bg-indigo-700 rounded-md hover:bg-indigo-800">Ver Tareas</a>
                                <form action="{{route('proyecto.delete', $proyecto)}}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full p-2 text-sm text-center text-white bg-red-700 rounded-md hover:bg-red-800">Eliminar</button>
                                </form>
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
</x-app-layout>
