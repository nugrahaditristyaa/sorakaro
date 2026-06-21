<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Flashcard', 'url' => route('flashcards.index')],
            ['label' => $category->name],
        ]" />
    </x-slot>

    <div class="space-y-6">

        {{-- ── Header ─────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('flashcards.index') }}"
                   class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                   id="flashcard-back-btn">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">{{ $category->icon ?? '📚' }}</span>
                        <h1 class="text-xl font-bold text-gray-900">{{ $category->name }}</h1>
                    </div>
                    @if($category->description)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $category->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Counter --}}
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-xl" id="flashcard-counter">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-semibold text-blue-700">
                    Kartu <span id="current-index">1</span> dari {{ $flashcards->count() }}
                </span>
            </div>
        </div>

        {{-- ── Progress Bar ───────────────────────────────────────────── --}}
        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <div id="progress-bar"
                 class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-full transition-all duration-500 ease-out"
                 style="width: {{ $flashcards->count() > 0 ? (1 / $flashcards->count()) * 100 : 0 }}%"></div>
        </div>

        @if($flashcards->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-orange-50 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-700 mb-1">Belum ada flashcard</h2>
                <p class="text-gray-400 text-sm">Kategori ini belum memiliki kosakata.</p>
            </div>
        @else
            {{-- ── Flashcard Container ────────────────────────────────── --}}
            <div class="flex flex-col items-center">

                {{-- Card area --}}
                <div id="flashcard-area"
                     class="w-full max-w-lg cursor-pointer select-none"
                     style="perspective: 1200px;"
                     role="button"
                     tabindex="0"
                     aria-label="Klik untuk membalik kartu">

                    <div id="flashcard-inner"
                         class="relative w-full transition-transform duration-500 ease-in-out"
                         style="transform-style: preserve-3d; min-height: 320px;">

                        {{-- Front (Karo word) --}}
                        <div id="card-front"
                             class="absolute inset-0 rounded-2xl bg-white border-2 border-blue-100 shadow-lg p-8 flex flex-col items-center justify-center text-center"
                             style="backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-2">Bahasa Karo</p>
                            <h2 id="front-word" class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4"></h2>
                            <p class="text-sm text-gray-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                                Klik untuk lihat artinya
                            </p>
                        </div>

                        {{-- Back (Indonesian translation) --}}
                        <div id="card-back"
                             class="absolute inset-0 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg p-8 flex flex-col items-center justify-center text-center text-white"
                             style="backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-5">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">Bahasa Indonesia</p>
                            <h2 id="back-translation" class="text-3xl sm:text-4xl font-bold mb-4"></h2>

                            {{-- Example sentence --}}
                            <div id="example-section" class="hidden w-full mt-2 pt-4 border-t border-white/20">
                                <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">Contoh Kalimat</p>
                                <p id="example-sentence" class="text-sm text-blue-100 italic"></p>
                                <p id="example-translation" class="text-xs text-blue-200 mt-1"></p>
                            </div>

                            <p class="text-sm text-blue-200 mt-4 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                                Klik untuk kembali
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Navigation Buttons ─────────────────────────────── --}}
                <div class="flex items-center gap-4 mt-8">
                    <button id="btn-prev"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl
                                   hover:bg-gray-50 hover:border-gray-300 active:scale-95 transition-all duration-200 shadow-sm
                                   disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:active:scale-100"
                            disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Sebelumnya
                    </button>

                    <button id="btn-next"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl
                                   hover:bg-blue-700 active:scale-95 transition-all duration-200 shadow-sm hover:shadow-md cta-glow
                                   disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-blue-600 disabled:active:scale-100">
                        Selanjutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                {{-- ── Keyboard Hints ─────────────────────────────────── --}}
                <p class="text-xs text-gray-400 mt-4 hidden sm:block">
                    💡 Gunakan tombol <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-500 font-mono text-xs">←</kbd>
                    <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-500 font-mono text-xs">→</kbd> untuk navigasi,
                    <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-500 font-mono text-xs">Space</kbd> untuk balik kartu
                </p>
            </div>

            {{-- ── Completion Modal ───────────────────────────────────── --}}
            <div id="completion-modal"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center animate-fade-in">
                    <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Selesai! 🎉</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Kamu telah menyelesaikan semua <strong>{{ $flashcards->count() }}</strong> kartu dalam kategori <strong>{{ $category->name }}</strong>.
                    </p>
                    <div class="flex flex-col gap-3">
                        <button id="btn-restart"
                                class="w-full px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 active:scale-95 transition-all duration-200 cta-glow">
                            Ulangi Lagi
                        </button>
                        <a href="{{ route('flashcards.index') }}"
                           class="w-full px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-200 active:scale-95 transition-all duration-200 text-center">
                            Pilih Kategori Lain
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @if($flashcards->isNotEmpty())
    @push('scripts')
    <script>
    (function () {
        // ── Data ──────────────────────────────────────────────────────────
        var cards = @json($flashcardsJson);

        var currentIndex = 0;
        var isFlipped = false;
        var total = cards.length;

        // ── DOM refs ──────────────────────────────────────────────────────
        var inner      = document.getElementById('flashcard-inner');
        var area       = document.getElementById('flashcard-area');
        var frontWord  = document.getElementById('front-word');
        var backTrans  = document.getElementById('back-translation');
        var exSection  = document.getElementById('example-section');
        var exSentence = document.getElementById('example-sentence');
        var exTrans    = document.getElementById('example-translation');
        var curIdx     = document.getElementById('current-index');
        var progress   = document.getElementById('progress-bar');
        var btnPrev    = document.getElementById('btn-prev');
        var btnNext    = document.getElementById('btn-next');
        var modal      = document.getElementById('completion-modal');
        var btnRestart = document.getElementById('btn-restart');

        // ── Render card ──────────────────────────────────────────────────
        function render() {
            var card = cards[currentIndex];
            frontWord.textContent = card.karo;
            backTrans.textContent = card.indo;

            if (card.example) {
                exSection.classList.remove('hidden');
                exSentence.textContent = '"' + card.example + '"';
                exTrans.textContent = card.exampleTrans || '';
            } else {
                exSection.classList.add('hidden');
            }

            curIdx.textContent = currentIndex + 1;
            progress.style.width = ((currentIndex + 1) / total * 100) + '%';

            btnPrev.disabled = currentIndex === 0;
            btnNext.disabled = false;

            // Reset flip
            if (isFlipped) {
                inner.style.transform = 'rotateY(0deg)';
                isFlipped = false;
            }
        }

        // ── Flip ─────────────────────────────────────────────────────────
        function flip() {
            if (isFlipped) {
                inner.style.transform = 'rotateY(0deg)';
            } else {
                inner.style.transform = 'rotateY(180deg)';
            }
            isFlipped = !isFlipped;
        }

        // ── Navigate ─────────────────────────────────────────────────────
        function prev() {
            if (currentIndex > 0) {
                currentIndex--;
                render();
            }
        }

        function next() {
            if (currentIndex < total - 1) {
                currentIndex++;
                render();
            } else {
                // Show completion modal
                modal.classList.remove('hidden');
            }
        }

        // ── Events ───────────────────────────────────────────────────────
        area.addEventListener('click', flip);
        area.addEventListener('keydown', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                flip();
            }
        });

        btnPrev.addEventListener('click', function (e) { e.stopPropagation(); prev(); });
        btnNext.addEventListener('click', function (e) { e.stopPropagation(); next(); });

        btnRestart.addEventListener('click', function () {
            currentIndex = 0;
            modal.classList.add('hidden');
            render();
        });

        // Close modal on backdrop click
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.classList.add('hidden');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (modal && !modal.classList.contains('hidden')) return;
            if (e.key === 'ArrowLeft')  prev();
            if (e.key === 'ArrowRight') next();
            if (e.key === ' ') { e.preventDefault(); flip(); }
        });

        // ── Init ─────────────────────────────────────────────────────────
        render();
    }());
    </script>
    @endpush
    @endif
</x-app-layout>
