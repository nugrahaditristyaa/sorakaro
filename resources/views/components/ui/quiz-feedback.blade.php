@props([
    'isCorrect' => false,
    'explanation' => null,
])

<div class="mt-6 p-4 rounded-lg border
    {{ $isCorrect ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
    <div class="flex items-center gap-3 mb-2">
        <div class="text-2xl">
            {{ $isCorrect ? '✅' : '❌' }}
        </div>
        <div class="font-bold text-lg {{ $isCorrect ? 'text-green-800' : 'text-red-800' }}">
            {{ $isCorrect ? 'Benar!' : 'Salah!' }}
        </div>
    </div>

    @if($explanation)
        <div class="text-gray-700 mt-2">
            <strong>Explanation:</strong> {{ $explanation }}
        </div>
    @endif
</div>
