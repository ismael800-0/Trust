<x-guest-layout>
    <!-- Background Image Layer -->
    <div class="fixed inset-0 w-full h-full bg-cover bg-center bg-no-repeat z-10" 
         style="background-image: url('https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1920');">
         <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    </div>

    <!-- Content Layer -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center px-4 z-20">

        <!-- Glassmorphism Container Card -->
        <div class="w-full max-w-md backdrop-blur-md bg-white/10 p-8 rounded-3xl border border-white/20 shadow-2xl text-white">

            <!-- Header Text -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-wide">Reset Password</h2>
                <p class="text-sm text-gray-200/80 mt-1">Choose a new password for your account</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address Field -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />

                    <div class="relative mt-1">
                        <input id="email"
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               type="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Your account email" />

                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- New Password Field -->
                <div x-data="{ showPassword: false }">
                    <x-input-label for="password" :value="__('New Password')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />

                    <div class="relative mt-1">
                        <input id="password"
                               :type="showPassword ? 'text' : 'password'"
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="New password" />

                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-300 hover:text-white transition-colors">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />

                    <div class="relative mt-1">
                        <input id="password_confirmation"
                               type="password"
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Confirm new password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full text-center bg-gradient-to-r from-lime-500 to-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:from-lime-600 hover:to-emerald-700 active:scale-[0.99] transform transition-all tracking-wide text-md">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>