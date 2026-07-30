<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Contributions
            </h2>
            <!-- Back to Dashboard Link -->
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-indigo-500 hover:text-blue-600 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Tontine</th>
                            <th class="px-6 py-3">Round</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($contributions as $contribution)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tontines.show', $contribution->tontine_id) }}" class="text-indigo-600 hover:underline">
                                        {{ $contribution->tontine->name ?? 'Unknown Tontine' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">Round {{ $contribution->round_number }}</td>
                                <td class="px-6 py-4 font-semibold">{{ number_format($contribution->amount, 2) }} CFA</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($contribution->status === 'paid') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($contribution->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $contribution->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    You haven't made any contributions yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>