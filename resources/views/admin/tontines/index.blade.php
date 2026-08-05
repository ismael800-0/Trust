<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Tontines</h2>
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

            <div
                x-data="{ show: false }"
                x-init="requestAnimationFrame(() => show = true)"
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="bg-white shadow-sm rounded-lg overflow-hidden"
            >
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-500">
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Organizer</th>
                            <th class="px-6 py-3">Members</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($tontines as $tontine)
                            <tr
                                x-data="{ visible: false }"
                                x-init="setTimeout(() => visible = true, {{ $loop->index * 60 }})"
                                x-show="visible"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"
                                class="transition-colors duration-150 hover:bg-gray-50"
                            >
                                <td class="px-6 py-4">
                                    <a href="{{ route('tontines.show', $tontine->id) }}" class="text-indigo-600 hover:underline transition-colors duration-150">{{ $tontine->name }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $tontine->creator->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4">{{ $tontine->members_count }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs transition-colors duration-300
                                        @if($tontine->status === 'active') bg-green-100 text-green-700
                                        @elseif($tontine->status === 'archived') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($tontine->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($tontine->status === 'archived')
                                        <form method="POST" action="{{ route('admin.tontines.reactivate', $tontine->id) }}">
                                            @csrf
                                            <button class="text-xs text-green-600 hover:text-green-800 hover:underline transition-colors duration-150">Reactivate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.tontines.suspend', $tontine->id) }}">
                                            @csrf
                                            <button class="text-xs text-red-600 hover:text-red-800 hover:underline transition-colors duration-150">Archive</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tontines->links() }}
            </div>

        </div>
    </div>
</x-app-layout>