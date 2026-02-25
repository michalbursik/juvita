<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 sm:mb-8 gap-4">
        <h2 class="text-xl sm:text-3xl font-extrabold text-gray-900 text-center md:text-left">Přehled všech pohybů</h2>
    </div>

    <x-card no-padding class="mb-8">
        <x-slot name="header">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div>
                    <x-label value="Produkt" />
                    <x-select wire:model.live="productId" class="w-full">
                        <option value="">Všechny produkty</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                @if(auth()->user()->role->isAdmin())
                    <div>
                        <x-label value="Typ" />
                        <x-select wire:model.live="type" class="w-full">
                            <option value="">Všechny typy</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </x-select>
                    </div>
                @endif

                <div>
                    <x-label value="Uživatel" />
                    <x-select wire:model.live="userId" class="w-full">
                        <option value="">Všichni uživatelé</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label value="Výdejní sklad" />
                    <x-select wire:model.live="issueWarehouseId" class="w-full">
                        <option value="">Všechny výdejní sklady</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <x-label value="Příjmový sklad" />
                    <x-select wire:model.live="receiptWarehouseId" class="w-full disabled:bg-gray-100" :disabled="auth()->user()->role->isEmployee()">
                        <option value="">Všechny příjmové sklady</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </x-slot>

        <div class="md:hidden">
            @foreach($movements as $m)
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
                <div class="p-4 border-b border-gray-100 {{ $rowClass }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-bold text-gray-900">{{ $m->product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $m->created_at->format('d. m. Y H:i:s') }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                            {{ $m->translated_type }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <div class="text-gray-400 text-[10px] uppercase font-bold">Z:</div>
                            <div class="text-gray-600">{{ $m->issueWarehouse->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-[10px] uppercase font-bold">Do:</div>
                            <div class="text-gray-600">{{ $m->receiptWarehouse->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-[10px] uppercase font-bold">Počet:</div>
                            <div class="font-mono font-bold">{{ $m->amount }} {{ $m->product->unit }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 text-[10px] uppercase font-bold">Cena:</div>
                            <div class="font-bold">{{ number_format($m->price, 2, ',', ' ') }} Kč</div>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center text-xs text-gray-500">
                        <i class="bi bi-person mr-1"></i> {{ $m->user->name }}
                    </div>
                </div>
            @endforeach
        </div>

        <x-table class="hidden md:table">
            <x-slot name="header">
                <th class="px-6 py-4">Výdejní</th>
                <th class="px-6 py-4">Příjmový</th>
                <th class="px-6 py-4">Produkt</th>
                <th class="px-6 py-4 text-center">Typ</th>
                <th class="px-6 py-4 text-right">Počet</th>
                <th class="px-6 py-4 text-right">Cena</th>
                <th class="px-6 py-4">Uživatel</th>
                <th class="px-6 py-4">Datum</th>
            </x-slot>

            @foreach($movements as $m)
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
                    <td class="px-6 py-4 text-gray-600">{{ $m->issueWarehouse->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $m->receiptWarehouse->name ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $m->product->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded text-xs font-medium {{ $badgeClass }}" title="{{ $m->translated_type }}">
                            {{ $m->translated_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono">{{ $m->amount }} {{ $m->product->unit }}</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">{{ number_format($m->price, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-gray-600">{{ $m->user->name }}</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $m->created_at->format('d. m. Y H:i:s') }}</td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <div class="mt-6">
        {{ $movements->links() }}
    </div>
</div>
