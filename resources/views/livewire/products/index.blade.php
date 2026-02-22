<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <h2 class="text-3xl font-extrabold text-gray-900">Správa produktů</h2>
        <x-button :href="route('products.create')" tag="a">
            <i class="bi bi-plus-lg mr-2"></i> Přidat produkt
        </x-button>
    </div>

    <x-card no-padding>
        <x-table>
            <x-slot name="header">
                <th class="px-6 py-4">Pořadí</th>
                <th class="px-6 py-4">Foto</th>
                <th class="px-6 py-4">Název</th>
                <th class="px-6 py-4">Původ</th>
                <th class="px-6 py-4">Jednotka</th>
                <th class="px-6 py-4">Aktivní</th>
                <th class="px-6 py-4 text-right">Akce</th>
            </x-slot>

            @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-500 font-mono">{{ $product->order }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/50' }}"
                             alt="{{ $product->name }}"
                             class="h-10 w-10 rounded object-cover bg-gray-100">
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $product->origin }}</td>
                    <td class="px-6 py-4 uppercase text-xs font-bold text-gray-400">{{ $product->unit }}</td>
                    <td class="px-6 py-4">
                        @if($product->active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Ano</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">Ne</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Upravit</a>
                            <button wire:click="deleteProduct('{{ $product->id }}')"
                                    wire:confirm="Opravdu smazat tento produkt?"
                                    class="text-rose-600 hover:text-rose-900 font-medium">
                                Smazat
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
