<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Levels
        </h2>
    </x-slot>

    <div class="pt-4 pb-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @foreach ($levels as $level)
                <div class="bg-white p-6 rounded shadow flex items-center justify-between mb-4">
                    <div>
                        <div class="font-bold text-xl text-gray-800">{{ $level->name }}</div>
                        @if ($level->description)
                            <div class="text-sm text-gray-600 mt-1">{{ $level->description }}</div>
                        @endif
                    </div>
                    
                    <a href="{{ route('learn.level', $level) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                        Join Quiz
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
