<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DataQuery AI') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f9fafb;
            color: #111827;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        body.dark {
            background: #030712;
            color: #f9fafb;
        }

        /* Nav */
        .nav {
            position: sticky; top: 0; z-index: 30;
            background: rgba(255,255,255,0.9);
            border-bottom: 1px solid #e5e7eb;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .dark .nav {
            background: rgba(3,7,18,0.9);
            border-bottom-color: #1f2937;
        }
        .nav-inner {
            max-width: 1120px; margin: 0 auto;
            padding: 0 1.5rem;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo {
            display: flex; align-items: center; gap: 0.5rem;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 32px; height: 32px;
            background: #4f46e5;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-logo-icon svg { color: white; }
        .nav-logo-text {
            font-size: 0.9375rem; font-weight: 600;
            color: #111827;
        }
        .dark .nav-logo-text { color: #f9fafb; }
        .nav-actions { display: flex; align-items: center; gap: 0.5rem; }
        .btn-ghost {
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            border: 1px solid transparent;
        }
        .dark .btn-ghost { color: #9ca3af; }
        .btn-ghost:hover { background: #f3f4f6; color: #111827; }
        .dark .btn-ghost:hover { background: #1f2937; color: #f9fafb; }
        .btn-primary {
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #4338ca; }
        .theme-toggle {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: transparent;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #6b7280;
            transition: background 0.15s;
        }
        .dark .theme-toggle { border-color: #374151; color: #9ca3af; }
        .theme-toggle:hover { background: #f3f4f6; }
        .dark .theme-toggle:hover { background: #1f2937; }
        .icon-sun { display: none; }
        .icon-moon { display: block; }
        .dark .icon-sun { display: block; }
        .dark .icon-moon { display: none; }

        /* Hero */
        .hero {
            flex: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 5rem 1.5rem 4rem;
            text-align: center;
            position: relative; overflow: hidden;
        }
        .hero-glow {
            position: absolute;
            top: -120px; left: 50%; transform: translateX(-50%);
            width: 700px; height: 400px;
            background: radial-gradient(ellipse at center, rgba(79,70,229,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .dark .hero-glow {
            background: radial-gradient(ellipse at center, rgba(79,70,229,0.18) 0%, transparent 70%);
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid #e0e7ff;
            background: #eef2ff;
            font-size: 0.75rem; font-weight: 500;
            color: #4f46e5;
            margin-bottom: 1.5rem;
        }
        .dark .hero-badge {
            border-color: rgba(79,70,229,0.3);
            background: rgba(79,70,229,0.1);
            color: #a5b4fc;
        }
        .hero-badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #4f46e5;
        }
        .dark .hero-badge-dot { background: #a5b4fc; }
        .hero-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 600;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: #111827;
            max-width: 640px;
            margin-bottom: 1.25rem;
        }
        .dark .hero-title { color: #f9fafb; }
        .hero-title span { color: #4f46e5; }
        .dark .hero-title span { color: #818cf8; }
        .hero-subtitle {
            font-size: 1.0625rem;
            color: #6b7280;
            max-width: 480px;
            line-height: 1.65;
            margin-bottom: 2.25rem;
        }
        .dark .hero-subtitle { color: #9ca3af; }
        .hero-cta {
            display: flex; align-items: center; gap: 0.75rem;
            flex-wrap: wrap; justify-content: center;
        }
        .btn-cta-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.375rem;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-size: 0.9375rem; font-weight: 500;
            text-decoration: none;
            transition: background 0.15s, transform 0.1s;
            box-shadow: 0 1px 3px rgba(79,70,229,0.3), 0 4px 12px rgba(79,70,229,0.15);
        }
        .btn-cta-primary:hover { background: #4338ca; transform: translateY(-1px); }
        .btn-cta-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.375rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            color: #374151;
            font-size: 0.9375rem; font-weight: 500;
            text-decoration: none;
            transition: border-color 0.15s, background 0.15s;
        }
        .dark .btn-cta-secondary {
            border-color: #374151;
            color: #d1d5db;
        }
        .btn-cta-secondary:hover { background: #f9fafb; border-color: #d1d5db; }
        .dark .btn-cta-secondary:hover { background: #111827; }

        /* Preview card */
        .preview-wrap {
            margin-top: 3.5rem;
            width: 100%; max-width: 780px;
        }
        .preview-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 10px 30px -5px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .dark .preview-card {
            background: #111827;
            border-color: #1f2937;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3), 0 10px 30px -5px rgba(0,0,0,0.4);
        }
        .preview-bar {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .dark .preview-bar { border-bottom-color: #1f2937; }
        .preview-dot {
            width: 10px; height: 10px; border-radius: 50%;
        }
        .preview-body {
            padding: 2rem 1.5rem;
        }
        .preview-prompt {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .preview-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #eef2ff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .dark .preview-avatar { background: rgba(79,70,229,0.2); }
        .preview-bubble {
            padding: 0.625rem 0.875rem;
            border-radius: 10px;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        .bubble-user {
            background: #f3f4f6;
            color: #374151;
            border-radius: 10px 10px 10px 2px;
        }
        .dark .bubble-user { background: #1f2937; color: #d1d5db; }
        .bubble-ai {
            background: #eef2ff;
            color: #3730a3;
            border-radius: 10px 10px 2px 10px;
        }
        .dark .bubble-ai { background: rgba(79,70,229,0.15); color: #a5b4fc; }
        .preview-table {
            margin-top: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            font-size: 0.8125rem;
        }
        .dark .preview-table { border-color: #374151; }
        .preview-table table { width: 100%; border-collapse: collapse; }
        .preview-table th {
            padding: 0.5rem 0.75rem;
            background: #f9fafb;
            color: #6b7280;
            font-weight: 500;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e5e7eb;
        }
        .dark .preview-table th { background: #1f2937; color: #6b7280; border-bottom-color: #374151; }
        .preview-table td {
            padding: 0.5rem 0.75rem;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }
        .dark .preview-table td { color: #d1d5db; border-bottom-color: #1f2937; }
        .preview-table tr:last-child td { border-bottom: none; }
        .badge-green {
            display: inline-block;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            background: #d1fae5;
            color: #065f46;
            font-size: 0.7rem; font-weight: 500;
        }
        .dark .badge-green { background: rgba(5,150,105,0.2); color: #6ee7b7; }

        /* Features */
        .features {
            max-width: 1120px; margin: 0 auto;
            padding: 4rem 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
        }
        .feature-card {
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: white;
        }
        .dark .feature-card { background: #111827; border-color: #1f2937; }
        .feature-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.875rem;
        }
        .feature-title {
            font-size: 0.9375rem; font-weight: 600;
            color: #111827;
            margin-bottom: 0.375rem;
        }
        .dark .feature-title { color: #f9fafb; }
        .feature-desc {
            font-size: 0.875rem;
            color: #6b7280;
            line-height: 1.6;
        }
        .dark .feature-desc { color: #9ca3af; }

        /* Footer */
        .footer {
            border-top: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.8125rem;
            color: #9ca3af;
        }
        .dark .footer { border-top-color: #1f2937; }

        @media (max-width: 640px) {
            .hero { padding: 3rem 1rem 2.5rem; }
            .preview-wrap { margin-top: 2.5rem; }
            .features { padding: 2.5rem 1rem; }
        }
    </style>
</head>
<body id="body">

    {{-- Nav --}}
    <nav class="nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                    </svg>
                </div>
                <span class="nav-logo-text">{{ config('app.name', 'DataQuery AI') }}</span>
            </a>

            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                    <svg class="icon-moon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="icon-sun" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero">
        <div class="hero-glow"></div>

        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            AI-powered SQL analytics
        </div>

        <h1 class="hero-title">
            Ask anything about your data — in <span>plain English</span>
        </h1>

        <p class="hero-subtitle">
            {{ config('app.name') }} turns natural language into SQL queries across multiple databases. No SQL knowledge needed.
        </p>

        <div class="hero-cta">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-cta-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Open Dashboard
                </a>
            @else
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-cta-primary">
                        Get started free
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-cta-secondary">Log in to your account</a>
                @endif
            @endauth
        </div>

        {{-- App preview mockup --}}
        <div class="preview-wrap">
            <div class="preview-card">
                <div class="preview-bar">
                    <div class="preview-dot" style="background:#ef4444"></div>
                    <div class="preview-dot" style="background:#f59e0b"></div>
                    <div class="preview-dot" style="background:#10b981"></div>
                    <span style="margin-left:0.5rem; font-size:0.75rem; color:#9ca3af; font-weight:500;">AI Multi-DB Analytics</span>
                </div>
                <div class="preview-body">
                    <div class="preview-prompt">
                        <div class="preview-avatar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div class="preview-bubble bubble-user">Show me top 5 TSOs by revenue this month</div>
                    </div>
                    <div class="preview-prompt" style="flex-direction:row-reverse">
                        <div class="preview-avatar" style="background:#eef2ff">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
                        </div>
                        <div style="flex:1">
                            <div class="preview-bubble bubble-ai">Here are the top 5 TSOs by revenue for this month:</div>
                            <div class="preview-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>TSO Name</th>
                                            <th>Revenue</th>
                                            <th>Sales</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Ahmed Raza</td><td>PKR 4.2M</td><td>38</td><td><span class="badge-green">Active</span></td></tr>
                                        <tr><td>Sara Khan</td><td>PKR 3.8M</td><td>31</td><td><span class="badge-green">Active</span></td></tr>
                                        <tr><td>Umar Farooq</td><td>PKR 3.1M</td><td>27</td><td><span class="badge-green">Active</span></td></tr>
                                        <tr><td>Zara Malik</td><td>PKR 2.9M</td><td>24</td><td><span class="badge-green">Active</span></td></tr>
                                        <tr><td>Ali Hassan</td><td>PKR 2.6M</td><td>21</td><td><span class="badge-green">Active</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon" style="background:#eef2ff">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div class="feature-title">Natural Language Queries</div>
            <div class="feature-desc">Type questions in plain English. The AI translates them to accurate SQL instantly.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#ecfdf5">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
            </div>
            <div class="feature-title">Multi-Database Support</div>
            <div class="feature-desc">Connect multiple databases and query across them from a single interface.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#fff7ed">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div class="feature-title">Instant Analytics</div>
            <div class="feature-desc">Get charts, tables, and summaries for sales, revenue, and KPIs in seconds.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon" style="background:#fdf4ff">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="feature-title">Organization Management</div>
            <div class="feature-desc">Manage teams, roles, and organizations with fine-grained access control.</div>
        </div>
    </section>

    <footer class="footer">
        {{ config('app.name') }} · Built on Laravel v{{ app()->version() }}
    </footer>

    <script>
        // Init theme
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
                document.getElementById('body').classList.add('dark');
            }
        })();
        function toggleTheme() {
            const isDark = document.getElementById('body').classList.contains('dark');
            document.getElementById('body').classList.toggle('dark', !isDark);
            document.documentElement.classList.toggle('dark', !isDark);
            localStorage.setItem('theme', !isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>