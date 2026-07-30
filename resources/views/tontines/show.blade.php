<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tontine->name }}</h2>
    </x-slot>
        
    @foreach ($flags as $flag)
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
        ⚠️ {{ $flag->message }}
        <form method="POST" action="{{ route('tontines.resolve-flag', $flag->id) }}" class="inline">
            @csrf
            <button class="ml-2 underline text-sm">Mark as reviewed</button>
        </form>
    </div>
@endforeach

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <p class="text-gray-600 mb-2">{{ $tontine->description }}</p>
                <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                    <div><span class="text-gray-500">Organizer:</span> {{ $tontine->creator->name }}</div>
                    <div><span class="text-gray-500">Contribution:</span> {{ number_format($tontine->contribution_amount) }} CFA</div>
                    <div><span class="text-gray-500">Frequency:</span> {{ ucfirst($tontine->frequency) }}</div>
                    <div><span class="text-gray-500">Current Round:</span> {{ $tontine->current_round }}</div>
                    <div><span class="text-gray-500">Status:</span> {{ ucfirst($tontine->status) }}</div>
                </div>

                @if (auth()->id() === $tontine->creator_id)
                    <a href="{{ route('tontines.manage-members', $tontine->id) }}" class="inline-block mt-4 text-indigo-600 hover:underline">
                        Manage Members →
                    </a>
                @endif

                <a href="{{ route('tontines.payouts', $tontine->id) }}" class="inline-block mt-4 ml-4 text-indigo-600 hover:underline">
                  View Payout History →
                </a>
             
                @if (auth()->id() === $tontine->creator_id)
    <a href="{{ route('tontines.edit', $tontine->id) }}" class="inline-block mt-4 ml-4 text-indigo-600 hover:underline">
        Edit Tontine →
    </a>
      @endif
            </div>
      @if (auth()->id() === $tontine->creator_id)
    <a href="{{ route('reports.tontine-summary', $tontine->id) }}" class="inline-block mt-4 ml-4 text-indigo-600 hover:underline">
        View Report →
    </a>
@endif
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Active Members</h3>
                <ul class="divide-y">
                    @foreach ($tontine->members->where('pivot.status', 'active') as $member)
                        <li class="py-2 flex justify-between">
                            <span>{{ $member->name }}</span>
                            <span class="text-sm text-gray-500">Position {{ $member->pivot->position_in_cycle }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            
          @if ($memberStatus === 'active')
         <a href="{{ route('tontines.contribute', $tontine->id) }}" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
              Make Contribution →
         </a>
    @endif
          
          @if ($tontine->status === 'completed' && auth()->id() === $tontine->creator_id)
    <div class="bg-gold-100/40 border border-gold-300 rounded-lg p-5 mt-4">
        <p class="text-sm text-ink mb-3">This tontine has completed its full cycle. What would you like to do?</p>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('tontines.renew', $tontine->id) }}">
                @csrf
                <button class="bg-trust-600 text-white px-4 py-2 rounded-md text-sm hover:bg-trust-700">
                    Start New Cycle
                </button>
            </form>
            <form method="POST" action="{{ route('tontines.destroy', $tontine->id) }}"
                  onsubmit="return confirm('This will permanently delete this tontine and all its history. Are you sure?');">
                @csrf
                @method('DELETE')
                <button class="bg-clay-500 text-white px-4 py-2 rounded-md text-sm hover:bg-clay-600">
                    Delete Tontine
                </button>
            </form>
        </div>
    </div>
@endif
        </div>
    </div>
</x-app-layout>