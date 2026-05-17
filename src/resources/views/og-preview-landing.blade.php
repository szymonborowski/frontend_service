<!DOCTYPE html>
<html lang="pl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>OG card preview — landing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { margin: 0; padding: 0; background: #020617; }
    </style>
</head>
<body>
    {{-- 1200×560 share card. Open viewport at exactly 1200×560 to capture. --}}
    <div class="relative overflow-hidden flex items-center"
         style="width: 1200px; height: 560px; background: linear-gradient(135deg, #071017 0%, #0f172a 50%, #0d2218 100%);">

        {{-- Soft glow accents --}}
        <div class="absolute opacity-35"
             style="top: -80px; right: -80px; width: 460px; height: 460px; background: radial-gradient(circle, rgba(16,185,129,0.5), transparent 70%); filter: blur(40px);"></div>
        <div class="absolute opacity-25"
             style="bottom: -120px; left: 200px; width: 560px; height: 360px; background: radial-gradient(circle, rgba(56,189,248,0.35), transparent 70%); filter: blur(60px);"></div>

        {{-- Content row --}}
        <div class="relative flex items-center gap-14 w-full" style="padding: 0 64px 0 80px;">

            {{-- Left: photo with gradient ring --}}
            <div class="relative flex-shrink-0">
                <div class="absolute inset-0 rounded-full blur-2xl opacity-50"
                     style="background: linear-gradient(135deg, #10b981, #38bdf8, #6366f1);"></div>
                <div class="relative rounded-full p-1"
                     style="background: linear-gradient(135deg, #10b981, #38bdf8, #6366f1);">
                    <img src="/images/me800x800.png"
                         alt="Szymon Borowski"
                         class="rounded-full block"
                         style="width: 280px; height: 280px; object-fit: cover;">
                </div>
            </div>

            {{-- Right: text + tags --}}
            <div class="flex-1 min-w-0">
                {{-- Greeting --}}
                <p class="text-emerald-400 font-mono mb-3" style="font-size: 22px; letter-spacing: 0.05em;">
                    > Cześć, jestem
                </p>

                {{-- Name --}}
                <h1 class="font-bold text-white leading-tight mb-3"
                    style="font-size: 64px; line-height: 1.05;">
                    Szymon Borowski
                </h1>

                {{-- Tagline with gradient --}}
                <p class="font-mono mb-7"
                   style="font-size: 24px; line-height: 1.3;">
                    <span style="background: linear-gradient(90deg, #10b981, #38bdf8, #6366f1); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        Aplikacje webowe · Automatyzacja · AI
                    </span>
                </p>

                {{-- Key tags --}}
                <div class="flex flex-wrap gap-2.5">
                    <span class="inline-flex items-center rounded-full border border-emerald-400/50 backdrop-blur-sm font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #a7f3d0;
                                 background: linear-gradient(90deg, rgba(16,185,129,0.18), rgba(56,189,248,0.18), rgba(99,102,241,0.18));
                                 box-shadow: 0 0 18px -6px rgba(16,185,129,0.35);">
                        Aplikacje webowe
                    </span>
                    <span class="inline-flex items-center rounded-full border border-emerald-400/50 backdrop-blur-sm font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #a7f3d0;
                                 background: linear-gradient(90deg, rgba(16,185,129,0.18), rgba(56,189,248,0.18), rgba(99,102,241,0.18));
                                 box-shadow: 0 0 18px -6px rgba(16,185,129,0.35);">
                        Automatyzacja
                    </span>
                    <span class="inline-flex items-center rounded-full border border-sky-300/40 font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #bae6fd; background: rgba(56,189,248,0.15);">
                        Systemy CRM
                    </span>
                    <span class="inline-flex items-center rounded-full border border-indigo-300/40 font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #c7d2fe; background: rgba(99,102,241,0.15);">
                        AI Asystenci
                    </span>
                </div>
            </div>
        </div>

        {{-- Brand footer --}}
        <div class="absolute font-mono"
             style="bottom: 24px; right: 48px; font-size: 18px; color: #6b7280;">
            <span style="color: #d1d5db;">borowski</span><span style="color: #6b7280;">.</span><span style="color: #34d399;">services</span>
        </div>
    </div>
</body>
</html>
