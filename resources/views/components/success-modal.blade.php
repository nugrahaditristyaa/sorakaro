@props(['triggerMessages' => []])

@php
    $message = null;
    foreach ($triggerMessages as $status => $msg) {
        if (session('status') === $status) {
            $message = $msg;
            break;
        }
    }
@endphp

<x-modal name="success-modal" :show="(bool) $message" focusable>
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Saved') }}
            </h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500 focus:outline-none transition ease-in-out duration-150">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <p class="text-sm text-gray-600 mb-6">
            {{ $message }}
        </p>

        <div class="flex justify-end">
            <x-primary-button x-on:click="$dispatch('close')">
                {{ __('OK') }}
            </x-primary-button>
        </div>
    </div>
</x-modal>
