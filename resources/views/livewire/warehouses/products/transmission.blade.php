<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('warehouses.show', $warehouse->id) }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-amber-500">{{ $warehouse->name }} - převodka</h2>
    </div>

    <div class="max-w-4xl mx-auto">
        <x-card class="shadow-xl mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex flex-col">
                    <x-label value="Sklad - výdej" />
                    <x-select wire:model.live="issueWarehouseId" class="w-full py-3 px-4 text-lg font-bold">
                        @foreach($allWarehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="issueWarehouseId" />
                </div>

                <div class="flex flex-col">
                    <x-label value="Sklad - příjem" />
                    <x-select wire:model.live="receiptWarehouseId" class="w-full py-3 px-4 text-lg font-bold">
                        @foreach($allWarehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="receiptWarehouseId" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="flex flex-col">
                    <x-label value="Množství (Skladem: {{ $this->getMaxAmount() }} {{ $product->unit }})" />
                    <button wire:click="setInput('amount')"
                            class="w-full text-center py-4 text-4xl font-black rounded border-4 transition-all duration-200 {{ $currentInput === 'amount' ? 'border-amber-500 bg-amber-50 text-amber-700 shadow-inner' : 'border-gray-100 bg-gray-50 text-gray-400 hover:border-gray-200' }}">
                        {{ $amount }}
                    </button>
                    <x-input-error for="amount" class="text-xs font-bold" />
                </div>

                <div class="flex flex-col">
                    <x-label value="Cena" />
                    <x-select wire:model="priceLevelId" class="w-full text-center py-4 text-xl font-bold border-4 border-gray-100 bg-gray-50 text-gray-700">
                        @forelse($priceLevels as $pl)
                            <option value="{{ $pl->id }}">{{ $pl->price }} Kč ({{ $pl->amount }} {{ $product->unit }})</option>
                        @empty
                            <option disabled>Není skladem na výdejním skladu</option>
                        @endforelse
                    </x-select>
                    <x-input-error for="priceLevelId" />
                </div>

                <div class="flex flex-col">
                    <x-label value="Produkt" />
                    <div class="w-full text-center py-4 text-2xl font-bold rounded bg-gray-100 text-gray-600 border-4 border-transparent flex items-center justify-center h-full">
                        {{ $product->name }}
                    </div>
                </div>
            </div>

            <x-input-error for="submit" class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500" />

            <!-- Numeric Pad -->
            <div class="grid grid-cols-3 gap-4">
                @foreach(['1','2','3','4','5','6','7','8','9'] as $num)
                    <button wire:click="writeDown('{{ $num }}')" class="pad hover:bg-gray-200 text-3xl font-bold shadow-sm border-gray-200">{{ $num }}</button>
                @endforeach

                <button wire:click="removeLast()" class="pad text-amber-500 hover:bg-amber-50 border-amber-100">
                    <i class="bi bi-backspace-fill"></i>
                </button>
                <button wire:click="writeDown('0')" class="pad hover:bg-gray-200 text-3xl font-bold">0</button>
                <button wire:click="writeDown('.')" class="pad hover:bg-gray-200 text-3xl font-bold">.</button>

                <button wire:click="clear()" class="pad text-rose-500 hover:bg-rose-50 border-rose-100 font-bold">C</button>
                <button wire:click="submit()"
                        class="col-span-2 flex items-center justify-center bg-amber-500 text-white rounded shadow-lg hover:bg-amber-600 active:transform active:scale-95 transition-all duration-150 disabled:opacity-50"
                        @if($loading || $priceLevels->isEmpty()) disabled @endif>
                    @if($loading)
                        <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    @else
                        <i class="bi bi-arrow-left-right text-4xl"></i>
                        <span class="ml-2 text-xl font-bold uppercase tracking-widest">Převést</span>
                    @endif
                </button>
            </div>
        </x-card>
    </div>
</div>
