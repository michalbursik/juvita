<div {{ $attributes->merge(['class' => 'bg-white shadow rounded border border-gray-200 overflow-hidden']) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $attributes->get('no-padding') ? '' : 'p-6 md:p-8' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            {{ $footer }}
        </div>
    @endif
</div>
