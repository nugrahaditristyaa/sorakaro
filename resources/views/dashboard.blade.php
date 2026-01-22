<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <x-ui.container>
            <div class="space-y-6">

                {{-- Current Level & Guidebook Section --}}
                @if($currentLevel)
                    <x-ui.card>
                        <div class="flex items-start justify-between gap-6">
                            <div class="min-w-0">
                                <x-ui.section-title :level="3" class="mb-2">Current Level</x-ui.section-title>

                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="text-2xl font-bold text-indigo-600">{{ $currentLevel->name }}</span>

                                    @if($currentLevel->description)
                                        <span class="text-sm text-gray-600">{{ $currentLevel->description }}</span>
                                    @endif
                                </div>
                            </div>

                            <x-ui.button variant="primary" :href="route('dashboard.guidebook')" class="shrink-0">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Guidebook
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                @endif

                {{-- Continue Learning Section --}}
                <x-ui.card>
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <x-ui.section-title :level="3">Continue Learning</x-ui.section-title>
                    </div>

                    @if($lastUnfinished)
                        <div class="rounded-lg border bg-gray-50 p-4">
                            <div class="flex items-start justify-between gap-6">
                                <div class="min-w-0">
                                    <div class="text-indigo-600 font-semibold text-sm">
                                        {{ $lastUnfinished->lesson->level->name ?? 'Level' }}
                                    </div>
                                    <div class="text-xl font-bold text-gray-800">
                                        {{ $lastUnfinished->lesson->title }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        Last active: {{ $lastUnfinished->updated_at->diffForHumans() }}
                                    </div>
                                </div>

                                <x-ui.button variant="secondary" :href="route('learn.resume', $lastUnfinished->id)" class="shrink-0">
                                    Continue &rarr;
                                </x-ui.button>
                            </div>
                        </div>
                    @else
                        <x-ui.empty-state
                            title="No Unfinished Lessons"
                            description="You have no unfinished lessons. Start a new one!">
                            <x-ui.button variant="primary" :href="route('learn.index')">
                                Go to Learn
                            </x-ui.button>
                        </x-ui.empty-state>
                    @endif
                </x-ui.card>
            </div>
        </x-ui.container>
    </div>
</x-app-layout>
