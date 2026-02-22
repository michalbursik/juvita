@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-gray-500 uppercase tracking-wider mb-2']) }}>
    {{ $value ?? $slot }}
</label>
