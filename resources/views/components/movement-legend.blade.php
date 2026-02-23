<div {{ $attributes->merge(['class' => 'flex flex-wrap gap-4 items-center bg-gray-50 px-4 py-2 rounded-lg border border-gray-100']) }}>
    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-2">Legenda:</span>

    <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-sm bg-emerald-500 shadow-sm"></span>
        <span class="text-sm text-gray-600 font-medium">Příjemka</span>
    </div>

    <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-sm bg-rose-500 shadow-sm"></span>
        <span class="text-sm text-gray-600 font-medium">Výdejka</span>
    </div>

    <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-sm bg-amber-500 shadow-sm"></span>
        <span class="text-sm text-gray-600 font-medium">Převodka</span>
    </div>

    <div class="flex items-center gap-2">
        <span class="w-3 h-3 rounded-sm bg-blue-500 shadow-sm"></span>
        <span class="text-sm text-gray-600 font-medium">Kontrola</span>
    </div>
</div>
