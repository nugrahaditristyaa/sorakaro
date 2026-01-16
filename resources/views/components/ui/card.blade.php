@props(['padding' => true])

@php
    $paddingClass = $padding ? 'p-6' : '';
@endphp

<div {{ $attributes->merge(['class' => "bg-white overflow-hidden shadow-sm sm:rounded-lg {$paddingClass}"]) }}>
    {{ $slot }}
</div>
