<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard']
        ]" />
    </x-slot>

    <div class="py-8">
        <x-ui.container>
            <div class="space-y-6">

                {{-- ROW 1: KPI GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <x-ui.card>
                        <div class="text-sm text-gray-500">Total Attempts</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAttempts ?? 0 }}</div>
                        <div class="mt-1 text-xs text-gray-500">All time</div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="text-sm text-gray-500">Average Score</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $avgScore ?? 0 }}%</div>
                        <div class="mt-1 text-xs text-gray-500">All attempts</div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="text-sm text-gray-500">Pass Rate</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $passRate ?? 0 }}%</div>

                        @php($pass = (int) ($passRate ?? 0))
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gray-900 h-2.5 rounded-full" style="width: {{ max(0, min(100, $pass)) }}%"></div>
                        </div>

                        <div class="mt-2 text-xs text-gray-500">Based on completed attempts</div>
                    </x-ui.card>

                    <x-ui.card>
                        <div class="text-sm text-gray-500">Current Level</div>
                        <div class="mt-2 text-lg font-semibold text-gray-900">
                            {{ $currentLevel?->name ?? '—' }}
                        </div>
                        <div class="mt-4">
                            <x-ui.button variant="primary" :href="route('dashboard.guidebook')">
                                Guidebook
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                </div>

                {{-- ROW 2: Current Level Card (optional) + Continue Learning --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Current Level & Guidebook Section (lebih detail) --}}
                    <div class="lg:col-span-1">
                        @if($currentLevel)
                            <x-ui.card>
                                <x-ui.section-title :level="3" class="mb-2">Current Level</x-ui.section-title>

                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="text-2xl font-bold text-indigo-600">{{ $currentLevel->name }}</span>

                                    @if($currentLevel->description)
                                        <span class="text-sm text-gray-600">{{ $currentLevel->description }}</span>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <x-ui.button variant="secondary" :href="route('learn.index')">
                                        Continue →
                                    </x-ui.button>
                                </div>
                            </x-ui.card>
                        @endif
                    </div>

                    {{-- Continue Learning --}}
                    <div class="lg:col-span-2">
                        <x-ui.card>
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <x-ui.section-title :level="3">Continue Learning</x-ui.section-title>
                            </div>

                            @if($lastUnfinished)
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
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

                                        <x-ui.button variant="primary" :href="route('learn.resume', $lastUnfinished->id)" class="shrink-0">
                                            Continue →
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
                </div>

                {{-- ROW 3: Attempt History + Category Performance --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Attempt History --}}
                    <div class="lg:col-span-2">
                        <x-ui.card>
                            <div class="flex items-center justify-between">
                                <x-ui.section-title :level="3">Attempt History</x-ui.section-title>
                                <a href="{{ route('attempts.index') }}" class="text-sm font-medium text-gray-800 hover:underline">
                                    View all
                                </a>
                            </div>

                            @if(empty($recentAttempts) || count($recentAttempts) === 0)
                                <div class="mt-4 text-sm text-gray-600">
                                    Belum ada attempt. Mulai belajar dulu ya.
                                    <a href="{{ route('learn.index') }}" class="font-medium text-gray-900 hover:underline">Go to Learn</a>
                                </div>
                            @else
                                <div class="mt-4 relative overflow-x-auto border border-gray-100 rounded-lg">
                                    <table class="w-full text-sm text-left text-gray-700">
                                        <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                                            <tr>
                                                <th class="px-4 py-3">Lesson</th>
                                                <th class="px-4 py-3">Score</th>
                                                <th class="px-4 py-3">Status</th>
                                                <th class="px-4 py-3">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentAttempts as $a)
                                                <tr class="bg-white border-t border-gray-100">
                                                    <td class="px-4 py-3 font-medium text-gray-900">
                                                        {{ $a['lesson'] ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        {{ $a['score'] ?? 0 }}%
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        @if(($a['passed'] ?? false) === true)
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                                Passed
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                                Failed
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-gray-600">
                                                        {{ $a['date'] ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </x-ui.card>
                    </div>

                    {{-- Right Column: Leaderboard + Category Performance --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Leaderboard Widget --}}
                        <x-ui.card>
                            <div class="flex items-center justify-between mb-4">
                                <x-ui.section-title :level="3">Leaderboard</x-ui.section-title>
                                <span class="text-xs font-medium px-2 py-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                                    Weekly
                                </span>
                            </div>

                            @if($topLeaderboard->isEmpty())
                                <p class="text-sm text-gray-500">No active learners this week.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($topLeaderboard as $index => $user)
                                        <div class="flex items-center gap-3">
                                            {{-- Rank --}}
                                            <div class="flex-none w-8 text-center font-bold text-lg">
                                                @if($index === 0) 🥇
                                                @elseif($index === 1) 🥈
                                                @elseif($index === 2) 🥉
                                                @else #{{ $index + 1 }}
                                                @endif
                                            </div>

                                            {{-- User Info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                                        {{ $user->name }}
                                                    </p>
                                                    @if($user->is_me)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">
                                                            You
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-cool-gray-500">
                                                    <span class="font-medium text-gray-900">{{ number_format($user->total_correct) }}</span> pts
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- User Rank Footer --}}
                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        @if($myRank)
                                            Your rank: <span class="font-bold text-gray-900">#{{ $myRank }}</span>
                                        @else
                                            <span class="text-gray-400">Not ranked</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('leaderboard.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                        View details →
                                    </a>
                                </div>
                            @endif
                        </x-ui.card>

                        {{-- Category Performance --}}
                        <x-ui.card>
                            <x-ui.section-title :level="3">Category Performance</x-ui.section-title>
                            <p class="mt-1 text-sm text-gray-600">Ringkasan performa per kategori</p>

                            @if(empty($categoryPerformance) || count($categoryPerformance) === 0)
                                <div class="mt-4 text-sm text-gray-600">
                                    Data kategori belum tersedia.
                                </div>
                            @else
                                <div class="mt-5 space-y-4">
                                    @foreach($categoryPerformance as $c)
                                        @php($pct = (int) ($c['percent'] ?? 0))
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-medium text-gray-800">
                                                    {{ $c['name'] ?? 'Category' }}
                                                </span>
                                                <span class="text-sm font-semibold text-gray-900">
                                                    {{ $pct }}%
                                                </span>
                                            </div>

                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-gray-900 h-2.5 rounded-full"
                                                     style="width: {{ max(0, min(100, $pct)) }}%"></div>
                                            </div>

                                            @if(isset($c['meta']))
                                                <div class="mt-1 text-xs text-gray-500">
                                                    {{ $c['meta'] }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </x-ui.card>
                    </div>
                </div>

            </div>
        </x-ui.container>
    </div>
</x-app-layout>
