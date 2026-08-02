<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Payouts</h2>
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

            <div class="fade-in bg-white shadow-sm sm:rounded-lg overflow-hidden transition duration-200 hover:shadow-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-900 font-semibold">
                                <th class="px-6 py-3 whitespace-nowrap">Tontine</th>
                                <th class="px-6 py-3 whitespace-nowrap">Round</th>
                                <th class="px-6 py-3 whitespace-nowrap">Amount</th>
                                <th class="px-6 py-3 whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($payouts as $index => $payout)
                                <tr class="fade-in transition duration-150 hover:bg-gray-50" style="animation-delay: {{ $index * 50 }}ms">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('tontines.show', $payout->tontine_id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline transition duration-150">
                                            {{ $payout->tontine->name ?? 'Unknown Tontine' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Round {{ $payout->round_number }}</td>
                                    <td class="px-6 py-4 font-semibold text-green-700 whitespace-nowrap">{{ number_format($payout->amount, 2) }} CFA</td>
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $payout->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        You haven't received any payouts yet.
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