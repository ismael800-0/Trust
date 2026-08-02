<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Members — {{ $tontine->name }}</h2>
            <a href="{{ route('tontines.show', $tontine->id) }}" class="inline-flex items-center text-sm font-medium text-blue-500 hover:text-indigo-600 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.4s ease-out both; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded animate-[fadeInUp_0.3s_ease-out]">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @foreach ($members as $index => $member)
                    <div class="fade-in p-4 flex justify-between items-center transition duration-200 hover:bg-gray-50"
                         style="animation-delay: {{ $index * 50 }}ms">
                        <div>
                            <p class="font-semibold">{{ $member->name }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($member->pivot->status) }}</p>
                        </div>

                        @if ($member->pivot->status === 'pending')
                            <div class="space-x-2">
                                <form method="POST" action="{{ route('tontines.approve-member', [$tontine->id, $member->id]) }}" class="inline">
                                    @csrf
                                    <button class="bg-green-600 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 hover:bg-green-700 active:scale-95">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('tontines.reject-member', [$tontine->id, $member->id]) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 hover:bg-red-700 active:scale-95">Reject</button>
                                </form>
                            </div>
                        @elseif ($member->pivot->status === 'active' && $member->id !== $tontine->creator_id)
                            <form method="POST" action="{{ route('tontines.remove-member', [$tontine->id, $member->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="bg-gray-600 text-white px-3 py-1.5 rounded-md text-sm transition duration-150 hover:bg-gray-700 active:scale-95">Remove</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 mt-6 transition duration-200 hover:shadow-md" style="animation-delay: 120ms">
                <h3 class="font-semibold mb-3">Adjust Rotation Order</h3>
                <form method="POST" action="{{ route('tontines.update-positions', $tontine->id) }}">
                    @csrf
                    @foreach ($members->where('pivot.status', 'active') as $member)
                        <div class="flex items-center justify-between py-2 border-b transition duration-150 hover:bg-gray-50 px-2 -mx-2 rounded">
                            <span>{{ $member->name }}</span>
                            <input
                                type="number"
                                name="positions[{{ $member->id }}]"
                                value="{{ $member->pivot->position_in_cycle }}"
                                min="1"
                                class="w-20 rounded-md border-gray-300 shadow-sm text-center transition duration-150 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                            >
                        </div>
                    @endforeach
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-150 active:scale-95">
                        Save Rotation Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>