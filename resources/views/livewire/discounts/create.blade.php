<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('discounts.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Nová sleva</h2>
    </div>

    <div class="max-w-2xl mx-auto">
        <form wire:submit.prevent="submit">
            <x-card>
                <div class="space-y-6">
                    <div>
                        <x-label for="warehouseId" value="Sklad" />
                        <x-select wire:model="warehouseId" id="warehouseId" class="w-full text-lg">
                            <option value="">Vyberte sklad</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="warehouseId" />
                    </div>

                    <div>
                        <x-label for="amount" value="Množství (Kč)" />
                        <div class="relative flex items-center">
                            <x-input type="number"
                                   step="0.01"
                                   wire:model="amount"
                                   id="amount"
                                   class="w-full text-2xl font-black p-4 pr-12"
                                   placeholder="0.00"
                            />
                            <span class="absolute right-4 text-gray-400 font-bold uppercase">Kč</span>
                        </div>
                        <x-input-error for="amount" />
                    </div>
                </div>

                <x-slot name="footer">
                    <x-button type="submit" loading="submit">
                        Uložit slevu
                    </x-button>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
