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
    <h1>My Financial Report</h1>
    <p><strong>Total Contributed:</strong> {{ number_format($totalContributed) }} CFA</p>
    <p><strong>Total Received:</strong> {{ number_format($totalReceived) }} CFA</p>

    <table>
        <thead>
            <tr><th>Tontine</th><th>Contributed</th><th>Received</th></tr>
        </thead>
        <tbody>
            @foreach ($tontineBreakdown as $t)
                <tr>
                    <td>{{ $t['name'] }}</td>
                    <td>{{ number_format($t['contributed']) }} CFA</td>
                    <td>{{ number_format($t['received']) }} CFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>