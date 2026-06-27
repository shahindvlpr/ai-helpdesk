{{-- resources/views/livewire/ticket/create.blade.php --}}
<div>
    <style>
        /* ===== Tokens (match login/register theme) ===== */
        .tc-root {
            --accent:        #5B5FED;
            --accent-hover:  #4F53D4;
            --gold:          #E8C87A;
            --bg-card:       #0D1024;
            --bg-field:      rgba(255,255,255,0.04);
            --bg-field-focus:rgba(91,95,237,0.07);
            --text-primary:  #F8F9FF;
            --text-secondary:#8892A4;
            --text-muted:    #3E4560;
            --border:        rgba(255,255,255,0.07);
            --border-strong: rgba(255,255,255,0.13);
            --border-accent: rgba(91,95,237,0.30);
            --radius:        10px;
            --transition:    all 0.2s cubic-bezier(0.4,0,0.2,1);

            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* ===== Back link ===== */
        .tc-back {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 24px;
            transition: var(--transition);
        }
        .tc-back:hover { color: #A5A8F5; }
        .tc-back svg { width: 15px; height: 15px; }

        /* ===== Alerts ===== */
        .tc-alert {
            padding: 13px 16px; border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 11px;
            font-size: 13px;
        }
        .tc-alert svg { width: 17px; height: 17px; flex-shrink: 0; margin-top: 1px; }
        .tc-alert-success {
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
        }
        .tc-alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #FCA5A5;
        }

        /* ===== Card ===== */
        .tc-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        /* ===== Card header ===== */
        .tc-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }
        .tc-header::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(91,95,237,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .tc-header-top {
            display: flex; align-items: center; gap: 14px;
            position: relative; z-index: 1;
        }
        .tc-header-icon {
            width: 42px; height: 42px;
            background: var(--accent);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(91,95,237,0.35);
        }
        .tc-header-icon svg { width: 20px; height: 20px; color: #fff; }
        .tc-header-title {
            font-size: 18px; font-weight: 800;
            color: var(--text-primary); letter-spacing: -0.3px;
        }
        .tc-header-sub {
            font-size: 12px; color: var(--text-secondary);
            margin-top: 2px;
        }

        /* Step indicator in header */
        .tc-steps {
            display: flex; align-items: center; gap: 0;
            margin-top: 20px; position: relative; z-index: 1;
        }
        .tc-step {
            display: flex; align-items: center; gap: 6px;
        }
        .tc-step-dot {
            width: 22px; height: 22px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; flex-shrink: 0;
        }
        .tc-step-dot.active  { background: var(--accent); color: #fff; }
        .tc-step-dot.pending { background: rgba(255,255,255,0.04); border: 1.5px solid var(--border); color: var(--text-muted); }
        .tc-step-lbl { font-size: 11px; font-weight: 600; }
        .tc-step-lbl.active  { color: #A5A8F5; }
        .tc-step-lbl.pending { color: var(--text-muted); }
        .tc-step-line { flex: 1; height: 1px; background: var(--border); margin: 0 10px; }

        /* ===== Form body ===== */
        .tc-body { padding: 28px; }

        /* ===== Section label ===== */
        .tc-section-label {
            font-size: 10px; font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 1px; text-transform: uppercase;
            margin-bottom: 16px; margin-top: 8px;
            display: flex; align-items: center; gap: 10px;
        }
        .tc-section-label::after {
            content: ''; flex: 1; height: 1px;
            background: var(--border);
        }

        /* ===== Field ===== */
        .tc-field { margin-bottom: 18px; }
        .tc-label {
            display: block;
            font-size: 10px; font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: 0.9px; text-transform: uppercase;
            margin-bottom: 7px;
        }
        .tc-required { color: #EF4444; margin-left: 2px; }

        .tc-input-wrap { position: relative; }
        .tc-input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted); pointer-events: none;
        }

        .tc-input, .tc-select, .tc-textarea {
            width: 100%;
            background: var(--bg-field);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-family: 'Outfit', -apple-system, sans-serif;
            font-size: 13px; font-weight: 400;
            color: var(--text-primary);
            outline: none;
            transition: var(--transition);
            -webkit-appearance: none;
        }
        .tc-input  { padding: 11px 13px 11px 40px; }
        .tc-select { padding: 11px 36px 11px 40px; }
        .tc-textarea { padding: 11px 13px 11px 13px; resize: vertical; min-height: 120px; }

        .tc-input:focus, .tc-select:focus, .tc-textarea:focus {
            border-color: var(--accent);
            background: var(--bg-field-focus);
            box-shadow: 0 0 0 3px rgba(91,95,237,0.12);
        }
        .tc-input::placeholder,
        .tc-textarea::placeholder { color: var(--text-muted); }
        .tc-select option { background: #1B1F35; color: var(--text-primary); }

        /* select arrow */
        .tc-select-wrap {
            position: relative;
        }
        .tc-select-arrow {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-muted); pointer-events: none;
        }

        /* char count */
        .tc-char-hint {
            display: flex; justify-content: space-between;
            margin-top: 5px;
        }
        .tc-hint { font-size: 11px; color: var(--text-muted); }
        .tc-char  { font-size: 11px; color: var(--text-muted); }

        /* field error */
        .tc-field-error {
            font-size: 11px; color: #FCA5A5;
            margin-top: 5px; display: flex; align-items: center; gap: 5px;
        }
        .tc-field-error svg { width: 12px; height: 12px; flex-shrink: 0; }

        /* error border */
        .tc-input.has-error,
        .tc-select.has-error,
        .tc-textarea.has-error {
            border-color: rgba(239,68,68,0.5);
        }

        /* ===== Two-column grid ===== */
        .tc-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ===== Priority cards ===== */
        .tc-priorities { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

        .tc-priority-card {
            cursor: pointer;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
            background: var(--bg-field);
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .tc-priority-card:hover { border-color: var(--border-strong); }
        .tc-priority-card input[type="radio"] { display: none; }

        .tc-priority-dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        .tc-priority-text {
            font-size: 12px; font-weight: 600; color: var(--text-secondary);
        }

        /* Active states — hardcoded to avoid Tailwind purge */
        .tc-priority-card.p-low.selected    { border-color: #22C55E; background: rgba(34,197,94,0.08); }
        .tc-priority-card.p-low.selected .tc-priority-text    { color: #86EFAC; }
        .tc-priority-card.p-low .tc-priority-dot              { background: #22C55E; }

        .tc-priority-card.p-medium.selected { border-color: #F59E0B; background: rgba(245,158,11,0.08); }
        .tc-priority-card.p-medium.selected .tc-priority-text { color: #FCD34D; }
        .tc-priority-card.p-medium .tc-priority-dot           { background: #F59E0B; }

        .tc-priority-card.p-high.selected   { border-color: #F97316; background: rgba(249,115,22,0.08); }
        .tc-priority-card.p-high.selected .tc-priority-text   { color: #FDBA74; }
        .tc-priority-card.p-high .tc-priority-dot             { background: #F97316; }

        .tc-priority-card.p-critical.selected { border-color: #EF4444; background: rgba(239,68,68,0.08); }
        .tc-priority-card.p-critical.selected .tc-priority-text { color: #FCA5A5; }
        .tc-priority-card.p-critical .tc-priority-dot         { background: #EF4444; }

        /* ===== Footer / buttons ===== */
        .tc-footer {
            display: flex; justify-content: flex-end; align-items: center; gap: 12px;
            padding: 20px 28px;
            border-top: 1px solid var(--border);
        }

        .tc-btn-cancel {
            padding: 10px 18px;
            background: transparent;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex; align-items: center; gap: 7px;
        }
        .tc-btn-cancel:hover { border-color: rgba(255,255,255,0.2); color: var(--text-primary); }
        .tc-btn-cancel svg { width: 14px; height: 14px; }

        .tc-btn-submit {
            padding: 10px 22px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius);
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex; align-items: center; gap: 8px;
        }
        .tc-btn-submit:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(91,95,237,0.35);
        }
        .tc-btn-submit:active { transform: translateY(0); }
        .tc-btn-submit svg { width: 14px; height: 14px; }

        /* loading state */
        .tc-btn-submit[wire\:loading] { opacity: 0.7; cursor: not-allowed; }

        /* spinner */
        @keyframes tc-spin { to { transform: rotate(360deg); } }
        .tc-spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: tc-spin 0.7s linear infinite;
            display: none;
        }

        /* ===== Responsive ===== */
        @media (max-width: 600px) {
            .tc-body    { padding: 20px 16px; }
            .tc-header  { padding: 18px 16px; }
            .tc-footer  { padding: 16px; flex-direction: column-reverse; }
            .tc-btn-cancel, .tc-btn-submit { width: 100%; justify-content: center; }
            .tc-grid-2  { grid-template-columns: 1fr; }
            .tc-priorities { grid-template-columns: 1fr 1fr; }
        }
    </style>

    <div class="tc-root">

        {{-- Back link --}}
        <a href="{{ route('tickets.index') }}" class="tc-back">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Tickets
        </a>

        {{-- Session alerts --}}
        @if(session()->has('success'))
            <div class="tc-alert tc-alert-success" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session()->has('error'))
            <div class="tc-alert tc-alert-error" role="alert">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Card --}}
        <div class="tc-card">

            {{-- Header --}}
            <div class="tc-header">
                <div class="tc-header-top">
                    <div class="tc-header-icon" aria-hidden="true">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="tc-header-title">Create New Ticket</div>
                        <div class="tc-header-sub">Submit a support request and our team will assist you</div>
                    </div>
                </div>

                {{-- Steps --}}
                <div class="tc-steps" aria-label="Ticket creation steps">
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

                    {{-- ── Section: Basic info ── --}}
                    <div class="tc-section-label">Basic Information</div>

                    {{-- Subject --}}
                    <div class="tc-field">
                        <label class="tc-label" for="tc-subject">
                            Subject <span class="tc-required">*</span>
                        </label>
                        <div class="tc-input-wrap">
                            <input
                                type="text"
                                id="tc-subject"
                                wire:model.live="subject"
                                placeholder="Brief description of your issue"
                                class="tc-input @error('subject') has-error @enderror"
                                maxlength="150"
                            >
                            <svg class="tc-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        @error('subject')
                            <div class="tc-field-error">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="tc-field">
                        <label class="tc-label" for="tc-description">
                            Description <span class="tc-required">*</span>
                        </label>
                        <textarea
                            id="tc-description"
                            wire:model.live="description"
                            placeholder="Please describe your issue in detail — include any steps to reproduce, error messages, or screenshots..."
                            class="tc-textarea @error('description') has-error @enderror"
                            rows="5"
                        ></textarea>
                        <div class="tc-char-hint">
                            <span class="tc-hint">Minimum 10 characters</span>
                            <span class="tc-char">{{ strlen($description ?? '') }} chars</span>
                        </div>
                        @error('description')
                            <div class="tc-field-error">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- ── Section: Classification ── --}}
                    <div class="tc-section-label" style="margin-top: 24px;">Classification</div>

                    <div class="tc-grid-2">

                        {{-- Priority --}}
                        <div class="tc-field">
                            <label class="tc-label">
                                Priority <span class="tc-required">*</span>
                            </label>
                            <div class="tc-priorities">
                                @php
                                    $priorities = [
                                        'low'      => 'Low',
                                        'medium'   => 'Medium',
                                        'high'     => 'High',
                                        'critical' => 'Critical',
                                    ];
                                @endphp
                                @foreach($priorities as $value => $label)
                                    <label class="tc-priority-card p-{{ $value }} {{ $priority == $value ? 'selected' : '' }}"
                                           wire:click="$set('priority', '{{ $value }}')">
                                        <input type="radio" name="priority" value="{{ $value }}"
                                               {{ $priority == $value ? 'checked' : '' }}>
                                        <span class="tc-priority-dot"></span>
                                        <span class="tc-priority-text">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('priority')
                                <div class="tc-field-error">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="tc-field">
                            <label class="tc-label" for="tc-category">Category</label>
                            <div class="tc-input-wrap tc-select-wrap">
                                <select
                                    id="tc-category"
                                    wire:model="category_id"
                                    class="tc-select @error('category_id') has-error @enderror"
                                >
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="tc-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                <svg class="tc-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('category_id')
                                <div class="tc-field-error">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>{{-- /tc-grid-2 --}}

                </div>{{-- /tc-body --}}

                {{-- Footer --}}
                <div class="tc-footer">
                    <a href="{{ route('tickets.index') }}" class="tc-btn-cancel">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="tc-btn-submit">
                        <span wire:loading.remove wire:target="save" style="display:contents;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            Create Ticket
                        </span>
                        <span wire:loading wire:target="save" style="display:contents;">
                            <span class="tc-spinner" style="display:block;"></span>
                            Submitting…
                        </span>
                    </button>
                </div>

            </form>

        </div>{{-- /tc-card --}}

    </div>{{-- /tc-root --}}
</div>