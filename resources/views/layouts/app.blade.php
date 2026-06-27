<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI HelpDesk')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-page:       #080C18;
            --bg-nav:        #0D1024;
            --accent:        #5B5FED;
            --gold:          #E8C87A;
            --text-primary:  #F8F9FF;
            --text-secondary:#8892A4;
            --text-muted:    #3E4560;
            --border:        rgba(255,255,255,0.07);
            --border-strong: rgba(255,255,255,0.12);
            --transition:    all 0.2s cubic-bezier(0.4,0,0.2,1);
        }

        html, body {
            min-height: 100%;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
        }

        /* ===== Top accent bar ===== */
        .app-accent-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, var(--accent) 30%, var(--gold) 70%, transparent 100%);
            opacity: 0.7;
            z-index: 200;
        }

        /* ===== Nav ===== */
        .app-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--bg-nav);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .app-nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        /* Brand */
        .app-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }

        .app-brand-icon {
            width: 34px; height: 34px;
            background: var(--accent);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(91,95,237,0.35);
            flex-shrink: 0;
        }

        .app-brand-icon svg { width: 17px; height: 17px; color: #fff; }

        .app-brand-name {
            font-size: 15px; font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.2px;
        }

        .app-brand-name span { color: var(--gold); }

        /* Nav links */
        .app-nav-links {
            display: flex; align-items: center; gap: 4px;
            flex: 1;
        }

        .app-nav-link {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 13px; font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .app-nav-link svg { width: 15px; height: 15px; flex-shrink: 0; }

        .app-nav-link:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text-primary);
        }

        .app-nav-link.active {
            background: rgba(91,95,237,0.12);
            border-color: rgba(91,95,237,0.25);
            color: #A5A8F5;
        }

        /* Nav right */
        .app-nav-right {
            display: flex; align-items: center; gap: 12px;
            flex-shrink: 0;
        }

        /* User dropdown */
        .app-user-btn {
            display: flex; align-items: center; gap: 9px;
            padding: 6px 12px 6px 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border-strong);
            border-radius: 10px;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 600;
            color: var(--text-primary);
        }

        .app-user-btn:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(255,255,255,0.18);
        }

        .app-user-avatar {
            width: 28px; height: 28px;
            background: var(--accent);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }

        .app-user-btn svg { width: 14px; height: 14px; color: var(--text-muted); }

        /* Dropdown menu */
        .app-dropdown {
            position: relative;
        }

        .app-dropdown-menu {
            display: none;
            position: absolute;
            right: 0; top: calc(100% + 8px);
            width: 200px;
            background: #111627;
            border: 1px solid var(--border-strong);
            border-radius: 12px;
            padding: 6px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            z-index: 999;
        }

        .app-dropdown:focus-within .app-dropdown-menu,
        .app-dropdown.open .app-dropdown-menu {
            display: block;
        }

        .app-dropdown-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px; font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            border: none; background: none;
            width: 100%; text-align: left;
            font-family: 'Outfit', sans-serif;
        }

        .app-dropdown-item svg { width: 15px; height: 15px; flex-shrink: 0; }

        .app-dropdown-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text-primary);
        }

        .app-dropdown-item.danger:hover {
            background: rgba(239,68,68,0.08);
            color: #FCA5A5;
        }

        .app-dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 5px 0;
        }

        /* Auth buttons */
        .app-btn-login {
            padding: 7px 14px;
            background: transparent;
            border: 1px solid var(--border-strong);
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        .app-btn-login:hover {
            border-color: rgba(255,255,255,0.2);
            color: var(--text-primary);
        }

        .app-btn-register {
            padding: 7px 14px;
            background: var(--accent);
            border: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 700;
            color: #fff;
            text-decoration: none;
            transition: var(--transition);
        }

        .app-btn-register:hover {
            background: #4F53D4;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(91,95,237,0.35);
        }

        /* ===== Page wrapper ===== */
        .app-page {
            min-height: calc(100vh - 60px);
            background: var(--bg-page);
            position: relative;
        }

        .app-page::before {
            content: '';
            position: fixed;
            top: 60px; right: 0;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(91,95,237,0.05) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        .app-page::after {
            content: '';
            position: fixed;
            bottom: 0; left: 10%;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(232,200,122,0.03) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        .app-main {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
            position: relative;
            z-index: 1;
        }

        /* ===== Mobile nav toggle ===== */
        .app-mobile-toggle {
            display: none;
            background: none; border: none;
            color: var(--text-secondary);
            cursor: pointer; padding: 6px;
            border-radius: 8px;
        }

        .app-mobile-toggle svg { width: 20px; height: 20px; }

        @media (max-width: 768px) {
            .app-nav-links { display: none; }
            .app-mobile-toggle { display: flex; }
            .app-main { padding: 20px 16px; }
        }
    </style>
</head>
<body>

    <!-- Top accent bar -->
    <div class="app-accent-bar" aria-hidden="true"></div>

    <!-- ===== Nav ===== -->
    <nav class="app-nav" aria-label="Main navigation">
        <div class="app-nav-inner">

            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="app-brand">
                <div class="app-brand-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="app-brand-name">AI <span>HelpDesk</span></span>
            </a>

            <!-- Nav links -->
            <div class="app-nav-links">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="app-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="app-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Users
                        </a>
                    @endif

                    @if(auth()->user()->isAgent() || auth()->user()->isAdmin())
                        <a href="{{ route('tickets.index') }}" class="app-nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            Tickets
                        </a>
                        <a href="{{ route('knowledge.index') }}" class="app-nav-link {{ request()->routeIs('knowledge.*') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Knowledge Base
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Right side -->
            <div class="app-nav-right">
                @auth
                    <div class="app-dropdown" id="userDropdown">
                        <button class="app-user-btn" onclick="toggleDropdown()" aria-expanded="false" aria-haspopup="true">
                            <div class="app-user-avatar" aria-hidden="true">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            {{ auth()->user()->name }}
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="app-dropdown-menu" role="menu">
                            <a href="{{ route('profile') }}" class="app-dropdown-item" role="menuitem">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile
                            </a>
                            <div class="app-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="app-dropdown-item danger" role="menuitem">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="app-btn-login">Sign in</a>
                    <a href="{{ route('register') }}" class="app-btn-register">Get started</a>
                @endauth

                <!-- Mobile toggle -->
                <button class="app-mobile-toggle" aria-label="Toggle menu">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    <!-- ===== Page Content ===== -->
    <div class="app-page">
        <main class="app-main">
            {{ $slot }}
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        function toggleDropdown() {
            const dd = document.getElementById('userDropdown');
            dd.classList.toggle('open');

            // close on outside click
            document.addEventListener('click', function handler(e) {
                if (!dd.contains(e.target)) {
                    dd.classList.remove('open');
                    document.removeEventListener('click', handler);
                }
            });
        }
    </script>

</body>
</html>