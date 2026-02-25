<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 sm:mb-8 gap-4">
        <h2 class="text-xl sm:text-3xl font-extrabold text-gray-900 text-center md:text-left">Slevy</h2>
        <div class="flex justify-center md:justify-end">
            <x-button :href="route('discounts.create')" tag="a">
                <i class="bi bi-plus-lg mr-2"></i> Přidat slevu
            </x-button>
        </div>
    </div>

    <x-card no-padding>
        <div class="md:hidden">
            @foreach($discounts as $discount)
                @php
                    $statusBadgeClass = match($discount->status) {
                        \App\Enums\DiscountStatus::ACTIVE => 'bg-emerald-100 text-emerald-700',
                        \App\Enums\DiscountStatus::APPLIED => 'bg-gray-100 text-gray-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <div class="p-4 border-b border-gray-100 @if(auth()->user()->role->isAdmin() && $discount->status->isActive()) active:bg-gray-50 @endif"
                     @if(auth()->user()->role->isAdmin() && $discount->status->isActive()) onclick="window.location.href='{{ route('discounts.edit', $discount->id) }}'" @endif>
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-bold text-gray-900">{{ $discount->warehouse->name }}</div>
                        <div class="font-mono text-[10px] text-gray-400">#{{ substr($discount->id, 0, 8) }}</div>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $statusBadgeClass }}">
                            {{ $discount->status->label() }}
                        </span>
                        <div class="font-black text-rose-600">-{{ number_format($discount->amount, 2, ',', ' ') }} Kč</div>
                    </div>
                    @if($discount->note)
                        <div class="text-sm text-gray-600 mb-2 italic">"{{ $discount->note }}"</div>
                    @endif
                    <div class="flex justify-between items-end text-[10px] text-gray-500">
                        <div>
                            <div><i class="bi bi-person mr-1"></i>{{ $discount->user->name }}</div>
                            <div><i class="bi bi-clock mr-1"></i>{{ $discount->created_at->format('d. m. Y H:i:s') }}</div>
                        </div>
                        @if(auth()->user()->role->isAdmin() && $discount->status->isActive())
                            <a href="{{ route('discounts.edit', $discount->id) }}" class="text-indigo-600 font-bold uppercase text-xs">Upravit</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <x-table class="hidden md:table">
            <x-slot name="header">
                <th class="px-6 py-4">ID</th>
                <th class="px-6 py-4">Sklad</th>
                <th class="px-6 py-4">Uživatel</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4">Popis</th>
                <th class="px-6 py-4 text-right">Množství</th>
                <th class="px-6 py-4">Vytvořeno</th>
                <th class="px-6 py-4 text-right">Akce</th>
            </x-slot>

            @foreach($discounts as $discount)
                @php
                    $statusBadgeClass = match($discount->status) {
                        \App\Enums\DiscountStatus::ACTIVE => 'bg-emerald-100 text-emerald-700',
                        \App\Enums\DiscountStatus::APPLIED => 'bg-gray-100 text-gray-700',
                        default => 'bg-gray-100 text-gray-700'
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-colors @if(auth()->user()->role->isAdmin() && $discount->status->isActive()) cursor-pointer @endif"
                    @if(auth()->user()->role->isAdmin() && $discount->status->isActive()) onclick="window.location.href='{{ route('discounts.edit', $discount->id) }}'" @endif>
                    <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ substr($discount->id, 0, 8) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $discount->warehouse->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $discount->user->name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded text-xs font-medium {{ $statusBadgeClass }}">
                            {{ $discount->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">
                        {{ $discount->note ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-black text-rose-600">-{{ number_format($discount->amount, 2, ',', ' ') }} Kč</td>
                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap text-sm">{{ $discount->created_at->format('d. m. Y H:i:s') }}</td>
                    <td class="px-6 py-4 text-right">
                        @if(auth()->user()->role->isAdmin() && $discount->status->isActive())
                            <a href="{{ route('discounts.edit', $discount->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Upravit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>
</div>
