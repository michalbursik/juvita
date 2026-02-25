<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 sm:mb-8 gap-4">
        <h2 class="text-xl sm:text-3xl font-extrabold text-gray-900 text-center md:text-left">Správa uživatelů</h2>
        <div class="flex justify-center md:justify-end">
            <x-button :href="route('users.create')" tag="a">
                <i class="bi bi-person-plus mr-2"></i> Přidat uživatele
            </x-button>
        </div>
    </div>

    <x-card no-padding>
        <div class="md:hidden">
            @foreach($users as $user)
                <div class="p-4 border-b border-gray-100">
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-bold text-gray-900">{{ $user->name }}</div>
                        @if($user->role->isAdmin())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 uppercase">Admin</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">Zaměstnanec</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-600 mb-2">{{ $user->email }}</div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="text-gray-500">
                            <i class="bi bi-geo-alt mr-1"></i> {{ $user->warehouse->name ?? '-' }}
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold uppercase">Upravit</a>
                            @if($user->id !== auth()->id())
                                <button wire:click="deleteUser('{{ $user->id }}')"
                                        wire:confirm="Opravdu smazat tohoto uživatele?"
                                        class="text-rose-600 hover:text-rose-900 font-bold uppercase">
                                    Smazat
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-table class="hidden md:table">
            <x-slot name="header">
                <th class="px-6 py-4">Jméno</th>
                <th class="px-6 py-4">E-mail</th>
                <th class="px-6 py-4">Role</th>
                <th class="px-6 py-4">Sklad</th>
                <th class="px-6 py-4 text-right">Akce</th>
            </x-slot>

            @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role->isAdmin())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Zaměstnanec</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->warehouse->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Upravit</a>
                            @if($user->id !== auth()->id())
                                <button wire:click="deleteUser('{{ $user->id }}')"
                                        wire:confirm="Opravdu smazat tohoto uživatele?"
                                        class="text-rose-600 hover:text-rose-900 font-medium">
                                    Smazat
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
