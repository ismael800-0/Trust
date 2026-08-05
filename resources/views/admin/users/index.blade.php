<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Tontines</h2>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors duration-150 hover:underline flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>
</x-slot>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="mb-4 p-4 bg-green-100 text-green-700 rounded"
                >
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4000)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="mb-4 p-4 bg-red-100 text-red-700 rounded"
                >
                    {{ session('error') }}
                </div>
            @endif

            <div
                x-data="{ show: false }"
                x-init="requestAnimationFrame(() => show = true)"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white shadow-sm rounded-lg overflow-hidden"
            >
                <!-- Mobile scroll hint -->
                <div class="sm:hidden px-6 pt-3 pb-1 text-xs text-gray-400 flex items-center gap-1">
                    Swipe to see more
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </div>

                <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
                    <table class="w-full text-sm min-w-[720px]">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-500">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Tontines Created</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($users as $user)
                                <tr
                                    x-data="{ visible: false }"
                                    x-init="setTimeout(() => visible = true, {{ $loop->index * 60 }})"
                                    x-show="visible"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-x-2"
                                    x-transition:enter-end="opacity-100 translate-x-0"
                                    class="transition-colors duration-150 hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.users.update-role', $user->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            <select name="role" class="text-xs rounded border-gray-300 transition-colors duration-150 focus:ring-2 focus:ring-indigo-300" onchange="this.form.submit()">
                                                <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                                                <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                                <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs transition-colors duration-300 {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $user->is_active ? 'Active' : 'Deactivated' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $user->created_tontines_count }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}">
                                            @csrf
                                            <button class="text-xs {{ $user->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }} hover:underline transition-colors duration-150">
                                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>