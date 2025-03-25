<x-app-layout>
    <div class="container mx-auto px-4">
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-800">Lista de Usuarios</h2>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-6 mx-24">
            @foreach ($usuarios as $usuario)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-700">{{ $usuario->name }}</h3>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $usuario->email }}</p>
                    <p class="text-gray-600"><strong>Tipo:</strong> {{ ucfirst($usuario->tipo) }}</p>

                    <div class="mt-4 flex space-x-2">
                        @if (auth()->user()->tipo === 'admin' && auth()->id() !== $usuario->id)
                            <form action="{{ route('usuario.cambiarTipo', $usuario->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                    Cambiar a {{ $usuario->tipo === 'admin' ? 'Cliente' : 'Admin' }}
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('usuario.tareas', $usuario->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Ver Tareas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
