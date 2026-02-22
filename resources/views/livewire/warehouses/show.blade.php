<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div class="flex items-center">
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('warehouses.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
                    <i class="bi bi-arrow-left"></i>
                </a>
            @endif
            <h2 class="text-3xl font-extrabold text-gray-900">{{ $warehouse->name }}</h2>
        </div>

        @if (auth()->user()->role === 'admin')
            <div class="w-full md:w-64">
                <x-select wire:change="changeWarehouse($event.target.value)" class="w-full">
                    <option disabled selected>Přepnout sklad</option>
                    @foreach ($allWarehouses as $wh)
                        @if ($wh->id !== $warehouse->id)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endif
                    @endforeach
                </x-select>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-12">
        @foreach ($warehouse->products as $product)
            <div class="flex flex-col">
                <div class="relative bg-white rounded-t shadow-sm border border-gray-200 overflow-hidden flex-1 flex flex-col" style="min-height: 200px;">
                    <!-- Product Image Background -->
                    <div class="absolute inset-0 opacity-20 pointer-events-none bg-center bg-no-repeat bg-contain" style="background-image: url('{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/150' }}');"></div>

                    <a href="{{ route('warehouses.products.show', [$warehouse->id, $product->id]) }}" class="relative flex-1 flex flex-col p-4 text-center z-10">
                        <span class="text-lg font-bold text-gray-800 mb-1 leading-tight">{{ $product->name }}</span>
                        <span class="text-2xl font-black text-indigo-600 mt-auto">
                            {{ $movementAmounts[$product->id] ?? 0 }} <span class="text-sm font-normal text-gray-500 uppercase">{{ $product->unit }}</span>
                        </span>
                    </a>
                </div>

                <!-- Quick Actions -->
                <div class="flex shadow-sm rounded-b overflow-hidden border-x border-b border-gray-200">
                    @if(auth()->user()->role === 'admin' && $warehouse->type->isMain())
                        <a href="{{ route('warehouses.products.receipt', [$warehouse->id, $product->id]) }}" class="flex-1 py-2 flex justify-center items-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors border-r border-gray-100" title="Příjem">
                            <i class="bi bi-plus-lg font-bold"></i>
                        </a>
                    @endif
                    <a href="{{ route('warehouses.products.transmission', [$warehouse->id, $product->id]) }}" class="flex-1 py-2 flex justify-center items-center bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors border-r border-gray-100" title="Převod">
                        <i class="bi bi-arrow-left-right"></i>
                    </a>
                    <a href="{{ route('warehouses.products.issue', [$warehouse->id, $product->id]) }}" class="flex-1 py-2 flex justify-center items-center bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Odpad">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Movements -->
    <x-card no-padding>
        <x-slot name="header">
            <h3 class="text-lg font-bold text-gray-800">Pohyby za poslední týden</h3>
        </x-slot>

        <x-table>
            <x-slot name="header">
                <th class="px-6 py-3">Produkt</th>
                <th class="px-6 py-3 text-center">Typ</th>
                <th class="px-6 py-3 text-right">Počet</th>
                <th class="px-6 py-3 text-right">Cena</th>
                <th class="px-6 py-3">Uživatel</th>
                <th class="px-6 py-3">Vytvořeno</th>
            </x-slot>

            @forelse ($warehouse->movements as $movement)
                @php
                    $rowClass = match($movement->type) {
                        'receipt' => 'bg-emerald-50/30',
                        'issue' => 'bg-rose-50/30',
                        'transmission' => 'bg-amber-50/30',
                        'check' => 'bg-blue-50/30',
                        default => ''
                    };
                    $badgeClass = match($movement->type) {
                        'receipt' => 'bg-emerald-100 text-emerald-700',
                        'issue' => 'bg-rose-100 text-rose-700',
                        'transmission' => 'bg-amber-100 text-amber-700',
                        'check' => 'bg-blue-100 text-blue-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <tr class="{{ $rowClass }} hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $movement->product->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                            {{ $movement->translated_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono">{{ $movement->amount }} {{ $movement->product->unit }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($movement->price, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-gray-600">{{ $movement->user->name }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $movement->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                        Žádné pohyby za poslední týden
                    </td>
                </tr>
            @endforelse
        </x-table>
    </x-card>
</div>
