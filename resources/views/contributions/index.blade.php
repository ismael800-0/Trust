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

            <div class="fade-in bg-white shadow-sm sm:rounded-lg overflow-hidden transition duration-200 hover:shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-900 font-semibold">
                                <th class="px-6 py-3 whitespace-nowrap">Tontine</th>
                                <th class="px-6 py-3 whitespace-nowrap">Round</th>
                                <th class="px-6 py-3 whitespace-nowrap">Amount</th>
                                <th class="px-6 py-3 whitespace-nowrap">Status</th>
                                <th class="px-6 py-3 whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($contributions as $index => $contribution)
                                <tr class="fade-in transition duration-150 hover:bg-gray-50" style="animation-delay: {{ $index * 50 }}ms">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('tontines.show', $contribution->tontine_id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                                            {{ $contribution->tontine->name ?? 'Unknown Tontine' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Round {{ $contribution->round_number }}</td>
                                    <td class="px-6 py-4 font-semibold whitespace-nowrap">{{ number_format($contribution->amount, 2) }} CFA</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded text-xs
                                            @if($contribution->status === 'paid') bg-green-100 text-green-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($contribution->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $contribution->created_at->format('M d, Y H:i') }}</td>
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
    </div>
</x-app-layout>