{{-- resources/views/dashboard/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HelpDesk - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #F1F5F9;
            min-height: 100vh;
        }

        /* ===== Navigation ===== */
        .navbar {
            background: white;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .navbar-brand .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 18px;
        }

        .navbar-brand .brand-text {
            font-size: 20px;
            font-weight: 700;
            color: #1E293B;
            letter-spacing: -0.5px;
        }

        .navbar-brand .brand-text span {
            color: #4F46E5;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: #F1F5F9;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background: #E2E8F0;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1E293B;
        }

        .user-role {
            font-size: 11px;
            color: #64748B;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-role.admin {
            color: #7C3AED;
        }

        .logout-btn {
            padding: 8px 20px;
            background: #EF4444;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .logout-btn:hover {
            background: #DC2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ===== Notification Badge ===== */
        .notification-badge {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .notification-badge:hover {
            background: #F1F5F9;
        }

        .notification-badge .badge-count {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #EF4444;
            color: white;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-badge svg {
            width: 22px;
            height: 22px;
            color: #64748B;
        }

        /* ===== Main Container ===== */
        .main-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===== Welcome Section ===== */
        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.5px;
        }

        .welcome-title .highlight {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: #64748B;
            font-size: 16px;
            margin-top: 4px;
        }

        .welcome-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .welcome-stats .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #64748B;
        }

        .welcome-stats .stat-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .welcome-stats .stat-item .dot.green {
            background: #22C55E;
        }

        .welcome-stats .stat-item .dot.yellow {
            background: #F59E0B;
        }

        .welcome-stats .stat-item .dot.red {
            background: #EF4444;
        }

        /* ===== Stats Grid ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            border: 1px solid #E2E8F0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.blue::before { background: #3B82F6; }
        .stat-card.yellow::before { background: #F59E0B; }
        .stat-card.green::before { background: #22C55E; }
        .stat-card.purple::before { background: #8B5CF6; }
        .stat-card.red::before { background: #EF4444; }
        .stat-card.indigo::before { background: #4F46E5; }
        .stat-card.pink::before { background: #EC4899; }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
        }

        .stat-card .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card .icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card .icon-wrapper svg {
            width: 22px;
            height: 22px;
        }

        .stat-card.blue .icon-wrapper { background: #EFF6FF; color: #3B82F6; }
        .stat-card.yellow .icon-wrapper { background: #FEF3C7; color: #F59E0B; }
        .stat-card.green .icon-wrapper { background: #DCFCE7; color: #22C55E; }
        .stat-card.purple .icon-wrapper { background: #F3E8FF; color: #8B5CF6; }
        .stat-card.red .icon-wrapper { background: #FEE2E2; color: #EF4444; }
        .stat-card.indigo .icon-wrapper { background: #EEF2FF; color: #4F46E5; }
        .stat-card.pink .icon-wrapper { background: #FDF2F8; color: #EC4899; }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.5px;
            margin-top: 8px;
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: #64748B;
            font-weight: 500;
        }

        .stat-card .stat-change {
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
            display: inline-block;
            padding: 2px 10px;
            border-radius: 50px;
        }

        .stat-card .stat-change.up {
            background: #DCFCE7;
            color: #16A34A;
        }

        .stat-card .stat-change.down {
            background: #FEE2E2;
            color: #DC2626;
        }

        .stat-card .stat-change.neutral {
            background: #F1F5F9;
            color: #64748B;
        }

        /* ===== Two Column Layout ===== */
        .two-col {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .col-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #E2E8F0;
        }

        .col-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .col-card .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0F172A;
        }

        .col-card .card-link {
            color: #4F46E5;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .col-card .card-link:hover {
            color: #4338CA;
        }

        /* ===== Ticket List ===== */
        .ticket-item {
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ticket-item:last-child {
            border-bottom: none;
        }

        .ticket-item .ticket-info {
            flex: 1;
        }

        .ticket-item .ticket-id {
            font-size: 12px;
            font-weight: 600;
            color: #4F46E5;
        }

        .ticket-item .ticket-subject {
            font-size: 14px;
            font-weight: 500;
            color: #0F172A;
        }

        .ticket-item .ticket-meta {
            font-size: 12px;
            color: #94A3B8;
        }

        .ticket-item .ticket-status {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        .ticket-item .ticket-status.open {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .ticket-item .ticket-status.in_progress {
            background: #FEF3C7;
            color: #F59E0B;
        }

        .ticket-item .ticket-status.resolved {
            background: #DCFCE7;
            color: #22C55E;
        }

        .ticket-item .ticket-status.closed {
            background: #F1F5F9;
            color: #64748B;
        }

        /* ===== Quick Actions ===== */
        .action-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 1px solid #E2E8F0;
            background: white;
            text-decoration: none;
            transition: all 0.3s;
            color: #0F172A;
        }

        .action-btn:hover {
            border-color: #4F46E5;
            background: #F8FAFC;
            transform: translateX(4px);
        }

        .action-btn .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .action-btn .action-icon.blue { background: #EFF6FF; color: #3B82F6; }
        .action-btn .action-icon.green { background: #DCFCE7; color: #22C55E; }
        .action-btn .action-icon.purple { background: #F3E8FF; color: #8B5CF6; }
        .action-btn .action-icon.orange { background: #FEF3C7; color: #F59E0B; }
        .action-btn .action-icon.red { background: #FEE2E2; color: #EF4444; }

        .action-btn .action-icon svg {
            width: 18px;
            height: 18px;
        }

        .action-btn .action-text {
            flex: 1;
        }

        .action-btn .action-text .action-name {
            font-size: 14px;
            font-weight: 600;
        }

        .action-btn .action-text .action-desc {
            font-size: 12px;
            color: #64748B;
        }

        .action-btn .action-arrow {
            color: #94A3B8;
        }

        /* ===== Responsive ===== */
        @media (max-width: 1024px) {
            .two-col {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 1rem;
            }

            .main-container {
                padding: 1rem;
            }

            .welcome-title {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .welcome-stats {
                gap: 1rem;
            }

            .navbar-brand .brand-text {
                font-size: 16px;
            }

            .user-name {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .logout-btn {
                padding: 6px 14px;
                font-size: 12px;
            }

            .notification-badge {
                padding: 4px;
            }
        }
    </style>
</head>
<body>

    <!-- ===== Navigation ===== -->
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <div class="logo">AI</div>
            <div class="brand-text">AI <span>HelpDesk</span></div>
        </a>

        <div class="navbar-right">
            <!-- Notification Bell -->
            <div class="notification-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="badge-count">3</span>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role admin">Administrator</div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="logout-btn"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </nav>

    <!-- ===== Main ===== -->
    <div class="main-container">

        <!-- ===== Welcome Section ===== -->
        <div class="welcome-section">
            <h1 class="welcome-title">
                👋 Welcome back, <span class="highlight">{{ auth()->user()->name }}</span>
            </h1>
            <p class="welcome-subtitle">
                Here's your admin overview for today
            </p>
            <div class="welcome-stats">
                <span class="stat-item">
                    <span class="dot green"></span>
                    System: Online
                </span>
                <span class="stat-item">
                    <span class="dot yellow"></span>
                    {{ \App\Models\Ticket::where('status', 'open')->count() }} Open Tickets
                </span>
                <span class="stat-item">
                    <span class="dot red"></span>
                    {{ \App\Models\Ticket::where('priority', 'critical')->count() }} Critical
                </span>
                <span class="stat-item">
                    📅 {{ now()->format('F j, Y') }}
                </span>
            </div>
        </div>

        <!-- ===== Stats ===== -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-top">
                    <span class="stat-label">Total Users</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ \App\Models\User::count() }}</div>
                <span class="stat-change up">↑ 12% this month</span>
            </div>

            <div class="stat-card yellow">
                <div class="stat-top">
                    <span class="stat-label">Total Tickets</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ \App\Models\Ticket::count() }}</div>
                <span class="stat-change up">↑ 8% this week</span>
            </div>

            <div class="stat-card green">
                <div class="stat-top">
                    <span class="stat-label">Resolved</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ \App\Models\Ticket::where('status', 'resolved')->count() }}</div>
                <span class="stat-change up">✓ Completed</span>
            </div>

            <div class="stat-card purple">
                <div class="stat-top">
                    <span class="stat-label">Agents</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ \App\Models\User::where('role', 'agent')->count() }}</div>
                <span class="stat-change neutral">Active</span>
            </div>

            <div class="stat-card red">
                <div class="stat-top">
                    <span class="stat-label">Critical Issues</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ \App\Models\Ticket::where('priority', 'critical')->count() }}</div>
                <span class="stat-change down">⚠️ Needs attention</span>
            </div>

            <div class="stat-card indigo">
                <div class="stat-top">
                    <span class="stat-label">Avg Response Time</span>
                    <div class="icon-wrapper">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">2.4h</div>
                <span class="stat-change up">↓ 15% faster</span>
            </div>
        </div>

        <!-- ===== Two Column ===== -->
        <div class="two-col">
            <!-- Left: Recent Tickets -->
            <div class="col-card">
                <div class="card-header">
                    <span class="card-title">🔄 Recent Tickets</span>
                    <a href="{{ route('tickets.index') }}" class="card-link">View All →</a>
                </div>

                @php
                    $recentTickets = \App\Models\Ticket::with(['user', 'agent'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp

                @if($recentTickets->count() > 0)
                    @foreach($recentTickets as $ticket)
                        <div class="ticket-item">
                            <div class="ticket-info">
                                <div>
                                    <span class="ticket-id">#{{ $ticket->ticket_id }}</span>
                                    <span class="ticket-subject">{{ $ticket->subject }}</span>
                                </div>
                                <div class="ticket-meta">
                                    By {{ $ticket->user->name }} • {{ $ticket->created_at->diffForHumans() }}
                                    @if($ticket->agent)
                                        • Assigned to {{ $ticket->agent->name }}
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="ticket-status {{ $ticket->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-center py-8">No tickets yet</p>
                @endif
            </div>

            <!-- Right: Quick Actions -->
            <div class="col-card">
                <div class="card-header">
                    <span class="card-title">⚡ Quick Actions</span>
                </div>

                <div class="action-list">
                    <a href="{{ route('tickets.create') }}" class="action-btn">
                        <div class="action-icon blue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="action-text">
                            <div class="action-name">Create Ticket</div>
                            <div class="action-desc">Add a new support ticket</div>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="{{ route('tickets.index') }}" class="action-btn">
                        <div class="action-icon green">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="action-text">
                            <div class="action-name">Manage Tickets</div>
                            <div class="action-desc">View and manage all tickets</div>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="{{ route('knowledge.create') }}" class="action-btn">
                        <div class="action-icon purple">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="action-text">
                            <div class="action-name">Knowledge Base</div>
                            <div class="action-desc">Add or edit articles</div>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="#" class="action-btn">
                        <div class="action-icon orange">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                        </div>
                        <div class="action-text">
                            <div class="action-name">Analytics</div>
                            <div class="action-desc">View system reports</div>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>

                    <a href="#" class="action-btn">
                        <div class="action-icon red">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="action-text">
                            <div class="action-name">System Settings</div>
                            <div class="action-desc">Configure your system</div>
                        </div>
                        <span class="action-arrow">→</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>