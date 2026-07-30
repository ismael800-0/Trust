@php
    $totalActive = $members->count();
    $currentRound = $tontine->current_round ?? 1;
    $beneficiaryPosition = $totalActive > 0 ? ((($currentRound - 1) % $totalActive) + 1) : null;

    $size = 160;
    $center = $size / 2;
    $radius = 58;
    $nodeRadius = 18;
@endphp

<svg viewBox="0 0 {{ $size }} {{ $size }}" class="w-full h-full">
    @if ($totalActive === 1)
        {{-- Single member: place side-by-side with the hub instead of stacking directly above it --}}
        @php
            $member = $members->first();
            $hubX = $center - 28;
            $memberX = $center + 28;
            $initial = strtoupper(substr($member->name, 0, 1));
        @endphp

        <line x1="{{ $hubX }}" y1="{{ $center }}" x2="{{ $memberX }}" y2="{{ $center }}" stroke="#CFE0D8" stroke-width="1.5" />

        <circle cx="{{ $hubX }}" cy="{{ $center }}" r="22" fill="#1B4D3E" />
        <text x="{{ $hubX }}" y="{{ $center - 2 }}" text-anchor="middle" fill="#F7F4EC" font-size="9" font-family="Inter, sans-serif">ROUND</text>
        <text x="{{ $hubX }}" y="{{ $center + 12 }}" text-anchor="middle" fill="#C99A2E" font-size="14" font-weight="600" font-family="Inter, sans-serif">{{ $currentRound }}</text>

        <circle cx="{{ $memberX }}" cy="{{ $center }}" r="{{ $nodeRadius }}" fill="#C99A2E" stroke="#A87F22" stroke-width="2.5" />
        <text x="{{ $memberX }}" y="{{ $center + 4 }}" text-anchor="middle" fill="#22201B" font-size="12" font-weight="600" font-family="Inter, sans-serif">{{ $initial }}</text>
    @else
        {{-- Multiple members: arrange in a circle around the hub --}}
        @foreach ($members as $i => $member)
            @php
                $angle = (360 / $totalActive) * $i - 90;
                $rad = deg2rad($angle);
                $x = $center + $radius * cos($rad);
                $y = $center + $radius * sin($rad);
            @endphp
            <line x1="{{ $center }}" y1="{{ $center }}" x2="{{ $x }}" y2="{{ $y }}" stroke="#CFE0D8" stroke-width="1.5" />
        @endforeach

        <circle cx="{{ $center }}" cy="{{ $center }}" r="22" fill="#1B4D3E" />
        <text x="{{ $center }}" y="{{ $center - 2 }}" text-anchor="middle" fill="#F7F4EC" font-size="9" font-family="Inter, sans-serif">ROUND</text>
        <text x="{{ $center }}" y="{{ $center + 12 }}" text-anchor="middle" fill="#C99A2E" font-size="14" font-weight="600" font-family="Inter, sans-serif">{{ $currentRound }}</text>

        @foreach ($members as $i => $member)
            @php
                $angle = (360 / $totalActive) * $i - 90;
                $rad = deg2rad($angle);
                $x = $center + $radius * cos($rad);
                $y = $center + $radius * sin($rad);
                $isBeneficiary = $member->pivot->position_in_cycle == $beneficiaryPosition;
                $initial = strtoupper(substr($member->name, 0, 1));
            @endphp
            <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $nodeRadius }}"
                fill="{{ $isBeneficiary ? '#C99A2E' : '#FFFFFF' }}"
                stroke="{{ $isBeneficiary ? '#A87F22' : '#1B4D3E' }}"
                stroke-width="{{ $isBeneficiary ? 2.5 : 1.5 }}" />
            <text x="{{ $x }}" y="{{ $y + 4 }}" text-anchor="middle"
                fill="{{ $isBeneficiary ? '#22201B' : '#1B4D3E' }}"
                font-size="12" font-weight="600" font-family="Inter, sans-serif">{{ $initial }}</text>
        @endforeach
    @endif
</svg>