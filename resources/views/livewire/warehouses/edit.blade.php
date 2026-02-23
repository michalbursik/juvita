<div>
    <div class="flex items-center mb-8 text-center md:text-left">
        <a href="{{ route('warehouses.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Upravit sklad: {{ $warehouse->name }}</h2>
    </div>

    <div class="max-w-2xl mx-auto">
        <form wire:submit.prevent="submit">
            <x-card class="overflow-hidden">
                <div class="space-y-6">
                    <div>
                        <x-label for="name" value="Název skladu" />
                        <x-input type="text" wire:model="name" id="name" class="w-full text-lg font-bold" />
                        <x-input-error for="name" />
                    </div>

                    <div>
                        <x-label for="type" value="Typ skladu" />
                        <x-select wire:model="type" id="type" class="w-full font-bold uppercase text-xs tracking-widest">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="type" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-input type="checkbox" wire:model="active" id="active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <x-label for="active" value="Aktivní" class="mb-0" />
                        <x-input-error for="active" />
                    </div>
                </div>

                <x-slot name="footer">
                    <x-button type="submit" loading="submit">
                        Uložit změny
                    </x-button>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
