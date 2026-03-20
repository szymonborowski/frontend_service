<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New contact message</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); padding: 32px 40px; }
        .header-label { color: #38bdf8; font-family: monospace; font-size: 13px; letter-spacing: .05em; margin-bottom: 8px; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; color: #ffffff; }
        .body { padding: 36px 40px; }
        .meta { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px 24px; margin-bottom: 28px; }
        .meta-row { display: flex; gap: 12px; margin-bottom: 10px; font-size: 14px; }
        .meta-row:last-child { margin-bottom: 0; }
        .meta-label { color: #6b7280; width: 80px; flex-shrink: 0; font-weight: 500; }
        .meta-value { color: #111827; word-break: break-all; }
        .meta-value a { color: #0369a1; text-decoration: none; }
        .message-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px; }
        .message-body { font-size: 15px; line-height: 1.7; color: #374151; white-space: pre-wrap; background: #f8fafc; border-left: 3px solid #0ea5e9; padding: 16px 20px; border-radius: 0 8px 8px 0; }
        .reply-btn { display: inline-block; margin-top: 28px; padding: 12px 24px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-label">> new message received</div>
            <h1>Extended\Mind::Thesis()</h1>
        </div>
        <div class="body">
            <div class="meta">
                <div class="meta-row">
                    <span class="meta-label">From</span>
                    <span class="meta-value">{{ $senderName }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">E-mail</span>
                    <span class="meta-value"><a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Subject</span>
                    <span class="meta-value">{{ $subject }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Sent at</span>
                    <span class="meta-value">{{ now()->format('d.m.Y H:i') }}</span>
                </div>
            </div>

            <div class="message-label">Message</div>
            <div class="message-body">{{ $messageBody }}</div>

            <a href="mailto:{{ $senderEmail }}?subject=Re: {{ rawurlencode($subject) }}" class="reply-btn">
                Reply to {{ $senderName }}
            </a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Szymon Borowski &mdash; borowski.services
        </div>
    </div>
</body>
</html>
