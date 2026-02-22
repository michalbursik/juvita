<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="w-full sm:max-w-md mt-6">
        <form wire:submit.prevent="login">
            <x-card>
                <x-slot name="header">
                    <h2 class="text-xl font-bold text-gray-800 text-center uppercase tracking-widest">Přihlášení</h2>
                </x-slot>

                <div>
                    <x-label for="email" value="E-mail" />
                    <x-input wire:model.defer="email"
                           type="email"
                           id="email"
                           class="block mt-1 w-full @error('email') border-red-500 @enderror"
                           required
                           autofocus
                    />
                    <x-input-error for="email" class="text-red-600" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="Heslo" />
                    <x-input wire:model.defer="password"
                           type="password"
                           id="password"
                           class="block mt-1 w-full @error('password') border-red-500 @enderror"
                           required
                    />
                    <x-input-error for="password" class="text-red-600" />
                </div>

                @if($error)
                    <div class="mt-4 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $error }}
                    </div>
                @endif

                <x-slot name="footer">
                    <x-button type="submit" loading="login" variant="primary" class="text-xs w-full justify-center">
                        Přihlásit se
                    </x-button>
                </x-slot>
            </x-card>
        </form>
    </div>
</div>
