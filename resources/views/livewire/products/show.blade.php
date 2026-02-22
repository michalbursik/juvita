<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('warehouses.show', $warehouse->id) }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">{{ $product->name }} <span class="text-gray-400 font-medium ml-2">v {{ $warehouse->name }}</span></h2>
    </div>

    <x-card no-padding class="mb-8">
        <x-slot name="header">
            <h3 class="text-lg font-bold text-gray-800">Historie pohybů produktu</h3>
        </x-slot>

        <x-table>
            <x-slot name="header">
                <th class="px-6 py-4 text-center">Typ</th>
                <th class="px-6 py-4 text-right">Počet</th>
                <th class="px-6 py-4 text-right">Cena</th>
                <th class="px-6 py-4">Sklad (odkud/kam)</th>
                <th class="px-6 py-4">Uživatel</th>
                <th class="px-6 py-4">Datum</th>
            </x-slot>

            @forelse($movements as $m)
                @php
                    $rowClass = match($m->type) {
                        'receipt' => 'bg-emerald-50/30',
                        'issue' => 'bg-rose-50/30',
                        'transmission' => 'bg-amber-50/30',
                        'check' => 'bg-blue-50/30',
                        default => ''
                    };
                    $badgeClass = match($m->type) {
                        'receipt' => 'bg-emerald-100 text-emerald-700',
                        'issue' => 'bg-rose-100 text-rose-700',
                        'transmission' => 'bg-amber-100 text-amber-700',
                        'check' => 'bg-blue-100 text-blue-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <tr class="{{ $rowClass }} hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                            {{ $m->translated_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono font-bold">{{ $m->amount }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($m->price, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($m->type === 'transmission')
                            {{ $m->issueWarehouse->name }} <i class="bi bi-arrow-right mx-1 text-gray-300"></i> {{ $m->receiptWarehouse->name }}
                        @elseif($m->receipt_warehouse_id === $warehouse->id)
                            Příjem na tento sklad
                        @else
                            Výdej z tohoto skladu
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $m->user->name }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $m->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Žádné pohyby pro tento produkt v tomto skladu</td>
                </tr>
            @endforelse
        </x-table>
    </x-card>

    <div class="mt-6">
        {{ $movements->links() }}
    </div>
</div>
