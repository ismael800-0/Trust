<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Tontines</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
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
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tontines.show', $tontine->id) }}" class="text-indigo-600 hover:underline">{{ $tontine->name }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $tontine->creator->name ?? 'Unknown' }}</td>
                                <td class="px-6 py-4">{{ $tontine->members_count }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs
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
                                            <button class="text-xs text-green-600 hover:underline">Reactivate</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.tontines.suspend', $tontine->id) }}">
                                            @csrf
                                            <button class="text-xs text-red-600 hover:underline">Archive</button>
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