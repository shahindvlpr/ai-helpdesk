{{-- resources/views/dashboard/customer.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI HelpDesk - Dashboard</title>
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
            background: #F8FAFC;
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

        .welcome-title span {
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

        /* ===== Stats Grid ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-card .icon-wrapper svg {
            width: 24px;
            height: 24px;
        }

        .stat-card .stat-label {
            font-size: 14px;
            color: #64748B;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.5px;
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

        /* ===== Stat Card Colors ===== */
        .stat-card.blue .icon-wrapper {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .stat-card.yellow .icon-wrapper {
            background: #FEF3C7;
            color: #F59E0B;
        }

        .stat-card.green .icon-wrapper {
            background: #DCFCE7;
            color: #22C55E;
        }

        .stat-card.red .icon-wrapper {
            background: #FEE2E2;
            color: #EF4444;
        }

        .stat-card.purple .icon-wrapper {
            background: #F3E8FF;
            color: #8B5CF6;
        }

        /* ===== Quick Actions ===== */
        .actions-section {
            margin-bottom: 2rem;
        }

        .actions-section .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 1rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #E2E8F0;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            border-color: #4F46E5;
        }

        .action-card .action-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .action-card .action-icon.blue {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .action-card .action-icon.green {
            background: #DCFCE7;
            color: #22C55E;
        }

        .action-card .action-icon.purple {
            background: #F3E8FF;
            color: #8B5CF6;
        }

        .action-card .action-icon.orange {
            background: #FEF3C7;
            color: #F59E0B;
        }

        .action-card .action-icon svg {
            width: 20px;
            height: 20px;
        }

        .action-card .action-info {
            flex: 1;
        }

        .action-card .action-info .action-name {
            font-size: 15px;
            font-weight: 600;
            color: #0F172A;
        }

        .action-card .action-info .action-desc {
            font-size: 13px;
            color: #64748B;
        }

        /* ===== Recent Tickets ===== */
        .recent-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #E2E8F0;
        }

        .recent-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .recent-header .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
        }

        .recent-header .view-all {
            color: #4F46E5;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .recent-header .view-all:hover {
            color: #4338CA;
        }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: #F1F5F9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
        }

        .empty-state .empty-icon svg {
            width: 40px;
            height: 40px;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 4px;
        }

        .empty-state p {
            color: #64748B;
            font-size: 14px;
        }

        /* ===== Responsive ===== */
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

            .actions-grid {
                grid-template-columns: 1fr;
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
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @if(auth()->user()->isAdmin())
                            Administrator
                        @elseif(auth()->user()->isAgent())
                            Support Agent
                        @else
                            Customer
                        @endif
                    </div>
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
                Welcome back, <span>{{ auth()->user()->name }}</span> 👋
            </h1>
            <p class="welcome-subtitle">
                Here's what's happening with your support tickets today
            </p>
        </div>

        <!-- ===== Stats ===== -->
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="stat-label">Total Tickets</div>
                <div class="stat-number">{{ auth()->user()->tickets()->count() }}</div>
                <span class="stat-change up">+12% this month</span>
            </div>

            <div class="stat-card yellow">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-label">Open Tickets</div>
                <div class="stat-number">{{ auth()->user()->tickets()->where('status', 'open')->count() }}</div>
                <span class="stat-change down">Need attention</span>
            </div>

            <div class="stat-card green">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-label">Resolved</div>
                <div class="stat-number">{{ auth()->user()->tickets()->where('status', 'resolved')->count() }}</div>
                <span class="stat-change up">All resolved</span>
            </div>

            <div class="stat-card red">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-label">Critical Issues</div>
                <div class="stat-number">{{ auth()->user()->tickets()->where('priority', 'critical')->count() }}</div>
                <span class="stat-change down">Urgent!</span>
            </div>
        </div>

        <!-- ===== Quick Actions ===== -->
        <div class="actions-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="actions-grid">
                <a href="{{ route('tickets.create') }}" class="action-card">
                    <div class="action-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="action-info">
                        <div class="action-name">Create Ticket</div>
                        <div class="action-desc">Submit a new support request</div>
                    </div>
                </a>

                <a href="{{ route('tickets.index') }}" class="action-card">
                    <div class="action-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="action-info">
                        <div class="action-name">View Tickets</div>
                        <div class="action-desc">See all your support tickets</div>
                    </div>
                </a>

                <a href="{{ route('knowledge.index') }}" class="action-card">
                    <div class="action-icon purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="action-info">
                        <div class="action-name">Knowledge Base</div>
                        <div class="action-desc">Find answers in our docs</div>
                    </div>
                </a>

                <a href="{{ route('profile') }}" class="action-card">
                    <div class="action-icon orange">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="action-info">
                        <div class="action-name">My Profile</div>
                        <div class="action-desc">Update your account settings</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- ===== Recent Tickets ===== -->
        <div class="recent-section">
            <div class="recent-header">
                <h2 class="section-title">Recent Tickets</h2>
                <a href="{{ route('tickets.index') }}" class="view-all">
                    View All →
                </a>
            </div>

            @if(auth()->user()->tickets()->count() > 0)
                <livewire:ticket.index />
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3>No tickets yet</h3>
                    <p>You haven't created any tickets. Start by creating your first ticket!</p>
                    <div class="mt-4">
                        <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 inline-block">
                            Create Your First Ticket
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>

    @livewireScripts
</body>
</html>