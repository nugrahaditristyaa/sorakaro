@props(['level' => 1])

@php
    $classes = match($level) {
        1 => 'text-2xl font-bold text-gray-900',
        2 => 'text-xl font-semibold text-gray-900',
        3 => 'text-lg font-bold text-gray-900',
        default => 'text-lg font-bold text-gray-900',
    };
@endphp

<h{{ $level }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</h{{ $level }}>
