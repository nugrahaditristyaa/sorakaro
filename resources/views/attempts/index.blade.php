@php
    $activeFilterCount = collect([
        request()->filled('lesson_id'),
        request()->filled('level_id'),
        request()->filled('status'),
    ])->filter()->count();

    $hasActiveFilters = $activeFilterCount > 0;
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dasbor', 'url' => route('dashboard')],
            ['label' => 'Riwayat Percobaan']
        ]" />
    </x-slot>

    <div class="space-y-5">

        {{-- ── Page Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 sm:p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Riwayat Percobaan</h1>
                <p class="mt-0.5 text-sm text-gray-500">Daftar seluruh kuis yang telah Anda kerjakan.</p>
            </div>
            <a href="{{ route('learn.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Pergi ke Belajar
            </a>
        </div>

        {{-- ── Collapsible Filter Panel ── --}}
        <div
            x-data="{ open: {{ $hasActiveFilters ? 'true' : 'false' }} }"
            class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden"
        >
            {{-- Filter Toggle Button Row --}}
            <div class="flex items-center justify-between px-5 py-3.5">
                <button
                    type="button"
                    @click="open = !open"
                    :class="open
                        ? 'bg-blue-600 text-white border-blue-600 shadow-sm shadow-blue-200'
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400'"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                >
                    {{-- Funnel icon --}}
                    <svg
                        class="w-4 h-4 transition-transform duration-200"
                        :class="open ? 'rotate-0' : ''"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>

                    Filter Riwayat

                    {{-- Active filter count badge --}}
                    @if($hasActiveFilters)
                        <span
                            class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full transition-colors"
                            :class="open ? 'bg-white text-blue-700' : 'bg-blue-600 text-white'"
                        >
                            {{ $activeFilterCount }}
                        </span>
                    @endif
                </button>

                {{-- Active filter chips (shown when panel is collapsed) --}}
                <div
                    x-show="!open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="flex flex-wrap items-center gap-2"
                >
                    @if($hasActiveFilters)
                        @if(request()->filled('lesson_id'))
                            @php $activeLesson = $lessonOptions->firstWhere('id', request('lesson_id')); @endphp
                            @if($activeLesson)
                                <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                                    {{ $activeLesson->title }}
                                </span>
                            @endif
                        @endif
                        @if(request()->filled('level_id'))
                            @php $activeLevel = $levelOptions->firstWhere('id', request('level_id')); @endphp
                            @if($activeLevel)
                                <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                    {{ $activeLevel->name }}
                                </span>
                            @endif
                        @endif
                        @if(request()->filled('status'))
                            <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border
                                {{ request('status') === 'passed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                {{ request('status') === 'passed' ? 'Lulus' : 'Gagal' }}
                            </span>
                        @endif
                        <a href="{{ route('attempts.index') }}"
                           class="hidden sm:inline-flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-gray-700 transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hapus
                        </a>
                    @else
                        <p class="text-xs text-gray-400 hidden sm:block">Pilih filter untuk menyaring hasil.</p>
                    @endif
                </div>
            </div>

            {{-- Collapsible Filter Body --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="border-t border-gray-100"
            >
                <form id="filter-form" action="{{ route('attempts.index') }}" method="GET">
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        {{-- Pelajaran --}}
                        <div>
                            <label for="filter-lesson" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Pelajaran
                            </label>
                            <select id="filter-lesson" name="lesson_id"
                                    onchange="document.getElementById('filter-form').submit()"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-800 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                                <option value="">Semua Pelajaran</option>
                                @foreach($lessonOptions as $lesson)
                                    <option value="{{ $lesson->id }}" {{ request('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                        {{ $lesson->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tingkat --}}
                        <div>
                            <label for="filter-level" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Tingkat
                            </label>
                            <select id="filter-level" name="level_id"
                                    onchange="document.getElementById('filter-form').submit()"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-800 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                                <option value="">Semua Tingkat</option>
                                @foreach($levelOptions as $level)
                                    <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="filter-status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Status
                            </label>
                            <select id="filter-status" name="status"
                                    onchange="document.getElementById('filter-form').submit()"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 text-sm text-gray-800 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Lulus</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                    </div>

                    {{-- Footer: Active chips + Reset --}}
                    @if($hasActiveFilters)
                        <div class="flex flex-wrap items-center gap-2 px-5 pb-4 pt-1 border-t border-gray-50">
                            <span class="text-xs font-medium text-gray-400 mr-1">Aktif:</span>

                            @if(request()->filled('lesson_id'))
                                @php $activeLesson = $lessonOptions->firstWhere('id', request('lesson_id')); @endphp
                                @if($activeLesson)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13"/></svg>
                                        {{ $activeLesson->title }}
                                    </span>
                                @endif
                            @endif

                            @if(request()->filled('level_id'))
                                @php $activeLevel = $levelOptions->firstWhere('id', request('level_id')); @endphp
                                @if($activeLevel)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                                        {{ $activeLevel->name }}
                                    </span>
                                @endif
                            @endif

                            @if(request()->filled('status'))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border
                                    {{ request('status') === 'passed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                    @if(request('status') === 'passed')
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Lulus
                                    @else
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Gagal
                                    @endif
                                </span>
                            @endif

                            <a href="{{ route('attempts.index') }}"
                               class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-semibold text-gray-500 hover:bg-gray-50 hover:text-gray-800 hover:border-gray-300 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- ── Results Table ── --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Result count bar --}}
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between gap-2">
                <p class="text-xs font-medium text-gray-500">
                    Menampilkan
                    <span class="font-semibold text-gray-800">{{ $attempts->firstItem() ?? 0 }}–{{ $attempts->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-gray-800">{{ $attempts->total() }}</span>
                    percobaan
                </p>
                @if($hasActiveFilters)
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        {{ $activeFilterCount }} Filter Aktif
                    </span>
                @endif
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Pelajaran</th>
                            <th class="px-5 py-3 font-semibold">Tingkat</th>
                            <th class="px-5 py-3 font-semibold">Skor</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($attempts as $attempt)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-medium text-gray-900">
                                    {{ $attempt->lesson->title ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($attempt->lesson?->level?->name)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                            {{ $attempt->lesson->level->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold text-gray-900">{{ (int) ($attempt->score ?? 0) }}%</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if((bool) ($attempt->passed ?? false))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Lulus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">
                                    {{ optional($attempt->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    @if($hasActiveFilters)
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Tidak ada riwayat dengan filter ini.</p>
                                                <p class="text-xs text-gray-400 mt-1">Coba ubah atau hapus filter yang aktif.</p>
                                            </div>
                                            <a href="{{ route('attempts.index') }}"
                                               class="inline-flex items-center gap-1.5 mt-1 px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Hapus Semua Filter
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Belum ada riwayat percobaan.</p>
                                                <p class="text-xs text-gray-400 mt-1">Mulai belajar untuk melihat riwayat kuis Anda di sini.</p>
                                            </div>
                                            <a href="{{ route('learn.index') }}"
                                               class="inline-flex items-center gap-1.5 mt-1 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                Mulai Belajar →
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($attempts->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
