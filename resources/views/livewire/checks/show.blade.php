<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('checks.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Detail kontroly <span class="text-gray-400">#{{ substr($check->id, 0, 8) }}</span></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-card class="p-6">
            <x-label value="Sklad" class="text-xs text-gray-400 mb-1" />
            <div class="text-xl font-bold text-gray-800">{{ $check->warehouse->name }}</div>
        </x-card>
        <x-card class="p-6">
            <x-label value="Datum" class="text-xs text-gray-400 mb-1" />
            <div class="text-xl font-bold text-gray-800">{{ $check->created_at }}</div>
        </x-card>
        <x-card class="p-6">
            <x-label value="Kontroloval" class="text-xs text-gray-400 mb-1" />
            <div class="text-xl font-bold text-gray-800">{{ $check->user->name }}</div>
        </x-card>
    </div>

    <x-card no-padding>
        <x-table>
            <x-slot name="header">
                <th class="px-6 py-4">Produkt</th>
                <th class="px-6 py-4 text-right">Cena</th>
                <th class="px-6 py-4 text-right">Původní stav</th>
                <th class="px-6 py-4 text-right">Nový stav</th>
                <th class="px-6 py-4 text-right">Rozdíl</th>
            </x-slot>

            @foreach($check->products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900 text-lg">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-right font-medium text-gray-600">{{ number_format($product->product_check->price, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-right text-gray-500">{{ $product->product_check->amount_before }} {{ $product->unit }}</td>
                    <td class="px-6 py-4 text-right text-gray-900 font-bold">{{ $product->product_check->amount_after }} {{ $product->unit }}</td>
                    @php $diff = $product->product_check->amount_after - $product->product_check->amount_before; @endphp
                    <td class="px-6 py-4 text-right font-black">
                        @if($diff > 0)
                            <span class="text-emerald-600">+{{ $diff }} {{ $product->unit }}</span>
                        @elseif($diff < 0)
                            <span class="text-rose-600">{{ $diff }} {{ $product->unit }}</span>
                        @else
                            <span class="text-gray-400">0</span>
                        @endif
                    </td>
                </tr>
            @endforeach

            @if($check->discount != 0)
                <tr class="bg-rose-50/50">
                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-rose-600 uppercase">Sleva</td>
                    <td class="px-6 py-4 text-right text-lg font-black text-rose-600">{{ number_format($check->discount, 2, ',', ' ') }} Kč</td>
                </tr>
            @endif
        </x-table>
    </x-card>
</div>
