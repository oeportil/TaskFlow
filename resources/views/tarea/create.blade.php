<x-app-layout>
    <div class="container mx-auto px-4">
        <x-slot name="header">
            <h2 class="text-2xl font-bold text-gray-800">Crear Nueva Tarea</h2>
            <p class="mt-2 text-gray-600">Asignar una nueva tarea al proyecto <strong>{{ $proyecto->nombre }}</strong></p>
        </x-slot>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 my-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <strong>{{session('error')}}</strong>
                    </div>
                @endif

                <form action="{{ route('tarea.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="proyecto_id" value="{{ $proyecto->id }}">

                    <div class="mb-4">
                        <label for="titulo" class="block text-gray-700 font-bold mb-2">Título</label>
                        <input type="text" name="titulo" id="titulo" class="w-full p-2 border border-gray-300 rounded-lg @error('titulo') border-red-500 @enderror" value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="w-full p-2 border border-gray-300 rounded-lg @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="prioridad" class="block text-gray-700 font-bold mb-2">Prioridad</label>
                        <select name="prioridad" id="prioridad" class="w-full p-2 border border-gray-300 rounded-lg @error('prioridad') border-red-500 @enderror">
                            <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                        @error('prioridad')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="fecha_limite" class="block text-gray-700 font-bold mb-2">Fecha Límite</label>
                        <input type="date" name="fecha_limite" id="fecha_limite" class="w-full p-2 border border-gray-300 rounded-lg @error('fecha_limite') border-red-500 @enderror" value="{{ old('fecha_limite') }}" required>
                        @error('fecha_limite')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-700 font-bold mb-2">Asignar a</label>
                        <select name="user_id" id="user_id" class="w-full p-2 border border-gray-300 rounded-lg @error('user_id') border-red-500 @enderror" required>
                            <option value="" disabled selected>Selecciona un usuario</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('proyecto.show', $proyecto->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Guardar Tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
