{{-- resources/views/livewire/tickets/create.blade.php --}}
<div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

        .tc-root {
            --accent:         #4F46E5;
            --accent-hover:   #4338CA;
            --accent-light:   #EEF2FF;
            --accent-border:  #C7D2FE;
            --bg-card:        #FFFFFF;
            --bg-section:     #F8F9FF;
            --bg-field:       #F9FAFB;
            --text-primary:   #111827;
            --text-secondary: #6B7280;
            --text-muted:     #9CA3AF;
            --border:         #E5E7EB;
            --border-strong:  #D1D5DB;
            --radius:         10px;
            --shadow:         0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-lg:      0 10px 25px rgba(0,0,0,0.08);
            --transition:     all 0.18s cubic-bezier(0.4,0,0.2,1);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
        }

        /* ===== Back link ===== */
        .tc-back {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 20px;
            padding: 6px 10px;
            border-radius: 8px;
            transition: var(--transition);
        }
        .tc-back:hover {
            color: var(--accent);
            background: var(--accent-light);
        }
        .tc-back svg { width: 14px; height: 14px; }

        /* ===== Alerts ===== */
        .tc-alert {
            padding: 12px 16px; border-radius: var(--radius);
            margin-bottom: 16px;
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 13px; font-weight: 500;
        }
        .tc-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .tc-alert-success { background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; }
        .tc-alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }

        /* ===== Card ===== */
        .tc-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        /* ===== Header ===== */
        .tc-header {
            padding: 24px 28px 20px;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, #EEF2FF 0%, #F5F3FF 100%);
            position: relative; overflow: hidden;
        }

        .tc-header::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: radial-gradient(circle, rgba(79,70,229,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .tc-header-top {
            display: flex; align-items: center; gap: 14px;
            position: relative; z-index: 1;
            margin-bottom: 20px;
        }

        .tc-header-icon {
            width: 44px; height: 44px;
            background: var(--accent);
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(79,70,229,0.3);
        }
        .tc-header-icon svg { width: 21px; height: 21px; color: #fff; }

        .tc-header-title {
            font-size: 18px; font-weight: 800;
            color: var(--text-primary); letter-spacing: -0.3px;
        }
        .tc-header-sub { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }

        /* Steps */
        .tc-steps {
            display: flex; align-items: center;
            position: relative; z-index: 1;
        }

        .tc-step { display: flex; align-items: center; gap: 7px; }

        .tc-step-dot {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800; flex-shrink: 0;
            transition: var(--transition);
        }
        .tc-step-dot.active  { background: var(--accent); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,0.35); }
        .tc-step-dot.pending { background: #F3F4F6; border: 1.5px solid var(--border); color: var(--text-muted); }

        .tc-step-lbl { font-size: 12px; font-weight: 600; }
        .tc-step-lbl.active  { color: var(--accent); }
        .tc-step-lbl.pending { color: var(--text-muted); }

        .tc-step-line { flex: 1; height: 1.5px; background: var(--border); margin: 0 12px; }

        /* ===== Body ===== */
        .tc-body { padding: 28px; }

        /* Section label */
        .tc-section {
            font-size: 10px; font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 1.2px; text-transform: uppercase;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .tc-section::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* ===== Fields ===== */
        .tc-field { margin-bottom: 18px; }

        .tc-label {
            display: block;
            font-size: 11px; font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: 0.7px; text-transform: uppercase;
            margin-bottom: 7px;
        }
        .tc-req { color: #DC2626; margin-left: 2px; }

        .tc-input-wrap { position: relative; }

        .tc-icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted); pointer-events: none;
            transition: var(--transition);
        }

        .tc-input, .tc-textarea {
            width: 100%;
            background: var(--bg-field);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; color: var(--text-primary);
            outline: none; transition: var(--transition);
        }

        .tc-input   { padding: 11px 13px 11px 38px; }
        .tc-textarea { padding: 11px 13px; resize: vertical; min-height: 130px; }

        .tc-input:focus, .tc-textarea:focus {
            border-color: var(--accent);
            background: #FAFBFF;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        .tc-input:focus + .tc-icon { color: var(--accent); }
        .tc-input::placeholder, .tc-textarea::placeholder { color: var(--text-muted); }

        .tc-input.err, .tc-textarea.err { border-color: #FCA5A5; }
        .tc-input.err:focus, .tc-textarea.err:focus {
            border-color: #DC2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
        }

        .tc-char-row {
            display: flex; justify-content: space-between;
            margin-top: 5px;
        }
        .tc-hint, .tc-char { font-size: 11px; color: var(--text-muted); }

        .tc-err-msg {
            font-size: 11px; color: #DC2626;
            margin-top: 5px; display: flex; align-items: center; gap: 5px;
        }
        .tc-err-msg svg { width: 12px; height: 12px; flex-shrink: 0; }

        /* ===== 2 col grid ===== */
        .tc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* ===== Priority buttons ===== */
        .tc-pri-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

        .tc-pri-btn {
            position: relative;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 14px;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            background: var(--bg-field);
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 700;
            color: var(--text-secondary);
            user-select: none;
        }

        .tc-pri-btn:hover { border-color: var(--border-strong); background: #F3F4F6; }

        .tc-pri-dot {
            width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
            transition: var(--transition);
        }

        /* Low */
        .tc-pri-btn[data-p="low"] .tc-pri-dot { background: #22C55E; }
        .tc-pri-btn[data-p="low"].active {
            border-color: #16A34A; background: #DCFCE7; color: #15803D;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.12);
        }

        /* Medium */
        .tc-pri-btn[data-p="medium"] .tc-pri-dot { background: #F59E0B; }
        .tc-pri-btn[data-p="medium"].active {
            border-color: #D97706; background: #FEF3C7; color: #B45309;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
        }

        /* High */
        .tc-pri-btn[data-p="high"] .tc-pri-dot { background: #F97316; }
        .tc-pri-btn[data-p="high"].active {
            border-color: #EA580C; background: #FFEDD5; color: #9A3412;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        }

        /* Critical */
        .tc-pri-btn[data-p="critical"] .tc-pri-dot { background: #EF4444; }
        .tc-pri-btn[data-p="critical"].active {
            border-color: #DC2626; background: #FEE2E2; color: #991B1B;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        }

        /* Check mark on active */
        .tc-pri-check {
            position: absolute; top: 6px; right: 7px;
            width: 14px; height: 14px;
            background: var(--accent);
            border-radius: 50%;
            display: none;
            align-items: center; justify-content: center;
        }
        .tc-pri-check svg { width: 8px; height: 8px; color: #fff; }
        .tc-pri-btn.active .tc-pri-check { display: flex; }

        /* ===== Category select ===== */
        .tc-sel-wrap { position: relative; }

        .tc-select {
            width: 100%;
            padding: 11px 36px 11px 38px;
            background: var(--bg-field);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; color: var(--text-primary);
            outline: none; transition: var(--transition);
            -webkit-appearance: none; cursor: pointer;
        }

        .tc-select:focus {
            border-color: var(--accent);
            background: #FAFBFF;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        .tc-select option { background: #fff; color: var(--text-primary); }

        .tc-sel-arrow {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted); pointer-events: none;
        }

        /* ===== Footer ===== */
        .tc-footer {
            display: flex; justify-content: flex-end; align-items: center; gap: 10px;
            padding: 18px 28px;
            border-top: 1px solid var(--border);
            background: #FAFBFC;
        }

        .tc-btn-cancel {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 18px;
            background: transparent;
            border: 1.5px solid var(--border-strong);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none; cursor: pointer;
            transition: var(--transition);
        }
        .tc-btn-cancel:hover { border-color: var(--text-muted); color: var(--text-primary); }
        .tc-btn-cancel svg { width: 14px; height: 14px; }

        .tc-btn-submit {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: var(--accent);
            border: none; border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 700; color: #fff;
            cursor: pointer; transition: var(--transition);
            box-shadow: 0 2px 8px rgba(79,70,229,0.25);
        }
        .tc-btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79,70,229,0.35);
        }
        .tc-btn-submit:active { transform: translateY(0); }
        .tc-btn-submit svg { width: 14px; height: 14px; }
        .tc-btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        @keyframes tc-spin { to { transform: rotate(360deg); } }
        .tc-spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: tc-spin 0.7s linear infinite;
            display: inline-block; flex-shrink: 0;
        }

        /* ===== Responsive ===== */
        @media (max-width: 620px) {
            .tc-body   { padding: 20px 16px; }
            .tc-header { padding: 18px 16px 16px; }
            .tc-footer { padding: 14px 16px; flex-direction: column-reverse; }
            .tc-btn-cancel, .tc-btn-submit { width: 100%; justify-content: center; }
            .tc-grid   { grid-template-columns: 1fr; }
        }
    </style>

    <div class="tc-root">

        {{-- Back --}}
        <a href="{{ route('tickets.index') }}" class="tc-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tickets
        </a>

        {{-- Alerts --}}
        @if(session()->has('success'))
            <div class="tc-alert tc-alert-success" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="tc-alert tc-alert-error" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="tc-card">

            {{-- Header --}}
            <div class="tc-header">
                <div class="tc-header-top">
                    <div class="tc-header-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="tc-header-title">Create New Ticket</div>
                        <div class="tc-header-sub">Submit a support request and our team will assist you</div>
                    </div>
                </div>

                <div class="tc-steps">
                    <div class="tc-step">
                        <div class="tc-step-dot active">1</div>
                        <span class="tc-step-lbl active">Details</span>
                    </div>
                    <div class="tc-step-line"></div>
                    <div class="tc-step">
                        <div class="tc-step-dot pending">2</div>
                        <span class="tc-step-lbl pending">Review</span>
                    </div>
                    <div class="tc-step-line"></div>
                    <div class="tc-step">
                        <div class="tc-step-dot pending">3</div>
                        <span class="tc-step-lbl pending">Submitted</span>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="save">
                <div class="tc-body">

                    {{-- Basic info --}}
                    <div class="tc-section">Basic Information</div>

                    {{-- Subject --}}
                    <div class="tc-field">
                        <label class="tc-label" for="tc-subject">
                            Subject <span class="tc-req">*</span>
                        </label>
                        <div class="tc-input-wrap">
                            <input
                                type="text"
                                id="tc-subject"
                                wire:model="subject"
                                placeholder="Brief description of your issue"
                                class="tc-input @error('subject') err @enderror"
                                maxlength="150"
                                autocomplete="off"
                            >
                            <svg class="tc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        @error('subject')
                            <div class="tc-err-msg">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="tc-field">
                        <label class="tc-label" for="tc-desc">
                            Description <span class="tc-req">*</span>
                        </label>
                        <textarea
                            id="tc-desc"
                            wire:model="description"
                            placeholder="Describe your issue in detail — include steps to reproduce, error messages, or screenshots..."
                            class="tc-textarea @error('description') err @enderror"
                            rows="5"
                        ></textarea>
                        <div class="tc-char-row">
                            <span class="tc-hint">Minimum 10 characters</span>
                            <span class="tc-char">{{ strlen($description ?? '') }} chars</span>
                        </div>
                        @error('description')
                            <div class="tc-err-msg">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Classification --}}
                    <div class="tc-section" style="margin-top:24px;">Classification</div>

                    <div class="tc-grid">

                        {{-- Priority --}}
                        <div class="tc-field">
                            <label class="tc-label">Priority <span class="tc-req">*</span></label>

                            {{-- Hidden input for Livewire --}}
                            <input type="hidden" wire:model="priority" id="priority-input">

                            <div class="tc-pri-grid" id="priority-grid">
                                @php
                                    $pList = [
                                        'low'      => 'Low',
                                        'medium'   => 'Medium',
                                        'high'     => 'High',
                                        'critical' => 'Critical',
                                    ];
                                @endphp
                                @foreach($pList as $val => $lbl)
                                    <button
                                        type="button"
                                        class="tc-pri-btn {{ $priority === $val ? 'active' : '' }}"
                                        data-p="{{ $val }}"
                                        wire:click="$set('priority', '{{ $val }}')"
                                        onclick="setPriority('{{ $val }}')"
                                    >
                                        <span class="tc-pri-check">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                        <span class="tc-pri-dot"></span>
                                        {{ $lbl }}
                                    </button>
                                @endforeach
                            </div>

                            @error('priority')
                                <div class="tc-err-msg" style="margin-top:8px;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="tc-field">
                            <label class="tc-label" for="tc-cat">Category</label>
                            <div class="tc-sel-wrap tc-input-wrap">
                                <select
                                    id="tc-cat"
                                    wire:model="category_id"
                                    class="tc-select @error('category_id') err @enderror"
                                >
                                    <option value="">Select a category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="tc-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                <svg class="tc-sel-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('category_id')
                                <div class="tc-err-msg">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>{{-- /tc-grid --}}

                </div>{{-- /tc-body --}}

                {{-- Footer --}}
                <div class="tc-footer">
                    <a href="{{ route('tickets.index') }}" class="tc-btn-cancel">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="tc-btn-submit" id="tc-submit">
                        <span wire:loading.remove wire:target="save">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;display:inline-block;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            Create Ticket
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="tc-spinner"></span>
                            Submitting…
                        </span>
                    </button>
                </div>

            </form>

        </div>{{-- /tc-card --}}

    </div>{{-- /tc-root --}}

    <script>
        function setPriority(val) {
            // Update active class on buttons immediately (before Livewire re-renders)
            document.querySelectorAll('#priority-grid .tc-pri-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.p === val);
            });
        }

        // Sync priority buttons after Livewire re-renders
        document.addEventListener('livewire:updated', function () {
            const hidden = document.getElementById('priority-input');
            if (!hidden) return;
            const val = hidden.value;
            document.querySelectorAll('#priority-grid .tc-pri-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.p === val);
            });
        });
    </script>
</div>