<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $level->name }} - Lessons
        </h2>
    </x-slot>

    <div class="pt-4 pb-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="mb-4">
                <a href="{{ route('learn.index') }}" class="text-black hover:text-gray-700 font-medium">
                    &larr; Back to Levels
                </a>
            </div>

            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow">
                @if ($level->description)
                    <div class="mb-6 text-gray-600 text-lg">{{ $level->description }}</div>
                @endif

                <div class="space-y-4">
                    @forelse ($lessons as $lesson)
                        <div class="flex items-center justify-between border rounded p-4 bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex items-center gap-4">
                                <!-- Icon placeholder (optional) -->
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-gray-800 font-bold text-lg">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-gray-800">{{ $lesson->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $lesson->questions_count }} Questions</div>

                                    {{-- Progress Info --}}
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        {{-- Badge --}}
                                        @if($lesson->status === 'completed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif($lesson->status === 'in_progress')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Sedang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                Belum mulai
                                            </span>
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
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                                        {{ $lesson->status === 'in_progress' ? 'Restart' : 'Start Lesson' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 text-center py-8">Belum ada lesson di level ini.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
