<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Payouts</h2>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-indigo-500 hover:text-blue-600 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Tontine</th>
                            <th class="px-6 py-3">Round</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($payouts as $payout)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tontines.show', $payout->tontine_id) }}" class="text-indigo-600 hover:underline">
                                        {{ $payout->tontine->name ?? 'Unknown Tontine' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">Round {{ $payout->round_number }}</td>
                                <td class="px-6 py-4 font-semibold text-green-700">{{ number_format($payout->amount, 2) }} CFA</td>
                                <td class="px-6 py-4 text-gray-500">{{ $payout->created_at->format('M d, Y H:i') }}</td>
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
</x-app-layout>