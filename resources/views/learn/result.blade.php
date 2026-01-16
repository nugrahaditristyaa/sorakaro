<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Result - {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-ui.container size="sm">
            <x-ui.card>
                
                {{-- Success Icon --}}
                <div class="text-center mb-6">
                    <div class="mx-auto h-20 w-20 rounded-full bg-green-100 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <x-ui.section-title :level="1" class="mb-2">Quiz Completed!</x-ui.section-title>
                    <p class="text-gray-600">Great job finishing this lesson</p>
                </div>

                {{-- Score Display --}}
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Your Score</div>
                        <div class="text-5xl font-bold text-indigo-600 mb-2">
                            {{ $attempt->score }}<span class="text-2xl text-gray-400">/{{ $attempt->total_questions }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ round(($attempt->score / $attempt->total_questions) * 100) }}% Correct
                        </div>
                    </div>
                </div>

                {{-- Completion Time --}}
                <div class="text-center text-sm text-gray-600 mb-6">
                    Finished at: {{ optional($attempt->finished_at)->format('Y-m-d H:i') }}
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-center gap-3">
                    <x-ui.button variant="primary" :href="route('learn.level', $lesson->level_id)">
                        Back to {{ $lesson->level ? $lesson->level->name : 'Level' }}
                    </x-ui.button>
                    <x-ui.button variant="ghost" :href="route('learn.index')">
                        All Levels
                    </x-ui.button>
                </div>

            </x-ui.card>
        </x-ui.container>
    </div>
</x-app-layout>
