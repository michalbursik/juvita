<div>
    <div class="flex items-center mb-8 text-center md:text-left">
        <a href="{{ route('discounts.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Upravit slevu</h2>
    </div>

    <div class="max-w-2xl mx-auto">
        <form wire:submit.prevent="submit">
            <x-card>
                <div class="space-y-6">
                    <div>
                        <x-label for="warehouseId" value="Sklad" />
                        <x-select wire:model="warehouseId" id="warehouseId" class="w-full text-lg">
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
                    <div class="flex flex-col sm:flex-row justify-between w-full gap-4">
                        <x-button type="button"
                                variant="secondary"
                                wire:click="delete"
                                wire:confirm="Opravdu smazat tuto slevu?"
                                class="text-rose-600 border-rose-200 hover:bg-rose-50">
                            Smazat slevu
                        </x-button>

                        <x-button type="submit" loading="submit">
                            Uložit změny
                        </x-button>
                    </div>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
