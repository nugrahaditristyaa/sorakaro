<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Stats Grid --}}


            {{-- Continue Learning Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Continue Learning</h3>
                    @if($lastUnfinished)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded border">
                            <div>
                                <div class="text-indigo-600 font-semibold text-sm">{{ $lastUnfinished->lesson->level->name ?? 'Level' }}</div>
                                <div class="text-xl font-bold text-gray-800">{{ $lastUnfinished->lesson->title }}</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    Last active: {{ $lastUnfinished->updated_at->diffForHumans() }}
                                </div>
                            </div>
                            <a href="{{ route('learn.resume', $lastUnfinished->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none transition ease-in-out duration-150">
                                Continue &rarr;
                            </a>
                        </div>
                    @else
                        <div class="text-gray-500 mb-4">You have no unfinished lessons. Start a new one!</div>
                        <a href="{{ route('learn.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                            Go to Learn
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
