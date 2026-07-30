<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payout History — {{ $tontine->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Round</th>
                            <th class="px-6 py-3">Beneficiary</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($payouts as $payout)
                            <tr>
                                <td class="px-6 py-4">Round {{ $payout->round_number }}</td>
                                <td class="px-6 py-4">{{ $payout->beneficiary->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4 font-semibold text-green-700">{{ number_format($payout->amount, 2) }} CFA</td>
                                <td class="px-6 py-4 text-gray-500">{{ $payout->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No payouts have been made yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>