<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 sm:mb-8 gap-4">
        <h2 class="text-xl sm:text-3xl font-extrabold text-gray-900 text-center md:text-left">Přehledy</h2>

        <div class="flex flex-wrap items-center justify-center md:justify-end gap-2 sm:gap-4 bg-white p-2 rounded border border-gray-200 shadow-sm">
            <div class="flex items-center gap-2 px-3">
                <span class="text-xs font-bold text-gray-400 uppercase">Od</span>
                <x-input type="date" wire:model.live="dateFrom" class="border-0 focus:ring-0 font-bold text-gray-700 p-0" />
            </div>
            <div class="h-8 w-px bg-gray-200"></div>
            <div class="flex items-center gap-2 px-3">
                <span class="text-xs font-bold text-gray-400 uppercase">Do</span>
                <x-input type="date" wire:model.live="dateTo" class="border-0 focus:ring-0 font-bold text-gray-700 p-0" />
            </div>
        </div>
    </div>

    @foreach($data as $productId => $product)
        <x-card no-padding class="mb-8">
            <x-slot name="header">
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ $product['product_name'] }}</h3>
            </x-slot>

            <div class="md:hidden">
                @foreach($product['warehouses'] as $warehouseId => $warehouse)
                    @if(count($warehouse['price_levels']) > 0)
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-gray-700">{{ $warehouse['warehouse_name'] }}</div>
                                @php
                                    $total = collect($warehouse['price_levels'])->sum('amount');
                                @endphp
                                <span class="text-lg font-black {{ $total > 0 ? 'text-emerald-700' : ($total < 0 ? 'text-rose-700' : 'text-gray-400') }}">
                                    {{ $total > 0 ? '+' : '' }}{{ $total }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($warehouse['price_levels'] as $pl)
                                    @if($pl['amount'] != 0)
                                        <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded border border-gray-100">
                                            <span class="text-[10px] font-bold text-gray-500">{{ number_format($pl['price'], 2, ',', ' ') }} Kč</span>
                                            <span class="text-xs font-mono font-bold {{ $pl['amount'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $pl['amount'] > 0 ? '+' : '' }}{{ $pl['amount'] }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <x-table class="hidden md:table">
                <x-slot name="header">
                    <th class="px-6 py-3">Sklad</th>
                    <th class="px-6 py-3 text-right">Cenové hladiny</th>
                    <th class="px-6 py-3 text-right">Celkem</th>
                </x-slot>

                @foreach($product['warehouses'] as $warehouseId => $warehouse)
                    @if(count($warehouse['price_levels']) > 0)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $warehouse['warehouse_name'] }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col gap-1 items-end">
                                    @foreach($warehouse['price_levels'] as $pl)
                                        @if($pl['amount'] != 0)
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold px-2 py-0.5 bg-gray-100 text-gray-600 rounded">{{ number_format($pl['price'], 2, ',', ' ') }} Kč</span>
                                                <span class="font-mono font-bold {{ $pl['amount'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $pl['amount'] > 0 ? '+' : '' }}{{ $pl['amount'] }}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $total = collect($warehouse['price_levels'])->sum('amount');
                                @endphp
                                <span class="text-lg font-black {{ $total > 0 ? 'text-emerald-700' : ($total < 0 ? 'text-rose-700' : 'text-gray-400') }}">
                                    {{ $total > 0 ? '+' : '' }}{{ $total }}
                                </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </x-table>
        </x-card>
    @endforeach
</div>
