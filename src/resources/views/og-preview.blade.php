<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <title>OG card preview</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { margin: 0; padding: 0; background: #020617; }
    </style>
</head>
<body>
    {{-- 1200×630 share card. Open viewport at exactly 1200×630 to capture. --}}
    <div class="relative overflow-hidden flex items-center"
         style="width: 1200px; height: 630px; background: linear-gradient(135deg, #0a0911 0%, #0f172a 50%, #1e1b4b 100%);">

        {{-- Soft glow accents --}}
        <div class="absolute opacity-40"
             style="top: -100px; right: -100px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(99,102,241,0.5), transparent 70%); filter: blur(40px);"></div>
        <div class="absolute opacity-30"
             style="bottom: -150px; left: 200px; width: 600px; height: 400px; background: radial-gradient(circle, rgba(56,189,248,0.4), transparent 70%); filter: blur(60px);"></div>

        {{-- Content row --}}
        <div class="relative flex items-center gap-12 px-20 w-full">

            {{-- Left: photo with gradient ring --}}
            <div class="relative flex-shrink-0">
                <div class="absolute inset-0 rounded-full blur-2xl opacity-50"
                     style="background: linear-gradient(135deg, #38bdf8, #6366f1, #a78bfa);"></div>
                <div class="relative rounded-full p-1"
                     style="background: linear-gradient(135deg, #38bdf8, #6366f1, #a78bfa);">
                    <img src="/images/me800x800.png"
                         alt="Szymon Borowski"
                         class="rounded-full block"
                         style="width: 320px; height: 320px; object-fit: cover;">
                </div>
            </div>

            {{-- Right: text + tags --}}
            <div class="flex-1 min-w-0">
                {{-- Greeting --}}
                <p class="text-sky-400 font-mono mb-3" style="font-size: 22px; letter-spacing: 0.05em;">
                    > Hello, World!
                </p>

                {{-- Name --}}
                <h1 class="font-bold text-white leading-tight mb-3"
                    style="font-size: 64px; line-height: 1.05;">
                    Szymon Borowski
                </h1>

                {{-- Tagline with gradient --}}
                <p class="font-mono mb-7"
                   style="font-size: 24px; line-height: 1.3;">
                    <span style="background: linear-gradient(90deg, #38bdf8, #6366f1, #a78bfa); -webkit-background-clip: text; background-clip: text; color: transparent;">
                        AI Engineer · Laravel · Kubernetes
                    </span>
                </p>

                {{-- Key tags --}}
                <div class="flex flex-wrap gap-2.5">
                    <span class="inline-flex items-center rounded-full border border-sky-400/50 backdrop-blur-sm font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #bae6fd;
                                 background: linear-gradient(90deg, rgba(56,189,248,0.18), rgba(99,102,241,0.18), rgba(167,139,250,0.18));
                                 box-shadow: 0 0 18px -6px rgba(99,102,241,0.35);">
                        Anthropic Claude
                    </span>
                    <span class="inline-flex items-center rounded-full border border-sky-400/50 backdrop-blur-sm font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #bae6fd;
                                 background: linear-gradient(90deg, rgba(56,189,248,0.18), rgba(99,102,241,0.18), rgba(167,139,250,0.18));
                                 box-shadow: 0 0 18px -6px rgba(99,102,241,0.35);">
                        RAG
                    </span>
                    <span class="inline-flex items-center rounded-full border border-cyan-300/40 font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #a5f3fc; background: rgba(34,211,238,0.15);">
                        Kubernetes
                    </span>
                    <span class="inline-flex items-center rounded-full border border-indigo-300/40 font-semibold"
                          style="font-size: 18px; padding: 6px 16px; color: #c7d2fe; background: rgba(99,102,241,0.15);">
                        Microservices
                    </span>
                </div>
            </div>
        </div>

        {{-- Brand footer --}}
        <div class="absolute font-mono text-gray-500"
             style="bottom: 32px; right: 48px; font-size: 18px;">
            <span class="text-gray-300">Extended</span><span class="text-gray-500">\</span><span class="text-sky-400">Mind</span><span class="text-sky-500">::</span><span class="text-violet-400">Thesis</span><span class="text-gray-500">()</span>
        </div>
    </div>
</body>
</html>
