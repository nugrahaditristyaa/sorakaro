<x-app-layout>
    <x-slot name="header">
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Attempts']
        ]" />
    </x-slot>

    <div class="space-y-6">
        <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">Attempt History</h1>
                    <p class="mt-1 text-sm text-gray-600">Riwayat kuis yang pernah kamu kerjakan.</p>
                </div>

                <a href="{{ route('learn.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    Go to Learn
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Lesson</th>
                            <th class="px-4 py-3">Level</th>
                            <th class="px-4 py-3">Score</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($attempts as $attempt)
                            <tr class="bg-white border-t border-gray-100">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $attempt->lesson->title ?? $attempt->lesson->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $attempt->lesson->level->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ (int) ($attempt->score ?? 0) }}%
                                </td>
                                <td class="px-4 py-3">
                                    @if((bool) ($attempt->passed ?? false))
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
                                    {{ optional($attempt->created_at)->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('learn.result', [$attempt->lesson_id, $attempt->id]) }}"
                                       class="text-sm font-semibold text-gray-900 hover:underline">
                                        View result →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-600">
                                    Belum ada attempt.
                                    <a href="{{ route('learn.index') }}" class="font-semibold text-gray-900 hover:underline">
                                        Mulai belajar
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attempts->hasPages())
                <div class="px-4 py-4 border-t border-gray-100">
                    {{ $attempts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
