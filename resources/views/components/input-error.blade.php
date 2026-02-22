@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-rose-600 text-sm mt-1 font-medium']) }}>{{ $message }}</p>
@enderror
