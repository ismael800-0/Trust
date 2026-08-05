<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Super Admin Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 0)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-blue-500 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-blue-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-blue-600">Total Users</p>
                    <p class="text-3xl font-bold transition-colors duration-300 group-hover:text-blue-700">{{ $stats['totalUsers'] }}</p>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 60)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-purple-500 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-purple-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-purple-600">Total Tontines</p>
                    <p class="text-3xl font-bold transition-colors duration-300 group-hover:text-purple-700">{{ $stats['totalTontines'] }}</p>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 120)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-green-500 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-green-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-green-600">Active Tontines</p>
                    <p class="text-3xl font-bold text-green-600 transition-colors duration-300 group-hover:text-green-700">{{ $stats['activeTontines'] }}</p>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 180)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-gray-400 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-gray-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-gray-700">Completed Tontines</p>
                    <p class="text-3xl font-bold text-gray-600 transition-colors duration-300 group-hover:text-gray-800">{{ $stats['completedTontines'] }}</p>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 240)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-indigo-500 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-indigo-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-indigo-600">Total Contributions</p>
                    <p class="text-2xl font-bold text-indigo-600 transition-colors duration-300 group-hover:text-indigo-700">{{ number_format($stats['totalContributions']) }} CFA</p>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 300)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group bg-white shadow-sm rounded-lg p-6 border-l-4 border-amber-500 transition-all duration-300 ease-out hover:shadow-xl hover:-translate-y-1 hover:bg-amber-50"
                >
                    <p class="text-sm text-gray-500 transition-colors duration-300 group-hover:text-amber-600">Total Payouts</p>
                    <p class="text-2xl font-bold text-indigo-600 transition-colors duration-300 group-hover:text-amber-700">{{ number_format($stats['totalPayouts']) }} CFA</p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 350)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white shadow-sm rounded-lg p-6 transition-all duration-300 ease-out hover:shadow-lg"
                >
                    <h3 class="font-semibold mb-3">Recent Tontines</h3>
                    <ul class="divide-y">
                        @foreach ($recentTontines as $t)
                            <li
                                x-data="{ visible: false }"
                                x-init="setTimeout(() => visible = true, {{ 400 + $loop->index * 50 }})"
                                x-show="visible"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                class="py-2 text-sm rounded-md transition-colors duration-200 hover:bg-blue-50 hover:px-2"
                            >
                                <span class="font-medium">{{ $t->name }}</span> — by {{ $t->creator->name ?? 'Unknown' }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 350)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white shadow-sm rounded-lg p-6 transition-all duration-300 ease-out hover:shadow-lg"
                >
                    <h3 class="font-semibold mb-3">Recent Users</h3>
                    <ul class="divide-y">
                        @foreach ($recentUsers as $u)
                            <li
                                x-data="{ visible: false }"
                                x-init="setTimeout(() => visible = true, {{ 400 + $loop->index * 50 }})"
                                x-show="visible"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                class="py-2 text-sm rounded-md transition-colors duration-200 hover:bg-purple-50 hover:px-2"
                            >
                                {{ $u->name }} — <span class="text-gray-500">{{ $u->email }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 hover:underline">Manage Users →</a>
                <a href="{{ route('admin.tontines.index') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors duration-200 hover:underline">Manage Tontines →</a>
            </div>

        </div>
    </div>
</x-app-layout>