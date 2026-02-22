<div>
    <div class="flex items-center mb-8">
        <a href="{{ route('users.index') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 bg-white rounded shadow-sm border border-gray-200 transition-all duration-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-gray-900">Nový uživatel</h2>
    </div>

    <div class="max-w-2xl mx-auto">
        <form wire:submit.prevent="submit">
            <x-card class="overflow-hidden">
                <div class="space-y-6">
                    <div>
                        <x-label for="name" value="Jméno" />
                        <x-input type="text" wire:model="name" id="name" class="w-full" />
                        <x-input-error for="name" />
                    </div>

                    <div>
                        <x-label for="email" value="E-mail" />
                        <x-input type="email" wire:model="email" id="email" class="w-full font-mono" />
                        <x-input-error for="email" />
                    </div>

                    <div>
                        <x-label for="password" value="Heslo" />
                        <x-input type="password" wire:model="password" id="password" class="w-full font-mono" />
                        <x-input-error for="password" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="role" value="Role" />
                            <x-select wire:model="role" id="role" class="w-full font-bold uppercase text-xs tracking-widest">
                                <option value="admin">Admin</option>
                                <option value="employee">Zaměstnanec</option>
                            </x-select>
                            <x-input-error for="role" />
                        </div>

                        <div>
                            <x-label for="warehouseId" value="Sklad" />
                            <x-select wire:model="warehouseId" id="warehouseId" class="w-full">
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="warehouseId" />
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <x-button type="submit" loading="submit">
                        Vytvořit uživatele
                    </x-button>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
