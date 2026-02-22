<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <h2 class="text-3xl font-extrabold text-gray-900">Slevy</h2>
        <x-button :href="route('discounts.create')" tag="a">
            <i class="bi bi-plus-lg mr-2"></i> Přidat slevu
        </x-button>
    </div>

    <x-card no-padding>
        <x-table>
            <x-slot name="header">
                <th class="px-6 py-4">ID</th>
                <th class="px-6 py-4">Sklad</th>
                <th class="px-6 py-4">Uživatel</th>
                <th class="px-6 py-4 text-right">Množství</th>
                <th class="px-6 py-4">Vytvořeno</th>
                <th class="px-6 py-4 text-right">Akce</th>
            </x-slot>

            @foreach($discounts as $discount)
                <tr class="hover:bg-gray-50 transition-colors @if(auth()->user()->role === 'admin') cursor-pointer @endif"
                    @if(auth()->user()->role === 'admin') onclick="window.location.href='{{ route('discounts.edit', $discount->id) }}'" @endif>
                    <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ substr($discount->id, 0, 8) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $discount->warehouse->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $discount->user->name }}</td>
                    <td class="px-6 py-4 text-right font-black text-rose-600">-{{ number_format($discount->amount, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap text-sm">{{ $discount->created_at }}</td>
                    <td class="px-6 py-4 text-right">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('discounts.edit', $discount->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Upravit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>
</div>
