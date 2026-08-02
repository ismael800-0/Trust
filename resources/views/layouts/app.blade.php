<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TRUST') }}</title>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-paper"
             x-data="idleLock()"
             x-init="startTimer()"
             @mousemove.window="resetTimer()"
             @keydown.window="resetTimer()"
             @touchstart.window="resetTimer()"
             @scroll.window="resetTimer()">

            @include('layouts.navigation')
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-trust-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="font-display text-2xl text-trust-700">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            {{-- Lock Screen Overlay --}}
            <div x-show="locked" x-cloak
                 class="fixed inset-0 z-[999] flex items-center justify-center bg-trust-900/95 backdrop-blur-sm">
                <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-8 mx-4">
                    <div class="text-center mb-6">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-trust-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-trust-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="font-display text-xl text-trust-700">Session Locked</h2>
                        <p class="text-sm text-gray-500 mt-1">Enter your password to continue</p>
                    </div>

                    <form @submit.prevent="unlock()">
                        <input
                            type="password"
                            x-model="password"
                            placeholder="Password"
                            class="w-full rounded-md border-gray-300 shadow-sm mb-2"
                            autofocus
                        >
                        <p x-show="error" x-cloak class="text-red-600 text-sm mb-2" x-text="error"></p>

                        <button type="submit" :disabled="loading"
                                class="w-full bg-trust-600 text-white py-2.5 rounded-md hover:bg-trust-700 transition duration-150 font-semibold disabled:opacity-60">
                            <span x-show="!loading">Unlock</span>
                            <span x-show="loading">Verifying...</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
                        @csrf
                        <button class="text-sm text-gray-400 hover:text-red-500 transition duration-150">
                            Not you? Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function idleLock() {
                return {
                    locked: false,
                    password: '',
                    error: '',
                    loading: false,
                    timer: null,
                    idleLimit: 120000, // 2 minutes in ms

                    startTimer() {
                        this.timer = setTimeout(() => { this.locked = true; }, this.idleLimit);
                    },

                    resetTimer() {
                        if (this.locked) return;
                        clearTimeout(this.timer);
                        this.startTimer();
                    },

                    async unlock() {
                        this.loading = true;
                        this.error = '';
                        try {
                            const response = await fetch("{{ route('verify-password') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ password: this.password }),
                            });

                            if (response.ok) {
                                this.locked = false;
                                this.password = '';
                                this.resetTimer();
                            } else {
                                this.error = 'Incorrect password. Try again.';
                            }
                        } catch (e) {
                            this.error = 'Something went wrong. Try again.';
                        } finally {
                            this.loading = false;
                        }
                    },
                }
            }
        </script>
    </body>
</html>