<x-app-layout>
    {{-- Header --}}
    <div class="bg-white shadow">
        <x-ui.container class="py-6">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $level->name }}
                </h2>
                <x-ui.button variant="outline" :href="route('learn.guidebook', $level)">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    GUIDEBOOK
                </x-ui.button>
            </div>
        </x-ui.container>
    </div>

    <div class="pt-4 pb-8">
        <x-ui.container size="md">

            <div class="mb-4">
                <a href="{{ route('learn.index') }}" class="text-black hover:text-gray-700 font-medium">
                    &larr; Back to Levels
                </a>
            </div>

            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <x-ui.card>
                @if ($level->description)
                    <div class="mb-6 text-gray-600 text-lg">{{ $level->description }}</div>
                @endif

                <div class="space-y-4">
                    @forelse ($lessons as $lesson)
                        <div class="flex items-center justify-between border rounded p-4 bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                {{-- Icon/Number --}}
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-gray-800 font-bold text-lg">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <x-ui.section-title :level="3">{{ $lesson->title }}</x-ui.section-title>
                                    <div class="text-sm text-gray-500">{{ $lesson->questions_count }} Questions</div>

                                    {{-- Progress Info --}}
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        {{-- Status Badge --}}
                                        @if($lesson->status === 'completed')
                                            <x-ui.badge variant="success">Selesai</x-ui.badge>
                                        @elseif($lesson->status === 'in_progress')
                                            <x-ui.badge variant="warning">Sedang</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="default">Belum mulai</x-ui.badge>
                                        @endif

                                        {{-- Last Score --}}
                                        @if($lesson->latest_attempt)
                                             <span class="text-xs text-gray-600 border-l pl-2 border-gray-300">
                                                Last: {{ $lesson->latest_attempt->score }}/{{ $lesson->latest_attempt->total_questions }}
                                             </span>
                                        @endif

                                        {{-- Attempts Count --}}
                                        @if($lesson->attempts_count > 0)
                                            <span class="text-xs text-gray-500 border-l pl-2 border-gray-300">
                                                {{ $lesson->attempts_count }} attempts
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                {{-- Continue Button --}}
                                @if($lesson->status === 'in_progress' && $lesson->latest_attempt)
                                     <a href="{{ route('learn.resume', $lesson->latest_attempt->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-900 underline">
                                         Continue
                                     </a>
                                @endif

                                <form method="POST" action="{{ route('learn.start', $lesson) }}">
                                    @csrf
                                    <x-ui.button variant="primary" type="submit">
                                        {{ $lesson->status === 'in_progress' ? 'Restart' : 'Start Lesson' }}
                                    </x-ui.button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state 
                            title="No Lessons Yet"
                            description="Belum ada lesson di level ini."
                        />
                    @endforelse
                </div>
            </x-ui.card>

        </x-ui.container>
    </div>
</x-app-layout>
