<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Belajar', 'url' => route('learn.index')],
            ['label' => 'Buku Panduan']
        ]" />
    </x-slot>

    <div class="py-12">
        <x-ui.container size="md">
            <div class="space-y-6">

                {{-- Empty State --}}
                @if($sections->isEmpty())
                    <x-ui.card>
                        <x-ui.empty-state
                            title="Buku Panduan Belum Tersedia"
                            description="Buku panduan untuk tingkat ini belum dibuat. Periksa kembali nanti!"
                        >
                            <x-slot:icon>
                                <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </x-slot:icon>

                            <x-ui.button variant="primary" :href="route('learn.level', $level)">
                                Lihat Pelajaran
                            </x-ui.button>
                        </x-ui.empty-state>
                    </x-ui.card>

                @else
                    {{-- Sections --}}
                    <div class="space-y-6">
                        @foreach($sections as $section)
                            <x-ui.card>
                                {{-- Header --}}
                                <div class="space-y-2">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ $section->title }}
                                    </div>

                                    @if($section->subtitle)
                                        <x-ui.section-title :level="2">
                                            {{ $section->subtitle }}
                                        </x-ui.section-title>
                                    @endif

                                    @if($section->description)
                                        <p class="text-sm text-gray-600">
                                            {{ $section->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="my-6 border-t"></div>

                                {{-- Items --}}
                                @if($section->items->isNotEmpty())
                                    <div class="space-y-3">
                                        @foreach($section->items as $item)
                                            <div class="rounded-2xl border bg-gray-50 p-4 hover:border-gray-300 hover:bg-gray-100 transition">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="mb-2">
                                                            @if($item->type === 'phrase')
                                                                <x-ui.badge variant="info">💬 Frasa</x-ui.badge>
                                                            @else
                                                                <x-ui.badge class="bg-purple-100 text-purple-800">💡 Tip</x-ui.badge>
                                                            @endif
                                                        </div>

                                                        <div class="text-base font-semibold text-gray-900">
                                                            {{ $item->text }}
                                                        </div>

                                                        @if($item->translation)
                                                            <div class="mt-1 text-sm text-gray-600 italic">
                                                                {{ $item->translation }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                     @if($item->audio_path)
                                                        <div x-data="audioPlayer('{{ Storage::disk('public')->url($item->audio_path) }}')">
                                                            {{-- Error fallback --}}
                                                            <span x-show="audioError"
                                                                  class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs text-red-600 bg-red-50 border border-red-200">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                                                                Gagal
                                                                <button type="button" @click="retry()" class="underline">Ulang</button>
                                                            </span>
                                                            {{-- Normal play button --}}
                                                            <button type="button"
                                                                    x-show="!audioError"
                                                                    @click="toggle()"
                                                                    :title="playing ? 'Jeda audio' : 'Putar audio'"
                                                                    class="group shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border
                                                                           text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400
                                                                           bg-white text-indigo-600 border-indigo-200 hover:bg-indigo-600 hover:text-white hover:border-indigo-600">
                                                                {{-- Play icon --}}
                                                                <svg x-show="!playing" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M8 5v14l11-7z"/>
                                                                </svg>
                                                                {{-- Pause icon --}}
                                                                <svg x-show="playing" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="display:none">
                                                                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                                </svg>
                                                                <span x-text="playing ? 'Jeda' : '🔊 Dengar'"></span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Belum ada item di bagian ini.</p>
                                @endif
                            </x-ui.card>
                        @endforeach
                    </div>
                @endif

            </div>
        </x-ui.container>
    </div>

</x-app-layout>

