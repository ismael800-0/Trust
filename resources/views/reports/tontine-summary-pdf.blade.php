<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Tontine Report: {{ $tontine->name }}</h1>
    <p><strong>Total Contributions:</strong> {{ number_format($totalContributions) }} CFA</p>
    <p><strong>Total Payouts:</strong> {{ number_format($totalPayouts) }} CFA</p>
    <p><strong>Current Round:</strong> {{ $tontine->current_round }}</p>
    <p><strong>Rounds Completed:</strong> {{ $tontine->total_rounds_completed }}</p>

    <table>
        <thead>
            <tr><th>Member</th><th>Total Contributed</th><th>Total Received</th></tr>
        </thead>
        <tbody>
            @foreach ($memberBreakdown as $member)
                <tr>
                    <td>{{ $member['name'] }}</td>
                    <td>{{ number_format($member['contributed']) }} CFA</td>
                    <td>{{ number_format($member['received']) }} CFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>