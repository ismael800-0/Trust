<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tontine->name }}</h2>
            <a href="{{ route('tontines.index') }}" class="inline-flex items-center text-sm font-medium text-blue-500 hover:text-indigo-600 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to My Tontines
            </a>
        </div>
    </x-slot>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeInUp 0.4s ease-out both;
        }
    </style>

    @foreach ($flags as $index => $flag)
        <div class="fade-in mb-4 p-4 bg-yellow-100 text-yellow-800 rounded" style="animation-delay: {{ $index * 60 }}ms">
            ⚠️ {{ $flag->message }}
            <form method="POST" action="{{ route('tontines.resolve-flag', $flag->id) }}" class="inline">
                @csrf
                <button class="ml-2 underline text-sm hover:text-yellow-900 transition duration-150">Mark as reviewed</button>
            </form>
        </div>
    @endforeach

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded animate-[fadeInUp_0.3s_ease-out]">{{ session('success') }}</div>
            @endif

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 mb-6 transition duration-200 hover:shadow-md">
                <p class="text-gray-600 mb-2">{{ $tontine->description }}</p>
                <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                    <div><span class="text-gray-500">Organizer:</span> {{ $tontine->creator->name }}</div>
                    <div><span class="text-gray-500">Contribution:</span> {{ number_format($tontine->contribution_amount) }} CFA</div>
                    <div><span class="text-gray-500">Frequency:</span> {{ ucfirst($tontine->frequency) }}</div>
                    <div><span class="text-gray-500">Current Round:</span> {{ $tontine->current_round }}</div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'completed' => 'bg-gray-100 text-gray-600',
                                'archived' => 'bg-gray-100 text-gray-400',
                            ];
                            $statusClass = $statusColors[$tontine->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($tontine->status) }}
                        </span>
                    </div>
                </div>

                @if (auth()->id() === $tontine->creator_id)
                    <a href="{{ route('tontines.manage-members', $tontine->id) }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                        Manage Members →
                    </a>
                @endif

                <a href="{{ route('tontines.payouts', $tontine->id) }}" class="inline-block mt-4 ml-4 text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                    View Payout History →
                </a>

                @if (auth()->id() === $tontine->creator_id)
                    <a href="{{ route('tontines.edit', $tontine->id) }}" class="inline-block mt-4 ml-4 text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                        Edit Tontine →
                    </a>
                @endif
            </div>

            @if (auth()->id() === $tontine->creator_id)
                <a href="{{ route('reports.tontine-summary', $tontine->id) }}" class="inline-block mt-4 ml-4 mb-4 text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                    View Report →
                </a>
            @endif

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 transition duration-200 hover:shadow-md" style="animation-delay: 100ms">
                <h3 class="font-semibold mb-3">Active Members</h3>
                <ul class="divide-y">
                    @foreach ($tontine->members->where('pivot.status', 'active') as $index => $member)
                        <li class="fade-in py-2 flex justify-between transition duration-150 hover:bg-gray-50 px-2 -mx-2 rounded"
                            style="animation-delay: {{ 150 + $index * 40 }}ms">
                            <span>{{ $member->name }}</span>
                            <span class="text-sm text-gray-500">Position {{ $member->pivot->position_in_cycle }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($memberStatus === 'active')
                <a href="{{ route('tontines.contribute', $tontine->id) }}" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-150 active:scale-95">
                    Make Contribution →
                </a>
            @endif

            @if ($tontine->status === 'completed' && auth()->id() === $tontine->creator_id)
                <div class="fade-in bg-gold-100/40 border border-gold-300 rounded-lg p-5 mt-4">
                    <p class="text-sm text-ink mb-3">This tontine has completed its full cycle. What would you like to do?</p>
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('tontines.renew', $tontine->id) }}">
                            @csrf
                            <button class="bg-trust-600 text-white px-4 py-2 rounded-md text-sm hover:bg-trust-700 transition duration-150 active:scale-95">
                                Start New Cycle
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tontines.destroy', $tontine->id) }}"
                              onsubmit="return confirm('This will permanently delete this tontine and all its history. Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="bg-clay-500 text-white px-4 py-2 rounded-md text-sm hover:bg-clay-600 transition duration-150 active:scale-95">
                                Delete Tontine
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>