<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Wallet</h2>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 text-center">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($wallet->balance, 2) }} CFA</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-3">Deposit</h3>
                    <form method="POST" action="{{ route('wallet.deposit') }}">
                        @csrf
                        <input type="number" name="amount" placeholder="Amount (CFA)" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <input type="text" name="phone_number" placeholder="Phone number" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <select name="payment_method" class="mb-3 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <option value="MTN Mobile Money">MTN Mobile Money</option>
                            <option value="Orange Money">Orange Money</option>
                        </select>
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">Deposit</button>
                    </form>
                </div>

    <div class="bg-white rounded-lg border border-trust-100 p-6" x-data="{ amount: '', feePercent: {{ $feePercentage }} }">
    <h3 class="font-display text-lg text-trust-700 mb-1">Withdraw</h3>
    <p class="text-xs text-trust-500 mb-3">A {{ $feePercentage }}% platform fee applies to withdrawals.</p>

    <form method="POST" action="{{ route('wallet.withdraw') }}">
        @csrf
        <input
            type="number"
            name="amount"
            x-model.number="amount"
            placeholder="Amount (CFA)"
            class="mb-2 block w-full rounded-md border-gray-300 shadow-sm"
            required
        >
        <input type="text" name="phone_number" placeholder="Phone number" class="mb-2 block w-full rounded-md border-gray-300 shadow-sm" required>

        <select name="payment_method" class="mb-3 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="MTN Mobile Money">MTN Mobile Money</option>
            <option value="Orange Money">Orange Money</option>
        </select>

        <div x-show="amount > 0" x-cloak class="mb-4 p-3 bg-paper rounded-md text-sm space-y-1">
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

        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-trust-700">
            Withdraw
        </button>
    </form>
</div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Transaction History</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2">Type</th>
                            <th class="pb-2">Amount</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $tx)
                            <tr class="border-b">
                                <td class="py-2">{{ ucfirst($tx->type) }}</td>
                                <td class="py-2">{{ number_format($tx->amount, 2) }} CFA</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($tx->status === 'completed') bg-green-100 text-green-700
                                        @elseif($tx->status === 'failed')  text-red-00
                                        @else bg-yellow-00 text-yellow-00 @endif">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                                <td class="py-2">{{ $tx->created_at->format('M d, H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500 text-center">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
            </div>

        </div>
    </div>
</x-app-layout>