{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HelpDesk — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ===== Reset ===== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ===== Tokens ===== */
        :root {
            --bg-page:        #0A0E1A;
            --bg-panel:       #0D1024;
            --bg-panel-alt:   #111629;
            --bg-field:       rgba(255,255,255,0.04);
            --bg-field-focus: rgba(91,95,237,0.07);

            --accent:         #5B5FED;
            --accent-hover:   #4F53D4;
            --gold:           #E8C87A;

            --text-primary:   #F8F9FF;
            --text-secondary: #8892A4;
            --text-muted:     #3E4560;

            --border:         rgba(255,255,255,0.07);
            --border-strong:  rgba(255,255,255,0.13);
            --border-accent:  rgba(91,95,237,0.30);

            --radius-sm:  8px;
            --radius-md:  10px;
            --radius-lg:  14px;
            --radius-xl:  18px;

            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== Base ===== */
        html, body {
            height: 100%;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-page);
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ===== Layout ===== */
        .layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--bg-panel);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 36px 20px 28px;
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(91,95,237,0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            bottom: -60px; right: -60px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(232,200,122,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 48px;
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: var(--accent);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(91,95,237,0.35);
        }

        .brand-icon svg {
            width: 19px; height: 19px;
            color: #fff;
        }

        .brand-text .name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .brand-text .tagline {
            font-size: 10px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Nav */
        .nav {
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.04);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: rgba(91,95,237,0.13);
            border-color: var(--border-accent);
            color: #A5A8F5;
        }

        .nav-item svg {
            width: 16px; height: 16px;
            flex-shrink: 0;
        }

        /* Status card */
        .status-card {
            margin-top: auto;
            padding: 14px 16px;
            background: rgba(255,255,255,0.025);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            position: relative;
            z-index: 1;
        }

        .status-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .status-dot {
            width: 7px; height: 7px;
            background: #22C55E;
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 0 6px rgba(34,197,94,0.6);
        }

        .status-text { font-size: 12px; color: var(--text-secondary); }
        .status-val { font-size: 12px; color: var(--gold); font-weight: 600; }

        /* ===== Main / Form area ===== */
        .main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: var(--bg-page);
            position: relative;
            overflow: hidden;
        }

        .main::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(91,95,237,0.07) 0%, transparent 65%);
            pointer-events: none;
        }

        .main::after {
            content: '';
            position: absolute;
            bottom: -100px; left: 20%;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(232,200,122,0.05) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Top accent bar */
        .accent-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, var(--accent) 30%, var(--gold) 70%, transparent 100%);
            opacity: 0.7;
            z-index: 100;
        }

        .form-wrap {
            width: 100%;
            max-width: 340px;
            position: relative;
            z-index: 1;
        }

        /* ===== Eyebrow ===== */
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(91,95,237,0.12);
            border: 1px solid var(--border-accent);
            border-radius: 20px;
            padding: 4px 13px;
            font-size: 10px;
            font-weight: 700;
            color: #A5A8F5;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .eyebrow-dot {
            width: 5px; height: 5px;
            background: var(--accent);
            border-radius: 50%;
        }

        /* ===== Heading ===== */
        .heading {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            line-height: 1.15;
            margin-bottom: 7px;
        }

        .heading em {
            font-style: normal;
            color: var(--gold);
        }

        .subheading {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 32px;
            font-weight: 400;
        }

        /* ===== Alert Messages ===== */
        .alert {
            padding: 13px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            font-size: 13px;
        }

        .alert svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }

        .alert-success {
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
        }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #FCA5A5;
        }

        .alert-error ul { list-style: none; }
        .alert-error ul li:not(:last-child) { margin-bottom: 3px; }

        /* ===== Form Fields ===== */
        .field { margin-bottom: 16px; }

        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: 0.9px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition);
        }

        .field-input {
            width: 100%;
            padding: 12px 13px 12px 40px;
            background: var(--bg-field);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 400;
            color: var(--text-primary);
            outline: none;
            transition: var(--transition);
            -webkit-appearance: none;
        }

        .field-input:focus {
            border-color: var(--accent);
            background: var(--bg-field-focus);
            box-shadow: 0 0 0 3px rgba(91,95,237,0.12);
        }

        .field-input:focus ~ .input-icon { color: var(--accent); }

        .field-input::placeholder { color: var(--text-muted); }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: var(--transition);
        }

        .pw-toggle:hover { color: var(--text-secondary); }
        .pw-toggle svg { width: 16px; height: 16px; }

        /* ===== Options row ===== */
        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0 24px;
        }

        .check-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
        }

        .check-wrap input[type="checkbox"] { display: none; }

        .check-box {
            width: 17px; height: 17px;
            border: 1.5px solid var(--border-strong);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .check-wrap input:checked + .check-box {
            background: var(--accent);
            border-color: var(--accent);
        }

        .check-wrap input:checked + .check-box::after {
            content: '✓';
            color: white;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
        }

        .check-label {
            font-size: 12px;
            color: var(--text-secondary);
            user-select: none;
        }

        .forgot-link {
            font-size: 12px;
            font-weight: 600;
            color: #A5A8F5;
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-link:hover { color: var(--text-primary); }

        /* ===== Submit button ===== */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            letter-spacing: 0.2px;
        }

        .btn-submit svg { width: 16px; height: 16px; }

        .btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(91,95,237,0.35);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ===== Divider ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 13px;
            margin: 22px 0;
        }

        .divider-line { flex: 1; height: 1px; background: var(--border); }

        .divider-text {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            white-space: nowrap;
        }

        /* ===== Social buttons ===== */
        .social-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 12px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-social:hover {
            background: rgba(255,255,255,0.06);
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        .btn-social svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* ===== Register link ===== */
        .register-text {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .register-text a {
            color: #A5A8F5;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .register-text a:hover { color: var(--text-primary); }

        /* ===== Responsive ===== */
        @media (max-width: 700px) {
            .sidebar { display: none; }

            .main { padding: 28px 20px; }

            .heading { font-size: 26px; }

            .social-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Top accent bar -->
    <div class="accent-bar" aria-hidden="true"></div>

    <div class="layout">

        <!-- ===== Sidebar ===== -->
        <aside class="sidebar" aria-label="Navigation">

            <!-- Brand -->
            <div class="brand">
                <div class="brand-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <div class="name">AI HelpDesk</div>
                    <div class="tagline">Support Platform</div>
                </div>
            </div>

            <!-- Nav -->
            <nav class="nav">
                <a href="{{ route('login') }}" class="nav-item active" aria-current="page">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Sign in
                </a>
                <a href="{{ route('register') }}" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Register
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Support
                </a>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Docs
                </a>
            </nav>

            <!-- Status -->
            <div class="status-card">
                <div class="status-label">System Status</div>
                <div class="status-row">
                    <span class="status-dot" aria-hidden="true"></span>
                    <span class="status-text">All systems&nbsp;</span>
                    <span class="status-val">operational</span>
                </div>
            </div>

        </aside>

        <!-- ===== Main / Form ===== -->
        <main class="main">
            <div class="form-wrap">

                <!-- Eyebrow -->
                <div class="eyebrow" aria-hidden="true">
                    <span class="eyebrow-dot"></span>
                    Secure Login
                </div>

                <!-- Heading -->
                <h1 class="heading">Welcome <em>back</em></h1>
                <p class="subheading">Sign in to your workspace to continue</p>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-error" role="alert">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="field">
                        <label class="field-label" for="email">Email address</label>
                        <div class="input-wrap">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="field-input"
                                placeholder="you@company.com"
                                required
                                autofocus
                                autocomplete="email"
                            >
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="field">
                        <label class="field-label" for="password">Password</label>
                        <div class="input-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="field-input"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                                style="padding-right: 40px;"
                            >
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <button type="button" class="pw-toggle" onclick="togglePassword()" aria-label="Show or hide password">
                                <svg id="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="options-row">
                        <label class="check-wrap">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="check-box"></span>
                            <span class="check-label">Keep me signed in</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @else
                            <a href="#" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Sign in to workspace
                    </button>

                </form>

                <!-- Register -->
                <p class="register-text">
                    Don't have an account? <a href="{{ route('register') }}">Create one free</a>
                </p>

                <!-- Divider -->
                <div class="divider" aria-hidden="true">
                    <div class="divider-line"></div>
                    <span class="divider-text">or continue with</span>
                    <div class="divider-line"></div>
                </div>

                <!-- Social -->
                <div class="social-grid">
                    <button type="button" class="btn-social">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </button>
                    <button type="button" class="btn-social">
                        <svg fill="currentColor" viewBox="0 0 24 24" style="color: #8892A4;" aria-hidden="true">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.15 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.62.24 2.85.12 3.15.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                        </svg>
                        GitHub
                    </button>
                </div>

            </div>
        </main>

    </div>

    <!-- ===== Scripts ===== -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                             a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                             M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532
                             l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5
                             c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411
                             m0 0L21 21"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                             -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>

    @livewireScripts
</body>
</html>