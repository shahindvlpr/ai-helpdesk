{{-- resources/views/livewire/ticket/index.blade.php --}}
<div>
    <style>
        .ti-root {
            --accent:         #4F46E5;
            --accent-hover:   #4338CA;
            --gold:           #D97706;
            --bg-card:        #FFFFFF;
            --bg-field:       #F9FAFB;
            --bg-field-focus: #EEF2FF;
            --text-primary:   #111827;
            --text-secondary: #6B7280;
            --text-muted:     #9CA3AF;
            --border:         #E5E7EB;
            --border-strong:  #D1D5DB;
            --border-accent:  #C7D2FE;
            --radius:         10px;
            --transition:     all 0.2s cubic-bezier(0.4,0,0.2,1);
            --shadow:         0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
        }

        /* ===== Page header ===== */
        .ti-page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .ti-page-title {
            font-size: 22px; font-weight: 800;
            color: var(--text-primary); letter-spacing: -0.4px;
        }

        .ti-page-sub {
            font-size: 13px; color: var(--text-secondary);
            margin-top: 3px;
        }

        .ti-btn-create {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px;
            background: var(--accent);
            border: none; border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 700; color: #fff;
            text-decoration: none; cursor: pointer;
            transition: var(--transition); white-space: nowrap;
        }

        .ti-btn-create:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(79,70,229,0.35);
        }

        .ti-btn-create svg { width: 15px; height: 15px; }

        /* ===== Stats row ===== */
        .ti-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .ti-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px 16px;
            display: flex; align-items: center; gap: 12px;
            box-shadow: var(--shadow);
        }

        .ti-stat-icon {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .ti-stat-icon svg { width: 17px; height: 17px; }

        .ti-stat-icon.total     { background: #EEF2FF; color: #4F46E5; }
        .ti-stat-icon.open      { background: #E0F2FE; color: #0284C7; }
        .ti-stat-icon.resolved  { background: #DCFCE7; color: #16A34A; }
        .ti-stat-icon.critical  { background: #FEE2E2; color: #DC2626; }

        .ti-stat-val {
            font-size: 20px; font-weight: 800;
            color: var(--text-primary); line-height: 1;
        }

        .ti-stat-lbl {
            font-size: 11px; color: var(--text-secondary);
            margin-top: 3px; font-weight: 500;
        }

        /* ===== Filters card ===== */
        .ti-filters {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 20px;
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
            box-shadow: var(--shadow);
        }

        .ti-search-wrap {
            position: relative; flex: 1; min-width: 200px;
        }

        .ti-search-icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: var(--text-muted); pointer-events: none;
        }

        .ti-search-input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            background: var(--bg-field);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; color: var(--text-primary);
            outline: none; transition: var(--transition);
        }

        .ti-search-input:focus {
            border-color: var(--accent);
            background: var(--bg-field-focus);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }

        .ti-search-input::placeholder { color: var(--text-muted); }

        .ti-select {
            padding: 9px 32px 9px 12px;
            background: var(--bg-field);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; color: var(--text-secondary);
            outline: none; transition: var(--transition);
            cursor: pointer; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%239CA3AF' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
        }

        .ti-select:focus {
            border-color: var(--accent);
            background-color: var(--bg-field-focus);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }

        .ti-select option { background: #FFFFFF; color: var(--text-primary); }

        .ti-btn-reset {
            padding: 9px 14px;
            background: transparent;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            cursor: pointer; transition: var(--transition);
            white-space: nowrap;
            display: inline-flex; align-items: center; gap: 6px;
        }

        .ti-btn-reset:hover { color: var(--text-secondary); border-color: var(--text-muted); }
        .ti-btn-reset svg { width: 13px; height: 13px; }

        /* ===== Table card ===== */
        .ti-table-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .ti-table-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .ti-table-title {
            font-size: 13px; font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase; letter-spacing: 0.8px;
        }

        .ti-table-count {
            font-size: 12px; color: var(--text-muted);
        }

        /* Table */
        .ti-table { width: 100%; border-collapse: collapse; }

        .ti-table th {
            padding: 10px 20px;
            font-size: 10px; font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.8px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            white-space: nowrap; cursor: pointer;
            transition: var(--transition);
            background: #FAFBFC;
        }

        .ti-table th:hover { color: var(--text-primary); }

        .ti-table th .sort-icon { display: inline-block; margin-left: 4px; opacity: 0.4; }

        .ti-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .ti-table tr:last-child td { border-bottom: none; }

        .ti-table tr:hover td { background: #F9FAFB; }

        /* Ticket ID */
        .ti-ticket-id {
            font-size: 11px; font-weight: 700;
            color: var(--accent); font-family: monospace;
            background: #EEF2FF;
            padding: 3px 8px; border-radius: 6px;
            white-space: nowrap;
        }

        /* Subject */
        .ti-subject-link {
            font-size: 13px; font-weight: 600;
            color: var(--text-primary); text-decoration: none;
            transition: var(--transition); display: block;
        }

        .ti-subject-link:hover { color: var(--accent); }

        .ti-subject-desc {
            font-size: 12px; color: var(--text-muted);
            margin-top: 3px; line-height: 1.4;
        }

        /* Badges */
        .ti-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
            white-space: nowrap;
        }

        .ti-badge-dot { width: 5px; height: 5px; border-radius: 50%; }

        /* Priority badges */
        .ti-badge.low      { background: #DCFCE7; color: #15803D; }
        .ti-badge.low .ti-badge-dot      { background: #22C55E; }
        .ti-badge.medium   { background: #FEF3C7; color: #B45309; }
        .ti-badge.medium .ti-badge-dot   { background: #F59E0B; }
        .ti-badge.high     { background: #FFEDD5; color: #9A3412; }
        .ti-badge.high .ti-badge-dot     { background: #F97316; }
        .ti-badge.critical { background: #FEE2E2; color: #991B1B; }
        .ti-badge.critical .ti-badge-dot { background: #EF4444; }

        /* Status badges */
        .ti-badge.open        { background: #EEF2FF; color: #4F46E5; }
        .ti-badge.open .ti-badge-dot        { background: #4F46E5; }
        .ti-badge.in_progress { background: #FEF3C7; color: #B45309; }
        .ti-badge.in_progress .ti-badge-dot { background: #F59E0B; }
        .ti-badge.resolved    { background: #DCFCE7; color: #15803D; }
        .ti-badge.resolved .ti-badge-dot    { background: #22C55E; }
        .ti-badge.closed      { background: #F3F4F6; color: #6B7280; }
        .ti-badge.closed .ti-badge-dot      { background: #6B7280; }

        /* Agent avatar */
        .ti-agent {
            display: flex; align-items: center; gap: 8px;
        }

        .ti-agent-avatar {
            width: 26px; height: 26px; border-radius: 7px;
            background: #EEF2FF;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800; color: #4F46E5;
            flex-shrink: 0;
        }

        .ti-agent-name { font-size: 12px; color: var(--text-secondary); font-weight: 500; }

        /* Time */
        .ti-time { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

        /* Row action */
        .ti-action-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: #F9FAFB;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none; cursor: pointer;
            transition: var(--transition); white-space: nowrap;
        }

        .ti-action-btn:hover {
            background: #EEF2FF;
            border-color: var(--accent);
            color: var(--accent);
        }

        .ti-action-btn svg { width: 13px; height: 13px; }

        /* ===== Empty state ===== */
        .ti-empty {
            text-align: center; padding: 60px 20px;
        }

        .ti-empty-icon {
            width: 56px; height: 56px;
            background: #F9FAFB;
            border: 1px solid var(--border);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            color: var(--text-muted);
        }

        .ti-empty-icon svg { width: 24px; height: 24px; }
        .ti-empty-title { font-size: 15px; font-weight: 700; color: var(--text-secondary); margin-bottom: 6px; }
        .ti-empty-sub { font-size: 13px; color: var(--text-muted); }

        .ti-empty-sub a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .ti-empty-sub a:hover { text-decoration: underline; }

        /* ===== Pagination ===== */
        .ti-pagination {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px; flex-wrap: wrap;
        }

        .ti-pagination .ti-page-info {
            font-size: 12px; color: var(--text-muted);
        }

        /* Override Laravel pagination styles */
        .ti-pagination nav { display: flex; align-items: center; }
        .ti-pagination nav > div:first-child { display: none; }

        /* ===== Responsive ===== */
        @media (max-width: 900px) {
            .ti-stats { grid-template-columns: 1fr 1fr; }
            .ti-table th:nth-child(4),
            .ti-table td:nth-child(4) { display: none; }
        }

        @media (max-width: 640px) {
            .ti-stats { grid-template-columns: 1fr 1fr; }
            .ti-filters { flex-direction: column; align-items: stretch; }
            .ti-search-wrap { min-width: unset; }
            .ti-table th:nth-child(3),
            .ti-table td:nth-child(3),
            .ti-table th:nth-child(5),
            .ti-table td:nth-child(5) { display: none; }
            .ti-page-header { flex-direction: column; align-items: stretch; }
            .ti-btn-create { justify-content: center; }
        }
    </style>

    <div class="ti-root">

        {{-- ===== Page header ===== --}}
        <div class="ti-page-header">
            <div>
                <div class="ti-page-title">Support Tickets</div>
                <div class="ti-page-sub">Manage and track all support requests</div>
            </div>
            <a href="{{ route('tickets.create') }}" class="ti-btn-create">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Ticket
            </a>
        </div>

        {{-- ===== Stats ===== --}}
        @php
            $total = $tickets->total();
            $open = $tickets->where('status', 'open')->count();
            $resolved = $tickets->where('status', 'resolved')->count();
            $critical = $tickets->where('priority', 'critical')->count();
        @endphp

        <div class="ti-stats">
            <div class="ti-stat-card">
                <div class="ti-stat-icon total">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <div class="ti-stat-val">{{ $total }}</div>
                    <div class="ti-stat-lbl">Total Tickets</div>
                </div>
            </div>
            <div class="ti-stat-card">
                <div class="ti-stat-icon open">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="ti-stat-val">{{ $open }}</div>
                    <div class="ti-stat-lbl">Open</div>
                </div>
            </div>
            <div class="ti-stat-card">
                <div class="ti-stat-icon resolved">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="ti-stat-val">{{ $resolved }}</div>
                    <div class="ti-stat-lbl">Resolved</div>
                </div>
            </div>
            <div class="ti-stat-card">
                <div class="ti-stat-icon critical">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="ti-stat-val">{{ $critical }}</div>
                    <div class="ti-stat-lbl">Critical</div>
                </div>
            </div>
        </div>

        {{-- ===== Filters ===== --}}
        <div class="ti-filters">
            <div class="ti-search-wrap">
                <svg class="ti-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search tickets, ID, subject..."
                    class="ti-search-input"
                >
            </div>

            <select wire:model.live="status" class="ti-select">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>

            <select wire:model.live="priority" class="ti-select">
                <option value="">All Priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>

            <select wire:model.live="perPage" class="ti-select">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>

            @if($search || $status || $priority)
                <button wire:click="resetFilters" class="ti-btn-reset">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            @endif
        </div>

        {{-- ===== Table ===== --}}
        <div class="ti-table-card">
            <div class="ti-table-header">
                <span class="ti-table-title">All Tickets</span>
                <span class="ti-table-count">{{ $tickets->total() }} results</span>
            </div>

            @if($tickets->count())
                <div style="overflow-x: auto;">
                    <table class="ti-table">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('ticket_id')">
                                    ID <span class="sort-icon">↕</span>
                                </th>
                                <th wire:click="sortBy('subject')">
                                    Subject <span class="sort-icon">↕</span>
                                </th>
                                <th wire:click="sortBy('priority')">
                                    Priority <span class="sort-icon">↕</span>
                                </th>
                                <th wire:click="sortBy('status')">
                                    Status <span class="sort-icon">↕</span>
                                </th>
                                <th>Agent</th>
                                <th wire:click="sortBy('created_at')">
                                    Created <span class="sort-icon">↕</span>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>
                                        <span class="ti-ticket-id">#{{ $ticket->ticket_id }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="ti-subject-link">
                                            {{ $ticket->subject }}
                                        </a>
                                        <div class="ti-subject-desc">
                                            {{ Str::limit($ticket->description, 60) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="ti-badge {{ $ticket->priority }}">
                                            <span class="ti-badge-dot"></span>
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="ti-badge {{ $ticket->status }}">
                                            <span class="ti-badge-dot"></span>
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($ticket->agent)
                                            <div class="ti-agent">
                                                <div class="ti-agent-avatar">
                                                    {{ strtoupper(substr($ticket->agent->name, 0, 1)) }}
                                                </div>
                                                <span class="ti-agent-name">{{ $ticket->agent->name }}</span>
                                            </div>
                                        @else
                                            <span class="ti-time">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="ti-time">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="ti-action-btn">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="ti-pagination">
                    <span class="ti-page-info">
                        Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }}
                    </span>
                    {{ $tickets->links() }}
                </div>

            @else
                <div class="ti-empty">
                    <div class="ti-empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ti-empty-title">No tickets found</div>
                    <div class="ti-empty-sub">
                        @if($search || $status || $priority)
                            Try adjusting your filters or
                            <a href="#" wire:click="resetFilters">clear all filters</a>
                        @else
                            No support tickets yet. <a href="{{ route('tickets.create') }}">Create your first ticket</a>
                        @endif
                    </div>
                </div>
            @endif

        </div>{{-- /ti-table-card --}}

    </div>{{-- /ti-root --}}
</div>