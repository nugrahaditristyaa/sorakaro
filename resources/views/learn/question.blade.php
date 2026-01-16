<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Progress & Score Bar --}}
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm font-medium text-gray-600">
                    Question {{ $progress['current'] }} of {{ $progress['total'] }}
                </div>
                <div class="text-sm font-medium text-gray-600">
                    Score: {{ $attempt->score }} / {{ $progress['total'] }}
                </div>
            </div>

            {{-- Continuous Progress Bar Visual --}}
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
                <div class="bg-gray-800 h-2.5 rounded-full transition-all duration-300" style="width: {{ ($progress['current'] / $progress['total']) * 100 }}%"></div>
            </div>

            <div class="bg-white p-6 rounded shadow relative">
                
                {{-- Question Prompt --}}
                <div class="text-lg font-semibold mb-6">
                    {{ $question->prompt }}
                </div>

                <form method="POST" action="{{ route('learn.submit', [$lesson, $question]) }}">
                    @csrf
                    
                    <div class="space-y-3">
                        @foreach ($choices as $choice)
                            @php
                                $isAnswered = isset($userAnswer);
                                $isSelected = $isAnswered && $userAnswer->choice_id == $choice->id;
                                $isCorrect = $choice->is_correct;
                                
                                $classes = "flex items-center gap-3 border rounded p-4 w-full text-left transition";
                                
                                if (!$isAnswered) {
                                    $classes .= " hover:bg-gray-50 cursor-pointer";
                                } else {
                                    $classes .= " cursor-default";
                                    if ($isCorrect) {
                                        $classes .= " bg-green-100 border-green-500 text-green-800";
                                    } elseif ($isSelected && !$isCorrect) {
                                        $classes .= " bg-red-100 border-red-500 text-red-800";
                                    } else {
                                        $classes .= " bg-gray-50 text-gray-400";
                                    }
                                }
                            @endphp

                            <label class="{{ $classes }}">
                                <input type="radio" name="choice_id" value="{{ $choice->id }}" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                    {{ $isAnswered ? 'disabled' : '' }}
                                    {{ $isSelected ? 'checked' : '' }}
                                >
                                <span class="font-medium">{{ $choice->text }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('choice_id')
                        <div class="mt-3 text-sm text-red-600 font-medium">{{ $message }}</div>
                    @enderror

                    {{-- Feedback / Actions Area --}}
                    @if (isset($userAnswer))
                        <div class="mt-6 p-4 rounded-lg {{ $userAnswer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="text-2xl">
                                    {{ $userAnswer->is_correct ? '✅' : '❌' }}
                                </div>
                                <div class="font-bold text-lg {{ $userAnswer->is_correct ? 'text-green-800' : 'text-red-800' }}">
                                    {{ $userAnswer->is_correct ? 'Benar!' : 'Salah!' }}
                                </div>
                            </div>
                            @if($question->explanation)
                                <div class="text-gray-700 mt-2">
                                    <strong>Explanation:</strong> {{ $question->explanation }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-8 flex items-center justify-between">
                        {{-- BACK BUTTON --}}
                        @if ($prevUrl)
                            <a href="{{ $prevUrl }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 font-medium">
                                &larr; Previous
                            </a>
                        @else
                            <div></div> {{-- Spacer --}}
                        @endif

                        {{-- ACTION BUTTON --}}
                        @if (isset($userAnswer))
                            <a href="{{ $nextUrl }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                                {{ Str::contains($nextUrl, 'result') ? 'Finish Quiz' : 'Next Question' }} &rarr;
                            </a>
                        @else
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                                Check Answer
                            </button>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
