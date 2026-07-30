<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report — {{ $tontine->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Contributions</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalContributions) }} CFA</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Payouts</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($totalPayouts) }} CFA</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('reports.tontine-summary-pdf', $tontine->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700">
                        Download PDF
                    </a>
                    <a href="{{ route('reports.tontine-summary-excel', $tontine->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700">
                        Download Excel
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Member</th>
                            <th class="px-6 py-3">Total Contributed</th>
                            <th class="px-6 py-3">Total Received</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($memberBreakdown as $member)
                            <tr>
                                <td class="px-6 py-4">{{ $member['name'] }}</td>
                                <td class="px-6 py-4">{{ number_format($member['contributed']) }} CFA</td>
                                <td class="px-6 py-4">{{ number_format($member['received']) }} CFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>