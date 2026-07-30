<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Tontines</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @forelse ($tontines as $tontine)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $tontine->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($tontine->frequency) }} · {{ number_format($tontine->contribution_amount) }} CFA ·
                                {{ $tontine->members_count }}/{{ $tontine->max_members }} members
                            </p>
                        </div>
                        <form method="POST" action="{{ route('tontines.join', $tontine->id) }}">
                            @csrf
                            <button class="bg-indigo-600 text-white px-3 py-1.5 rounded-md text-sm">Request to Join</button>
                        </form>
                    </div>
                @empty
                    <p class="p-4 text-gray-500">No active tontines available right now.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>