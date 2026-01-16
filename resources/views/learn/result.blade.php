<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Result - {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow space-y-3">
                <div class="text-xl font-semibold">
                    Score: {{ $attempt->score }} / {{ $attempt->total_questions }}
                </div>

                <div class="text-gray-600">
                    Finished at: {{ optional($attempt->finished_at)->format('Y-m-d H:i') }}
                </div>

                <a href="{{ route('learn.level', $lesson->level_id) }}" class="inline-block px-4 py-2 rounded bg-gray-800 text-white">
                    Back to {{ $lesson->level ? $lesson->level->name : 'Level' }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
