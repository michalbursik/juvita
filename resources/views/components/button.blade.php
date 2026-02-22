@props(['type' => 'submit', 'variant' => 'primary', 'loading' => null, 'tag' => 'button'])

@php
    $baseClasses = 'inline-flex items-center px-6 py-3 border border-transparent rounded font-bold uppercase tracking-widest transition ease-in-out duration-150 shadow-lg focus:outline-none focus:ring-4 disabled:opacity-50';

    $variants = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-900 ring-indigo-300',
        'secondary' => 'bg-white text-gray-700 border-gray-200 hover:text-gray-900 hover:border-gray-300 active:bg-gray-100 ring-gray-200',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 active:bg-rose-900 ring-rose-300',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 active:bg-emerald-900 ring-emerald-300',
        'warning' => 'bg-amber-500 text-white hover:bg-amber-600 active:bg-amber-700 ring-amber-200',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($tag === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }} @if($loading) wire:loading.attr="disabled" @endif>
        @if($loading)
            <span wire:loading wire:target="{{ $loading }}" class="animate-spin mr-2">
                <svg class="h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        @endif
        {{ $slot }}
    </button>
@endif
