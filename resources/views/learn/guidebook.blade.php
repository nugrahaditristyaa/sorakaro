<x-app-layout>
    {{-- Green Header --}}
    <div class="bg-green-500 shadow">
        <x-ui.container class="py-4">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-white">
                    {{ $level->name }} Guidebook
                </h2>
                <x-ui.button variant="outline" :href="route('learn.level', $level)" class="!bg-white !text-green-600 !border-green-600 hover:!bg-green-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    BACK TO LESSONS
                </x-ui.button>
            </div>
        </x-ui.container>
    </div>

    <div class="py-8">
        <x-ui.container size="md">
            
            {{-- Empty State --}}
            @if($sections->isEmpty())
                <x-ui.card>
                    <x-ui.empty-state 
                        title="No Guidebook Available"
                        description="The guidebook for this level hasn't been created yet. Check back later!">
                        <x-slot:icon>
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </x-slot:icon>
                    </x-ui.empty-state>
                </x-ui.card>
            @else
                {{-- Guidebook Sections --}}
                <div class="space-y-8">
                    @foreach($sections as $section)
                        <x-ui.card>
                            {{-- Section Header --}}
                            <div class="mb-6">
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                    {{ $section->title }}
                                </div>
                                @if($section->subtitle)
                                    <x-ui.section-title :level="2" class="mb-2">
                                        {{ $section->subtitle }}
                                    </x-ui.section-title>
                                @endif
                                @if($section->description)
                                    <p class="text-gray-600">
                                        {{ $section->description }}
                                    </p>
                                @endif
                            </div>

                            {{-- Section Items (Bubble Cards) --}}
                            @if($section->items->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach($section->items as $item)
                                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 hover:border-green-400 hover:bg-green-50 transition-all duration-200">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1">
                                                    {{-- Item Type Badge --}}
                                                    <div class="flex items-center gap-2 mb-2">
                                                        @if($item->type === 'phrase')
                                                            <x-ui.badge variant="info">
                                                                💬 Phrase
                                                            </x-ui.badge>
                                                        @else
                                                            <x-ui.badge class="bg-purple-100 text-purple-800">
                                                                💡 Tip
                                                            </x-ui.badge>
                                                        @endif
                                                    </div>

                                                    {{-- Main Text --}}
                                                    <div class="text-lg font-semibold text-gray-900 mb-1">
                                                        {{ $item->text }}
                                                    </div>

                                                    {{-- Translation --}}
                                                    @if($item->translation)
                                                        <div class="text-sm text-gray-600 italic">
                                                            {{ $item->translation }}
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Audio Button (Future-proof) --}}
                                                @if($item->audio_path)
                                                    <button 
                                                        type="button"
                                                        class="flex-shrink-0 p-3 bg-green-500 hover:bg-green-600 rounded-full text-white transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                                        title="Play audio"
                                                    >
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm italic">No items in this section yet.</p>
                            @endif
                        </x-ui.card>
                    @endforeach
                </div>
            @endif

        </x-ui.container>
    </div>
</x-app-layout>
