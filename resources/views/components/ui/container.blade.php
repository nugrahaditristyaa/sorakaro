@props(['size' => 'default'])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-3xl',
        'md' => 'max-w-5xl',
        'lg' => 'max-w-7xl',
        default => 'max-w-7xl',
    };
@endphp

<div {{ $attributes->merge(['class' => "{$sizeClasses} mx-auto sm:px-6 lg:px-8"]) }}>
    {{ $slot }}
</div>
