<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contribute — {{ $tontine->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                    @if (session('insufficient_funds'))
                        <a href="{{ route('wallet.index') }}" class="block mt-2 underline font-semibold">Go to Wallet to deposit funds →</a>
                    @endif
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-1">Round {{ $tontine->current_round }} contribution due:</p>
                <p class="text-2xl font-bold text-indigo-600 mb-4">{{ number_format($tontine->contribution_amount) }} CFA</p>
                <p class="text-sm text-gray-500 mb-6">Your wallet balance: {{ number_format(auth()->user()->wallet->balance, 2) }} CFA</p>

                <form method="POST" action="{{ route('contributions.store', $tontine->id) }}">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
                        Pay {{ number_format($tontine->contribution_amount) }} CFA from Wallet
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>