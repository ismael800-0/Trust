<x-guest-layout>
    <!-- Background Image Layer (Elevated z-index to break past layout defaults) -->
    <div class="fixed inset-0 w-full h-full bg-cover bg-center bg-no-repeat z-10" 
         style="background-image: url('https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1920');">
         <!-- Dark overlay tint to ensure maximum text readability -->
         <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    </div>

    <!-- Content Layer (Positioned beautifully over the image) -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center px-4 z-20">
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 w-full max-w-md text-center" :status="session('status')" />

        <!-- Glassmorphism Container Card -->
        <div class="w-full max-w-md backdrop-blur-md bg-white/10 p-8 rounded-3xl border border-white/20 shadow-2xl text-white">
            
            <!-- Login Header Text -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-wide">Login</h2>
                <p class="text-sm text-gray-200/80 mt-1">Welcome back, please login to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email / Username Address Field -->
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
                               autocomplete="username" 
                               placeholder="User Name / Email" />
                        
                        <!-- User SVG Icon Overlay -->
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Password Input Field with Show/Hide -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />
                    
                    <div class="relative mt-1">
                        <input id="password" 
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               type="password"
                               name="password"
                               required 
                               autocomplete="current-password" 
                               placeholder="Password" />
                        
                        <!-- Eye SVG Icon Overlay - Made clickable for toggle -->
                        <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-300 hover:text-white transition-colors focus:outline-none toggle-password"
                                data-target="password"
                                aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Remember Me & Forgot Password Utilities -->
                <div class="flex items-center justify-between mt-2 text-sm">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-white/30 text-emerald-500 bg-white/10 shadow-sm focus:ring-emerald-400 focus:ring-offset-0 focus:ring-1" name="remember">
                        <span class="ms-2 text-gray-200 text-xs">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs text-gray-300 hover:text-white underline transition-colors" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Gradient Custom Action Submission Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full text-center bg-gradient-to-r from-lime-500 to-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:from-lime-600 hover:to-emerald-700 active:scale-[0.99] transform transition-all tracking-wide text-md">
                        {{ __('Login') }}
                    </button>
                </div>

                <!-- Signup Alternate Redirection -->
                <div class="text-center mt-4 pt-1">
                    <p class="text-xs text-gray-300/90">
                        Don't have an account? 
                        <a href="#" class="font-bold text-white hover:underline ml-1">Signup</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Password Toggle -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all toggle password buttons
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Get the target input field
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    
                    if (!input) return;
                    
                    // Toggle password visibility
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    // Toggle the eye icon to eye-slash when visible
                    const svg = this.querySelector('svg');
                    if (type === 'text') {
                        // Password is visible - show eye-slash icon
                        svg.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 0 1-4.243-4.243m4.242 4.242L9.88 9.88" />
                        `;
                        this.classList.add('text-emerald-400');
                    } else {
                        // Password is hidden - show eye icon
                        svg.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        `;
                        this.classList.remove('text-emerald-400');
                    }
                });
            });
            
            // Optional: Auto-toggle when pressing Ctrl+Enter on password fields
            document.querySelectorAll('input[type="password"]').forEach(input => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && e.ctrlKey) {
                        const button = this.parentElement.querySelector('.toggle-password');
                        if (button) button.click();
                    }
                });
            });
        });
    </script>
    @endpush
</x-guest-layout>