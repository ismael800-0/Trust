<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tontine</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('tontines.update', $tontine->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tontine Name</label>
                        <input type="text" name="name" value="{{ old('name', $tontine->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $tontine->description) }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Maximum Members</label>
                    <input type="number" name="max_members" value="{{ old('max_members', $tontine->max_members) }}" min="{{ $tontine->members()->wherePivot('status', 'active')->count() }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <p class="text-xs text-gray-500 mt-1">Currently {{ $tontine->members()->wherePivot('status', 'active')->count() }} active member(s). You can't set this below that number.</p>
               </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Save Changes
                        </button>
                        <a href="{{ route('tontines.show', $tontine->id) }}" class="text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>