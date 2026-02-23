<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <h2 class="text-3xl font-extrabold text-gray-900">Přehled všech kontrol</h2>
        <x-button :href="route('checks.create')" tag="a">
            <i class="bi bi-plus-lg mr-2"></i> Nová kontrola
        </x-button>
    </div>

    <x-card no-padding>
        <x-table>
            <x-slot name="header">
                <th class="px-6 py-4">ID</th>
                <th class="px-6 py-4">Sklad</th>
                <th class="px-6 py-4">Datum</th>
                <th class="px-6 py-4">Kontrolor</th>
                <th class="px-6 py-4 text-right">Akce</th>
            </x-slot>

            @foreach($checks as $check)
                <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('checks.show', $check->id) }}'">
                    <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ substr($check->id, 0, 8) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $check->warehouse->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $check->created_at->format('d. m. Y H:i:s') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $check->user->name }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('checks.show', $check->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <div class="mt-6">
        {{ $checks->links() }}
    </div>
</div>
