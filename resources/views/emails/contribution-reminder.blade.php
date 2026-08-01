<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f4ec; padding: 20px; margin: 0;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 32px; border: 1px solid #e5e5e5;">

        @if ($isOverdue)
            <h2 style="color: #a6402f; margin-top: 0;">Overdue Contribution</h2>
            <p>Hi {{ $member->name }},</p>
            <p>
                Your contribution of <strong>{{ number_format($tontine->contribution_amount, 2) }} CFA</strong>
                for <strong>{{ $tontine->name }}</strong> was due on
                <strong>{{ $dueDate->format('l, F j, Y') }}</strong> and has not yet been received.
            </p>
            <p>Please contribute as soon as possible to keep the rotation on track.</p>
        @else
            <h2 style="color: #1f8a6f; margin-top: 0;">Contribution Reminder</h2>
            <p>Hi {{ $member->name }},</p>
            <p>
                Your contribution of <strong>{{ number_format($tontine->contribution_amount, 2) }} CFA</strong>
                for <strong>{{ $tontine->name }}</strong> is due on
                <strong>{{ $dueDate->format('l, F j, Y') }}</strong>.
            </p>
        @endif

        <p style="margin-top: 24px;">
            <a href="{{ url('/tontines/' . $tontine->id) }}"
               style="background: #1f8a6f; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block;">
                View Tontine
            </a>
        </p>

        <p style="margin-top: 32px; color: #888; font-size: 13px;">
            Thanks,<br>
            {{ config('app.name') }}
        </p>
    </div>
</body>
</html>