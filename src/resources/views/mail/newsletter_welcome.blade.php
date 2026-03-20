<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Extended\Mind::Thesis()</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); padding: 40px; text-align: center; }
        .header-label { color: #38bdf8; font-family: monospace; font-size: 13px; letter-spacing: .05em; margin-bottom: 12px; }
        .header h1 { margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #ffffff; }
        .header p { margin: 0; font-size: 14px; color: #94a3b8; }
        .body { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 16px; }
        .text { font-size: 15px; line-height: 1.7; color: #374151; margin-bottom: 16px; }
        .topics { margin: 28px 0; }
        .topics-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px; }
        .pill-row { display: flex; flex-wrap: wrap; gap: 8px; }
        .pill { display: inline-block; padding: 5px 12px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 20px; font-size: 13px; color: #475569; font-weight: 500; }
        .cta-btn { display: inline-block; padding: 13px 28px; background: #0284c7; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 32px 0; }
        .unsubscribe { font-size: 12px; color: #9ca3af; text-align: center; margin-top: 20px; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-label">> subscription confirmed</div>
            <h1>Extended\Mind::Thesis()</h1>
            <p>PHP · Laravel · Docker · Architecture</p>
        </div>
        <div class="body">
            <div class="greeting">You're on the list!</div>
            <p class="text">
                Thanks for subscribing to <strong>Extended\Mind::Thesis()</strong>. You'll be the first to know
                when a new post goes live — no spam, no filler, just real content about building software.
            </p>
            <p class="text">
                I write about PHP, Laravel, Docker, microservices architecture, DevOps, and the occasional
                deep-dive into whatever I'm exploring. Expect practical insights from real projects.
            </p>

            <div class="topics">
                <div class="topics-label">Topics you'll see</div>
                <div class="pill-row">
                    <span class="pill">#Laravel</span>
                    <span class="pill">#PHP</span>
                    <span class="pill">#Docker</span>
                    <span class="pill">#Architecture</span>
                    <span class="pill">#DevOps</span>
                    <span class="pill">#Tailwind</span>
                </div>
            </div>

            <a href="{{ config('app.url') }}" class="cta-btn">Read the blog</a>

            <hr class="divider">

            <p class="text" style="font-size:14px; color:#6b7280;">
                — Szymon Borowski<br>
                <a href="mailto:szymon@borowski.services" style="color:#0369a1; text-decoration:none;">szymon@borowski.services</a>
            </p>

            <p class="unsubscribe">
                You subscribed with <strong>{{ $subscriberEmail }}</strong>.<br>
                If this was a mistake, simply ignore this email.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Szymon Borowski &mdash; borowski.services
        </div>
    </div>
</body>
</html>
