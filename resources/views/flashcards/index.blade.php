<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Flashcard'],
        ]" />
    </x-slot>

    <div class="space-y-6">

        {{-- ── Hero ─────────────────────────────────────────────────────── --}}
        <div class="bg-blue-600 rounded-2xl p-8 lg:p-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/30 rounded-full -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400/20 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold leading-tight">Flashcard Bahasa Karo</h1>
                        <p class="text-blue-100 text-sm italic">kata ibas kartu</p>
                    </div>
                </div>
                <p class="text-blue-100 text-sm mt-3 max-w-lg">
                    Pilih kategori dan mulai menghafal kosakata Bahasa Karo dengan kartu interaktif. Klik kartu untuk melihat terjemahan!
                </p>
            </div>
        </div>

        {{-- ── Category Grid ─────────────────────────────────────────── --}}
        @if($categories->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-700 mb-1">Belum ada kategori</h2>
                <p class="text-gray-400 text-sm max-w-xs">
                    Kategori flashcard belum tersedia. Silakan hubungi admin.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($categories as $index => $category)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300 overflow-hidden"
                         style="animation: fade-in 0.4s ease-out both; animation-delay: {{ $index * 80 }}ms;">

                        {{-- Card header with gradient accent --}}
                        <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-400 group-hover:from-blue-600 group-hover:to-blue-500 transition-all duration-300"></div>

                        <div class="p-6">
                            {{-- Icon + Name --}}
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center flex-shrink-0 transition-colors duration-300 text-2xl">
                                    {{ $category->icon ?? '📚' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition-colors duration-200 truncate">
                                        {{ $category->name }}
                                    </h3>
                                    @if($category->description)
                                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Stats + CTA --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-700">{{ $category->flashcards_count }}</span> Kata
                                </div>

                                @if($category->flashcards_count > 0)
                                    <a href="{{ route('flashcards.show', $category) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl
                                              hover:bg-blue-700 active:scale-95 transition-all duration-200 shadow-sm hover:shadow-md"
                                       id="flashcard-start-{{ $category->id }}">
                                        Mulai
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum ada kata</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
