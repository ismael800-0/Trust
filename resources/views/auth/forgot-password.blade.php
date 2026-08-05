<x-guest-layout>
    <!-- Background Image Layer -->
    <div class="fixed inset-0 w-full h-full bg-cover bg-center bg-no-repeat z-10" 
         style="background-image: url('https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1920');">
         <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    </div>

    <!-- Content Layer -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center px-4 z-20">

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 w-full max-w-md text-center" :status="session('status')" />

        <!-- Glassmorphism Container Card -->
        <div class="w-full max-w-md backdrop-blur-md bg-white/10 p-8 rounded-3xl border border-white/20 shadow-2xl text-white">

            <!-- Header Text -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-wide">Forgot Password?</h2>
                <p class="text-sm text-gray-200/80 mt-1">No problem. Enter your email and we'll send you a reset link.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address Field -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />

                    <div class="relative mt-1">
                        <input id="email"
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="Your account email" />

                        <!-- User SVG Icon Overlay -->
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full text-center bg-gradient-to-r from-lime-500 to-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:from-lime-600 hover:to-emerald-700 active:scale-[0.99] transform transition-all tracking-wide text-md">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center mt-4 pt-1">
                    <p class="text-xs text-gray-300/90">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="font-bold text-white hover:underline ml-1">Back to Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>