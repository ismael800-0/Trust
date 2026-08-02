<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wallet</h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-blue-500 hover:text-indigo-600 transition duration-150 ease-in-out">
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
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded animate-[fadeInUp_0.3s_ease-out]">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded animate-[fadeInUp_0.3s_ease-out]">{{ session('error') }}</div>
            @endif

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 mb-6 text-center transition duration-200 hover:shadow-md">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($wallet->balance, 2) }} CFA</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 transition duration-200 hover:shadow-md" style="animation-delay: 60ms">
                    <h3 class="font-semibold mb-3">Deposit</h3>
                    <form method="POST" action="{{ route('wallet.deposit') }}">
                        @csrf
                        <input type="number" name="amount" placeholder="Amount (CFA)" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-green-400 focus:border-green-400" required>
                        <input type="text" name="phone_number" placeholder="Phone number" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-green-400 focus:border-green-400" required>
                        <select name="payment_method" class="mb-3 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-green-400 focus:border-green-400" required>
                            <option value="MTN Mobile Money">MTN Mobile Money</option>
                            <option value="Orange Money">Orange Money</option>
                        </select>
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition duration-150 active:scale-95 font-bold">Deposit</button>
                    </form>
                </div>

                <div class="fade-in bg-white rounded-lg border border-trust-100 p-6 transition duration-200 hover:shadow-md" style="animation-delay: 120ms" x-data="{ amount: '', feePercent: {{ $feePercentage }} }">
                    <h3 class="font-display text-lg text-trust-700 mb-1">Withdraw</h3>
                    <p class="text-xs text-trust-500 mb-3">A {{ $feePercentage }}% platform fee applies to withdrawals.</p>

                    <form method="POST" action="{{ route('wallet.withdraw') }}">
                        @csrf
                        <input
                            type="number"
                            name="amount"
                            x-model.number="amount"
                            placeholder="Amount (CFA)"
                            class="mb-2 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                            required
                        >
                        <input type="text" name="phone_number" placeholder="Phone number" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>

                        <select name="payment_method" class="mb-3 block w-full rounded-md border-gray-300 shadow-sm transition duration-150 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                            <option value="MTN Mobile Money">MTN Mobile Money</option>
                            <option value="Orange Money">Orange Money</option>
                        </select>

                        <div x-show="amount > 0" x-cloak x-transition class="mb-4 p-3 bg-paper rounded-md text-sm space-y-1">
                            <div class="flex justify-between text-trust-500">
                                <span>You will receive</span>
                                <span class="font-mono" x-text="amount.toLocaleString() + ' CFA'"></span>
                            </div>
                            <div class="flex justify-between text-clay-500">
                                <span>Platform fee (<span x-text="feePercent"></span>%)</span>
                                <span class="font-mono" x-text="'+' + Math.round(amount * feePercent / 100).toLocaleString() + ' CFA'"></span>
                            </div>
                            <div class="flex justify-between text-ink font-semibold border-t border-trust-100 pt-1 mt-1">
                                <span>Total deducted from wallet</span>
                                <span class="font-mono" x-text="Math.round(amount + (amount * feePercent / 100)).toLocaleString() + ' CFA'"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-trust-700 font-bold transition duration-150 active:scale-95">
                            Withdraw
                        </button>
                    </form>
                </div>
            </div>

            <div class="fade-in bg-white shadow-sm sm:rounded-lg p-6 transition duration-200 hover:shadow-md" style="animation-delay: 180ms">
                <h3 class="font-semibold mb-3">Transaction History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-900 font-semibold border-b">
                                <th class="pb-2 pr-4 whitespace-nowrap">Type</th>
                                <th class="pb-2 pr-4 whitespace-nowrap">Amount</th>
                                <th class="pb-2 pr-4 whitespace-nowrap">Status</th>
                                <th class="pb-2 whitespace-nowrap">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $index => $tx)
                                <tr class="fade-in border-b transition duration-150 hover:bg-gray-50" style="animation-delay: {{ $index * 50 }}ms">
                                    <td class="py-2 pr-4 whitespace-nowrap">{{ ucfirst($tx->type) }}</td>
                                    <td class="py-2 pr-4 whitespace-nowrap">{{ number_format($tx->amount, 2) }} CFA</td>
                                    <td class="py-2 pr-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded text-xs
                                            @if($tx->status === 'completed') bg-green-100 text-green-700
                                            @elseif($tx->status === 'failed') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ ucfirst($tx->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2 text-gray-500 whitespace-nowrap">{{ $tx->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-gray-500 text-center">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $transactions->links() }}
            </div>

        </div>
    </div>

    @php
        $latestTx = $transactions->first();
    @endphp

    @if ($latestTx && $latestTx->status === 'pending')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const latestTransactionId = {{ $latestTx->id }};

        const pollInterval = setInterval(async () => {
            try {
                const response = await fetch("{{ route('wallet.status') }}", {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.latest_id === latestTransactionId && data.latest_status !== 'pending') {
                    clearInterval(pollInterval);
                    window.location.reload();
                }
            } catch (error) {
                console.error('Wallet status check failed:', error);
            }
        }, 3000);
    });
    </script>
    @endif

</x-app-layout>