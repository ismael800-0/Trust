<nav x-data="{ mobileOpen: false }" class="bg-trust-600 border-b border-trust-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center gap-8">
                {{-- Brand mark --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <span class="w-8 h-8 rounded-full border-2 border-gold-500 flex items-center justify-center">
                        <span class="w-3 h-3 rounded-full bg-gold-500"></span>
                    </span>
                    <span class="font-display text-xl text-white tracking-wide">TRUST</span>
                </a>

                {{-- Desktop nav links --}}
                <div class="hidden lg:flex items-center gap-1">
                    @php
                        $navLink = function($route, $label) {
                            $active = request()->routeIs($route . '*');
                            $classes = $active
                                ? 'bg-trust-700 text-white'
                                : 'text-trust-100 hover:bg-trust-500 hover:text-white';
                            return "<a href=\"" . route($route) . "\" class=\"px-3 py-2 rounded-md text-sm font-medium transition {$classes}\">{$label}</a>";
                        };
                    @endphp
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Dashboard</a>
                    <a href="{{ route('tontines.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('tontines.index') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">My Tontines</a>
                    <a href="{{ route('tontines.browse') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('tontines.browse') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Browse</a>
                    <a href="{{ route('wallet.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('wallet.index') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Wallet</a>
                    <a href="{{ route('contributions.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('contributions.index') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Contributions</a>
                    <a href="{{ route('payouts.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('payouts.index') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Payouts</a>
                    <a href="{{ route('reports.my-report') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('reports.my-report') ? 'bg-trust-700 text-white' : 'text-trust-100 hover:bg-trust-500 hover:text-white' }}">Report</a>
                    @if (auth()->user()->role === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.*') ? 'bg-gold-500 text-trust-900' : 'text-gold-300 hover:bg-trust-500 hover:text-white' }}">Admin</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">

                {{-- Notification bell --}}
                {{-- Notification bell --}}
                <div class="relative" x-data="{ open: false }" x-init="
                    $watch('open', value => {
                        if (value) {
                            fetch('{{ route('notifications.mark-read') }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                            });
                        }
                    });
                ">
                    <button @click="open = !open" class="relative w-9 h-9 flex items-center justify-center rounded-full text-trust-100 hover:bg-trust-500 hover:text-white transition">
                        <span class="text-lg">&#128276;</span>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if ($unreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-clay-500 text-white text-[10px] font-semibold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="fixed sm:absolute inset-x-4 sm:inset-x-auto top-16 sm:top-auto right-auto sm:right-0 mt-0 sm:mt-2 w-auto sm:w-80 bg-white shadow-xl rounded-lg overflow-hidden z-50 border border-trust-100">
                     <div class="px-4 py-2 bg-paper border-b border-trust-100">
                            <span class="text-xs font-semibold uppercase tracking-wide text-trust-600">Notifications</span>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse (auth()->user()->notifications->take(10) as $notification)
                                <div x-data="{ hidden: false }" x-show="!hidden" class="px-4 py-3 border-b border-trust-50 {{ $notification->read_at ? 'bg-white' : 'bg-gold-100/40' }} flex justify-between items-start gap-2">
                                    <div>
                                        <p class="text-sm text-ink">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-trust-500 font-mono mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    <button @click="
                                        hidden = true;
                                        fetch('{{ route('notifications.destroy', $notification->id) }}', {
                                            method: 'DELETE',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        });
                                    " class="text-trust-300 hover:text-clay-500 shrink-0 text-lg leading-none">&times;</button>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-trust-300 text-sm">No notifications yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Profile dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full text-trust-100 hover:bg-trust-500 hover:text-white transition">
                        <span class="w-7 h-7 rounded-full bg-gold-500 text-trust-900 text-xs font-semibold flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="text-sm font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-lg overflow-hidden z-50 border border-trust-100">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-ink hover:bg-paper">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full text-left px-4 py-2 text-sm text-clay-500 hover:bg-paper">Log Out</button>
                        </form>
                    </div>
                </div>

                {{-- Mobile menu toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile nav --}}
    <div x-show="mobileOpen" x-cloak class="lg:hidden border-t border-trust-700 bg-trust-600 px-2 py-3 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Dashboard</a>
        <a href="{{ route('tontines.index') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">My Tontines</a>
        <a href="{{ route('tontines.browse') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Browse</a>
        <a href="{{ route('wallet.index') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Wallet</a>
        <a href="{{ route('contributions.index') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Contributions</a>
        <a href="{{ route('payouts.index') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Payouts</a>
        <a href="{{ route('reports.my-report') }}" class="block px-3 py-2 rounded-md text-trust-100 hover:bg-trust-500 hover:text-white">Report</a>
        @if (auth()->user()->role === 'super_admin')
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-gold-300 hover:bg-trust-500 hover:text-white">Admin</a>
        @endif
    </div>
</nav>