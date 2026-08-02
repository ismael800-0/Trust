<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f4ec; padding: 20px; margin: 0;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 32px; border: 1px solid #e5e5e5;">
        <h2 style="color: #1f8a6f; margin-top: 0;">New Contact Form Message</h2>

        <p><strong>Name:</strong> {{ $senderName }}</p>
        <p><strong>Email:</strong> {{ $senderEmail }}</p>

        <div style="margin-top: 20px; padding: 16px; background: #f7f4ec; border-radius: 6px;">
            <p style="white-space: pre-wrap; margin: 0;">{{ $senderMessage }}</p>
        </div>

        <p style="margin-top: 32px; color: #888; font-size: 13px;">
            Sent via {{ config('app.name') }} contact form.
        </p>
    </div>
</body>
</html>