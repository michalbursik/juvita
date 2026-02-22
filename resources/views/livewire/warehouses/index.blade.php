<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Sklady</h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($warehouses as $warehouse)
            @if (!$warehouse->type->isTrash())
                <x-card no-padding class="relative group hover:shadow-md transition-shadow duration-200">
                    <a href="{{ route('warehouses.show', $warehouse->id) }}" class="flex items-center justify-center h-32 p-4 text-center">
                        <span class="text-lg font-bold text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">
                            {{ $warehouse->name }}
                        </span>
                    </a>

                    <div class="absolute bottom-2 right-2" x-data="{ open: false }">
                        <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-100 transition-colors duration-200">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 bottom-full mb-2 w-32 bg-white rounded shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <div class="py-1">
                                <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Upravit</a>
                                <button wire:click="deleteWarehouse('{{ $warehouse->id }}')"
                                        wire:confirm="Opravdu smazat tento sklad?"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Smazat
                                </button>
                            </div>
                        </div>
                    </div>
                </x-card>
            @endif
        @endforeach

        <a href="{{ route('warehouses.create') }}" class="flex items-center justify-center h-32 p-4 border-2 border-dashed border-gray-300 rounded text-gray-400 hover:border-indigo-400 hover:text-indigo-500 transition-all duration-200">
            <span class="text-4xl font-light">+</span>
        </a>
    </div>
</div>
