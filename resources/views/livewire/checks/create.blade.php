<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('checks.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Nová kontrola</h2>
    </div>

    <x-card class="mb-8">
        <div class="max-w-md">
            <x-label for="warehouse_id" value="Sklad" />
            <x-select wire:model.live="warehouseId" class="w-full text-lg font-bold">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </x-select>
        </div>
    </x-card>

    @if($warehouseId)
        <form wire:submit.prevent="submit">
            <x-card no-padding class="mb-8">
                <x-table>
                    <x-slot name="header">
                        <th class="px-6 py-4">Produkt</th>
                        <th class="px-6 py-4 text-right">Cena</th>
                        <th class="px-6 py-4 text-right">Skladem (systém)</th>
                        <th class="px-6 py-4" style="width: 250px;">Skladem (fyzicky)</th>
                    </x-slot>

                    @foreach($priceLevels as $pl)
                        <tr class="hover:bg-gray-50 transition-colors align-middle">
                            <td class="px-6 py-4 font-bold text-gray-900 text-lg">{{ $pl->product->name }}</td>
                            <td class="px-6 py-4 text-right text-gray-600 font-medium">{{ number_format($pl->price, 2, ',', ' ') }} Kč</td>
                            <td class="px-6 py-4 text-right text-gray-500">{{ $pl->amount }} {{ $pl->product->unit }}</td>
                            <td class="px-6 py-4">
                                <div class="relative flex items-center">
                                    <x-input type="number"
                                           step="0.1"
                                           class="w-full pl-4 pr-12 py-3 text-lg font-mono font-bold"
                                           wire:model.defer="chosenProducts.{{ $pl->id }}"
                                           placeholder="0.0"
                                    />
                                    <span class="absolute right-4 text-gray-400 font-bold uppercase text-xs">{{ $pl->product->unit }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @php $discount = $this->getDiscountAmount(); @endphp
                    @if($discount != 0)
                        <tr class="bg-rose-50/50">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-rose-600 uppercase">Sleva</td>
                            <td class="px-6 py-4 text-lg font-black text-rose-600">{{ number_format($discount, 2, ',', ' ') }} Kč</td>
                        </tr>
                    @endif
                </x-table>
            </x-card>

            <x-input-error for="submit" class="mb-8 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-md" />

            <div class="flex justify-end">
                <x-button type="submit" loading="submit">
                    Uložit kontrolu
                </x-button>
            </div>
        </form>
    @endif
</div>
