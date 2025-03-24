<div class="flex flex-col items-center p-4">
    <form action="" class="space-y-5 md:w-1/2" wire:submit.prevent="crearProyecto">
        <div class="mt-4">
            <x-input-label for="nombre" :value="('Nombre')"/>
            <x-text-input
            id="nombre"
            class="block w-full mt-1"
            type="text"
            wire:model='nombre'
            value="{{old('nombre')}}"
            placeholder="Nombre proyecto"/>
            @error('nombre')
                <p class="text-xs text-center text-red-500 uppercase">{{$message}}</p>
            @enderror
        </div>

        <div class="mt-4">
            <x-input-label for="descripcion" :value="('Descripcion')"/>
            <textarea wire:model="descripcion" id="" placeholder="Descripcion general de puesto, experiencia"
            class="w-full h-40 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            {{old('descripcion')}}</textarea>
            @error('descripcion')
            <p class="text-xs text-center text-red-500 uppercase">{{$message}}</p>
            @enderror
        </div>


        <div class="mt-4">
            <x-input-label for="fecha_inicio" :value="('Fecha de Inicio')"/>
            <x-text-input
            id="fecha_inicio"
            class="block w-full mt-1"
            type="date"
            wire:model='fecha_inicio'
            value="{{old('fecha_inicio')}}"/>
            @error('fecha_inicio')
                <p class="text-xs text-center text-red-500 uppercase">{{$message}}</p>
            @enderror
        </div>


        <div class="mt-4">
            <x-input-label for="fecha_fin" :value="('Fecha de Fin')"/>
            <x-text-input
            id="fecha_fin"
            class="block w-full mt-1"
            type="date"
            wire:model='fecha_fin'
            value="{{old('fecha_fin')}}"/>
            @error('fecha_fin')
                <p class="text-xs text-center text-red-500 uppercase">{{$message}}</p>
            @enderror
        </div>

        <div>
            <x-primary-button>
                Crear Proyecto
            </x-primary-button>
        </div>
    </form>
</div>
