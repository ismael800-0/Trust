<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report — {{ $tontine->name }}</h2>
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

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 mb-6 transition duration-200 hover:shadow-md">
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
                    <a href="{{ route('reports.tontine-summary-pdf', $tontine->id) }}" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition duration-150 active:scale-95">
                        Download PDF
                    </a>
                    <a href="{{ route('reports.tontine-summary-excel', $tontine->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700 transition duration-150 active:scale-95">
                        Download Excel
                    </a>
                </div>
            </div>

            <div class="fade-in bg-white shadow-sm sm:rounded-lg overflow-hidden transition duration-200 hover:shadow-md" style="animation-delay: 120ms">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Member</th>
                            <th class="px-6 py-3">Total Contributed</th>
                            <th class="px-6 py-3">Total Received</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($memberBreakdown as $index => $member)
                            <tr class="fade-in transition duration-150 hover:bg-gray-50" style="animation-delay: {{ 150 + $index * 40 }}ms">
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