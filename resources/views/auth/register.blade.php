<x-guest-layout>
    <!-- Background Image Layer (Elevated z-index to break past layout defaults) -->
    <div class="fixed inset-0 w-full h-full bg-cover bg-center bg-no-repeat z-10" 
         style="background-image: url('https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1920');">
        <!-- Overlay to keep text perfectly readable against the background -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    </div>

    <!-- Content Layer -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center px-4 py-8 z-20">
        
        <!-- Glassmorphism Container Card -->
        <div class="w-full max-w-md backdrop-blur-md bg-white/10 p-8 rounded-3xl border border-white/20 shadow-2xl text-white">
            
            <!-- Register Header Text -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-wide">Register</h2>
                <p class="text-sm text-gray-200/80 mt-1">Create your account to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name Input Field -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />
                    <div class="relative mt-1">
                        <input id="name" 
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Your Full Name" />
                        
                        <!-- Name SVG Icon Overlay -->
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21 8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Email Address Input Field -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />
                    <div class="relative mt-1">
                        <input id="email" 
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username" 
                               placeholder="Email Address" />
                        
                        <!-- Email SVG Icon Overlay -->
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
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
                               autocomplete="new-password" 
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

                <!-- Confirm Password Input Field with Show/Hide -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-200 text-xs font-semibold uppercase tracking-wider mb-1 block" />
                    <div class="relative mt-1">
                        <input id="password_confirmation" 
                               class="block w-full bg-white/10 border border-white/30 text-white placeholder-gray-300 rounded-xl py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all"
                               type="password"
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password" 
                               placeholder="Confirm Password" />
                        
                        <!-- Eye SVG Icon Overlay - Made clickable for toggle -->
                        <button type="button" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-300 hover:text-white transition-colors focus:outline-none toggle-password"
                                data-target="password_confirmation"
                                aria-label="Toggle confirm password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-300 text-xs" />
                </div>

                <!-- Gradient Custom Action Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full text-center bg-gradient-to-r from-lime-500 to-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:from-lime-600 hover:to-emerald-700 active:scale-[0.99] transform transition-all tracking-wide text-md">
                        {{ __('Register') }}
                    </button>
                </div>

                <!-- Alternative Link redirection back to Login -->
                <div class="text-center mt-4 pt-1">
                    <p class="text-xs text-gray-300/90">
                        {{ __('Already registered?') }}
                        <a href="{{ route('login') }}" class="font-bold text-white hover:underline ml-1">
                            {{ __('Login') }}
                        </a>
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