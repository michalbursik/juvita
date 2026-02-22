<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('products.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Upravit produkt</h2>
    </div>

    <div class="max-w-4xl mx-auto">
        <form wire:submit.prevent="submit">
            <x-card class="overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div>
                            <x-label for="name" value="Název produktu" />
                            <x-input type="text" wire:model="name" id="name" class="w-full text-lg font-bold" />
                            <x-input-error for="name" />
                        </div>

                        <div>
                            <x-label for="origin" value="Původ" />
                            <x-input type="text" wire:model="origin" id="origin" class="w-full" />
                            <x-input-error for="origin" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="unit" value="Jednotka" />
                                <x-select wire:model="unit" id="unit" class="w-full font-bold">
                                    <option value="kg">kg</option>
                                    <option value="ks">ks</option>
                                </x-select>
                                <x-input-error for="unit" />
                            </div>
                            <div>
                                <x-label for="order" value="Pořadí" />
                                <x-input type="number" wire:model="order" id="order" class="w-full font-mono" />
                                <x-input-error for="order" />
                            </div>
                        </div>

                        <div class="flex items-center mt-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50 h-5 w-5 bg-gray-50/50">
                                <span class="ml-3 text-sm font-bold text-gray-700 uppercase tracking-widest">Aktivní produkt</span>
                            </label>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-6">
                        <div>
                            <x-label value="Obrázek produktu" />
                            <div class="mt-1 relative group h-full">
                                <label for="file-upload" class="flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded hover:border-indigo-400 hover:bg-indigo-50 transition-all cursor-pointer overflow-hidden min-h-[220px] h-full relative">
                                    @if ($image)
                                        <div class="absolute inset-0 w-full h-full p-2 bg-white">
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-contain">
                                        </div>
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white font-bold text-xs uppercase tracking-widest">Změnit obrázek</span>
                                        </div>
                                    @elseif ($existingImage)
                                        <div class="absolute inset-0 w-full h-full p-2 bg-white">
                                            <img src="{{ asset('storage/'.$existingImage) }}" class="w-full h-full object-contain">
                                        </div>
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white font-bold text-xs uppercase tracking-widest">Změnit obrázek</span>
                                        </div>
                                    @else
                                        <div class="space-y-2 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex flex-col text-sm text-gray-600">
                                                <span class="font-bold text-indigo-600">Nahrát soubor</span>
                                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF do 1MB</p>
                                            </div>
                                        </div>
                                    @endif
                                    <input id="file-upload" wire:model="image" type="file" class="sr-only">
                                </label>
                            </div>
                            <x-input-error for="image" />
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <x-button type="submit" loading="submit">
                        Uložit změny
                    </x-button>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
