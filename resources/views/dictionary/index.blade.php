<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Kamus Bahasa Karo'],
        ]" />
    </x-slot>

    <div class="space-y-6">

        {{-- ── Hero + Search ─────────────────────────────────────────────── --}}
        <div class="bg-blue-600 rounded-2xl p-8 lg:p-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500/30 rounded-full -translate-y-1/3 translate-x-1/4 pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold leading-tight">Kamus Bahasa Karo</h1>
                        <p class="text-blue-100 text-sm">Cari arti kata Karo ↔ Indonesia dengan cepat</p>
                    </div>
                </div>

                {{-- Search Form --}}
                <form method="GET" action="{{ route('dictionary.index') }}" id="kamus-search-form" class="mt-5">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg id="search-icon" class="w-5 h-5 text-gray-400 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            type="search"
                            name="q"
                            id="kamus-input"
                            value="{{ $query }}"
                            placeholder="Cari kata Bahasa Karo atau Indonesia..."
                            autocomplete="off"
                            class="w-full bg-white text-gray-900 font-medium text-base pl-12 pr-4 py-4 rounded-xl
                                   border-0 shadow-lg focus:outline-none focus:ring-4 focus:ring-white/40
                                   placeholder-gray-400 transition"
                        >
                        @if($query)
                            <a href="{{ route('dictionary.index') }}"
                               class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Result count hint --}}
                @if($query && $entries)
                    <p class="mt-3 text-sm text-blue-100">
                        @if($entries->total() > 0)
                            Ditemukan <strong class="text-white">{{ $entries->total() }}</strong> kata untuk "<em>{{ $query }}</em>"
                        @endif
                    </p>
                @endif
            </div>
        </div>

        {{-- ── Results ─────────────────────────────────────────────────────── --}}
        @if(is_null($entries))
            {{-- Initial state — no search yet --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-700 mb-1">Cari kata Bahasa Karo</h2>
                <p class="text-gray-400 text-sm max-w-xs">
                    Ketik kata dalam Bahasa Karo atau Indonesia untuk menemukan artinya.
                </p>
            </div>

        @elseif($entries->isEmpty())
            {{-- Empty state — searched but nothing found --}}
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-orange-50 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-700 mb-1">Kata tidak ditemukan 😢</h2>
                <p class="text-gray-400 text-sm max-w-xs">
                    Tidak ada kata yang cocok dengan "<strong class="text-gray-600">{{ $query }}</strong>".
                    <br>Coba gunakan kata lain atau periksa ejaan.
                </p>
                <a href="{{ route('dictionary.index') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition active:scale-95">
                    Cari Kata Lain
                </a>
            </div>

        @else
            {{-- Results grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($entries as $entry)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-blue-100 transition group">

                        {{-- Karo word --}}
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="text-lg font-bold text-gray-900 leading-tight group-hover:text-blue-700 transition">
                                {{ $entry->karo_word }}
                            </span>
                            <span class="flex-shrink-0 mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 uppercase tracking-wide">
                                Karo
                            </span>
                        </div>

                        {{-- Indonesian translation --}}
                        <p class="text-sm text-gray-600 leading-relaxed mb-0">
                            {{ $entry->indonesian_translation }}
                        </p>

                        {{-- Example sentence (if available) --}}
                        @if($entry->example_sentence)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                                    Contoh
                                </div>
                                <p class="text-sm text-gray-700 italic">
                                    "{{ $entry->example_sentence }}"
                                </p>
                                @if($entry->example_translation)
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $entry->example_translation }}
                                    </p>
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($entries->hasPages())
                <div class="mt-6">
                    {{ $entries->links() }}
                </div>
            @endif
        @endif

    </div>

    {{-- Debounce: auto-submit search form 400ms after user stops typing --}}
    @push('scripts')
    <script>
    (function () {
        var input  = document.getElementById('kamus-input');
        var form   = document.getElementById('kamus-search-form');
        var timer  = null;

        if (!input || !form) return;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                form.submit();
            }, 400);
        });

        // Focus search bar on page load for faster interaction
        input.focus();
    }());
    </script>
    @endpush

</x-app-layout>
