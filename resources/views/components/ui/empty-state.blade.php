@props([
    'title' => '',
    'description' => '',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    @if($icon)
        <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
            {!! $icon !!}
        </div>
    @endif
    
    @if($title)
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600">{{ $description }}</p>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
