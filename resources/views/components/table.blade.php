<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse']) }}>
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
