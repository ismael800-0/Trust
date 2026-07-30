<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg p-5 transition-all duration-300 ease-out hover:-translate-y-0.5 opacity-0 animate-[fadeInUp_0.5s_ease-out_forwards]" style="animation-delay: 0ms">
                    <p class="text-xs uppercase tracking-wide text-trust-500 font-medium">Wallet Balance</p>
                    <p class="font-mono text-2xl text-ink mt-1">{{ number_format($wallet->balance, 2) }} <span class="text-sm text-trust-500">CFA</span></p>
                    <a href="{{ route('wallet.index') }}" class="text-xs text-gold-600 hover:underline inline-flex items-center gap-1 group">
                        Manage wallet <span class="transition-transform duration-200 group-hover:translate-x-0.5">&rarr;</span>
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg p-5 transition-all duration-300 ease-out hover:-translate-y-0.5 opacity-0 animate-[fadeInUp_0.5s_ease-out_forwards]" style="animation-delay: 80ms">
                    <p class="text-xs uppercase tracking-wide text-trust-500 font-medium">Active Tontines</p>
                    <p class="font-mono text-2xl text-ink mt-1">{{ $activeTontines->count() }}</p>
                    <a href="{{ route('tontines.index') }}" class="text-xs text-gold-600 hover:underline inline-flex items-center gap-1 group">
                        View all <span class="transition-transform duration-200 group-hover:translate-x-0.5">&rarr;</span>
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-sm hover:shadow-lg p-5 transition-all duration-300 ease-out hover:-translate-y-0.5 opacity-0 animate-[fadeInUp_0.5s_ease-out_forwards]" style="animation-delay: 160ms">
                    <p class="text-xs uppercase tracking-wide text-trust-500 font-medium">Find a Circle</p>
                    <p class="text-sm text-trust-500 mt-1">Browse open tontines to join</p>
                    <a href="{{ route('tontines.browse') }}" class="text-xs text-gold-600 hover:underline inline-flex items-center gap-1 group">
                        Browse tontines <span class="transition-transform duration-200 group-hover:translate-x-0.5">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-8">

                    <div>
                        <h3 class="font-display text-lg text-trust-700 mb-4">Your Active Circles</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($activeTontines as $tontine)
                                <a href="{{ route('tontines.show', $tontine->id) }}" class="bg-white rounded-lg shadow-sm hover:shadow-lg p-5 transition-all duration-300 ease-out hover:-translate-y-0.5 block opacity-0 animate-[fadeInUp_0.5s_ease-out_forwards]" style="animation-delay: {{ $loop->index * 60 }}ms">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-20 shrink-0 transition-transform duration-500 ease-out hover:rotate-6">
                                            @include('partials.rotation-wheel', ['tontine' => $tontine, 'members' => $tontine->members])
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-display text-base text-ink truncate">{{ $tontine->name }}</p>
                                            <p class="text-xs text-trust-500 font-mono mt-1">{{ number_format($tontine->contribution_amount) }} CFA / {{ $tontine->frequency }}</p>
                                            <p class="text-xs text-trust-500 mt-1">Round {{ $tontine->current_round }} of {{ $tontine->members->count() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-2 bg-white rounded-lg shadow-sm p-8 text-center text-trust-300 transition-all duration-300">
                                    You're not part of any active tontines yet.
                                    <a href="{{ route('tontines.browse') }}" class="text-gold-600 hover:underline block mt-2">Browse tontines to join &rarr;</a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if ($completedTontines->count() > 0)
                        <div>
                            <h3 class="font-display text-lg text-trust-700 mb-4">Completed Circles</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($completedTontines as $tontine)
                                    <a href="{{ route('tontines.show', $tontine->id) }}" class="bg-white rounded-lg shadow-sm hover:shadow-lg p-5 transition-all duration-300 ease-out hover:-translate-y-0.5 block opacity-75 hover:opacity-100">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 shrink-0 rounded-full bg-trust-50 flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                                                <span class="text-trust-500 text-xl">✓</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-display text-base text-ink truncate">{{ $tontine->name }}</p>
                                                <p class="text-xs text-trust-500 font-mono mt-1">{{ number_format($tontine->contribution_amount) }} CFA / {{ $tontine->frequency }}</p>
                                                <p class="text-xs text-gold-600 mt-1">Cycle complete — {{ $tontine->total_rounds_completed }} rounds</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <div>
                    <h3 class="font-display text-lg text-trust-700 mb-4">Recent Activity</h3>
                    <div class="bg-white rounded-lg shadow-sm divide-y divide-trust-50 transition-shadow duration-300 hover:shadow-md">
                        @forelse ($recentActivity as $activity)
                            <div
                                x-data="{ hidden: false }"
                                x-show="!hidden"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-x-0"
                                x-transition:leave-end="opacity-0 translate-x-2"
                                class="px-4 py-3 flex justify-between items-start gap-2 opacity-0 animate-[fadeInUp_0.4s_ease-out_forwards] transition-colors duration-200 hover:bg-trust-50/40"
                                style="animation-delay: {{ $loop->index * 50 }}ms"
                            >
                                <div class="flex-1">
                                    <div class="flex justify-between items-start gap-2">
                                        <span class="text-xs uppercase tracking-wide font-medium
                                            {{ $activity['type'] === 'deposit' ? 'text-trust-500' : ($activity['type'] === 'withdrawal' ? 'text-clay-500' : 'text-gold-600') }}">
                                            {{ ucfirst($activity['type']) }}
                                        </span>
                                        <span class="font-mono text-sm text-ink">{{ number_format($activity['amount']) }} CFA</span>
                                    </div>
                                    <p class="text-xs text-trust-500 mt-1">{{ $activity['created_at']->diffForHumans() }}</p>
                                </div>
                                <button @click="
                                    hidden = true;
                                    fetch('{{ route('dashboard.dismiss-activity', $activity['id']) }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    });
                                " class="text-trust-300 hover:text-clay-500 shrink-0 text-lg leading-none transition-colors  hover:rotate-90 transform duration-200">&times;</button>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center text-trust-300 text-sm">No recent activity.</div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</x-app-layout>