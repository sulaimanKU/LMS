@extends('applayouts.app')
@section('contents')

<style>
.sl-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

/* Header */
.sl-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:1.25rem; }
.sl-title   { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.sl-sub     { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.sl-stats { display:flex; gap:.65rem; flex-wrap:wrap; margin-bottom:1.1rem; }
.sl-stat {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:12px;
    padding:.7rem 1rem; display:flex; align-items:center; gap:.65rem;
    box-shadow:0 1px 4px rgba(0,0,0,.04); min-width:130px;
}
.sl-stat-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.sl-stat-num { font-size:1.15rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.sl-stat-lbl { font-size:.66rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.1rem 0 0; }

/* Filter bar */
.sl-filters {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:12px;
    padding:.85rem 1rem; display:flex; align-items:center; gap:.65rem;
    flex-wrap:wrap; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,.03);
}
.sl-search {
    flex:1; min-width:180px; border:1.5px solid #E2E8F0; border-radius:8px;
    padding:.42rem .75rem .42rem 2rem; font-size:.82rem; color:#1E293B;
    outline:none; background:#F8FAFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") .6rem center / 14px no-repeat;
    transition:border-color .15s;
}
.sl-search:focus { border-color:#7C3AED; background-color:#fff; }

.sl-lvl-btns { display:flex; gap:.35rem; flex-wrap:wrap; }
.sl-lvl-btn {
    padding:.35rem .8rem; border-radius:7px; border:1.5px solid #E2E8F0;
    background:#fff; font-size:.75rem; font-weight:700; cursor:pointer;
    transition:.15s; color:#64748B;
}
.sl-lvl-btn:hover { border-color:#C4B5FD; color:#4F46E5; }
.sl-lvl-btn.active { border-color:transparent; color:#fff; }
.sl-lvl-btn.active[data-lvl="ALL"]     { background:#4F46E5; }
.sl-lvl-btn.active[data-lvl="ERROR"]   { background:#DC2626; }
.sl-lvl-btn.active[data-lvl="WARNING"] { background:#D97706; }
.sl-lvl-btn.active[data-lvl="INFO"]    { background:#0891B2; }
.sl-lvl-btn.active[data-lvl="DEBUG"]   { background:#64748B; }

.sl-date-sel {
    border:1.5px solid #E2E8F0; border-radius:8px; padding:.4rem .7rem;
    font-size:.78rem; color:#64748B; outline:none; cursor:pointer;
    background:#F8FAFF;
}

/* Entries */
.sl-list { display:flex; flex-direction:column; gap:.55rem; }

.sl-entry {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:12px;
    border-left:4px solid #CBD5E1; overflow:hidden;
    box-shadow:0 1px 4px rgba(0,0,0,.04); transition:box-shadow .15s;
}
.sl-entry:hover { box-shadow:0 3px 12px rgba(0,0,0,.07); }
.sl-entry[data-group="ERROR"]   { border-left-color:#EF4444; }
.sl-entry[data-group="WARNING"] { border-left-color:#F59E0B; }
.sl-entry[data-group="INFO"]    { border-left-color:#06B6D4; }
.sl-entry[data-group="DEBUG"]   { border-left-color:#94A3B8; }

.sl-entry-head {
    display:flex; align-items:flex-start; gap:.75rem;
    padding:.75rem 1rem; cursor:pointer; user-select:none;
}
.sl-entry-head:hover { background:#FAFBFF; }

.sl-datetime { text-align:center; flex-shrink:0; min-width:52px; }
.sl-day  { font-size:1.1rem; font-weight:800; color:#1E293B; line-height:1; }
.sl-mon  { font-size:.6rem; font-weight:700; text-transform:uppercase; color:#94A3B8; letter-spacing:.5px; }
.sl-time { font-size:.65rem; color:#CBD5E1; margin-top:.15rem; font-family:monospace; }

.sl-main { flex:1; min-width:0; }
.sl-badge {
    display:inline-flex; align-items:center; gap:.25rem;
    padding:.12rem .5rem; border-radius:50px; font-size:.63rem; font-weight:800;
    text-transform:uppercase; letter-spacing:.4px; margin-bottom:.3rem;
}
.sl-badge-ERROR   { background:#FEE2E2; color:#DC2626; }
.sl-badge-WARNING { background:#FEF3C7; color:#D97706; }
.sl-badge-INFO    { background:#CFFAFE; color:#0E7490; }
.sl-badge-DEBUG   { background:#F1F5F9; color:#64748B; }
.sl-badge-OTHER   { background:#F1F5F9; color:#64748B; }
.sl-CRITICAL      { background:#FEE2E2; color:#DC2626; }
.sl-EMERGENCY     { background:#FEE2E2; color:#991B1B; }
.sl-ALERT         { background:#FEE2E2; color:#B91C1C; }
.sl-NOTICE        { background:#FEF3C7; color:#D97706; }

.sl-msg { font-size:.82rem; color:#334155; margin:0; word-break:break-word; line-height:1.5; }

.sl-expand-btn {
    flex-shrink:0; width:26px; height:26px; border-radius:7px;
    border:1px solid #E2E8F0; background:#F8FAFF; color:#94A3B8;
    display:flex; align-items:center; justify-content:center;
    font-size:.7rem; cursor:pointer; transition:.15s; margin-top:.1rem;
}
.sl-expand-btn.open { background:#EEF2FF; color:#4F46E5; border-color:#C7D2FE; transform:rotate(90deg); }

.sl-trace {
    display:none; border-top:1px solid #F1F5F9;
    padding:.75rem 1rem; background:#FAFBFF;
}
.sl-trace.show { display:block; }
.sl-trace pre {
    margin:0; font-size:.72rem; line-height:1.6;
    color:#475569; white-space:pre-wrap; word-break:break-all;
    max-height:260px; overflow-y:auto;
}

/* Empty */
.sl-empty { text-align:center; padding:4rem 1rem; color:#CBD5E1; }
.sl-empty i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
.sl-empty p { margin:0; font-size:.9rem; }

/* Count bar */
.sl-count-bar { font-size:.76rem; color:#94A3B8; margin-bottom:.65rem; }
.sl-count-bar strong { color:#475569; }
</style>

<div class="sl-page">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="sl-header">
        <div>
            <h5 class="sl-title"><i class="fa-solid fa-clipboard-list me-2 text-primary"></i>System Logs</h5>
            <p class="sl-sub">Parsed from <code>storage/logs/laravel.log</code> &nbsp;·&nbsp; showing last {{ count($entries) }} entries</p>
        </div>
        <form action="{{ route('systemlogs.clear') }}" method="POST"
              onsubmit="return confirm('Clear the entire log file? This cannot be undone.')">
            @csrf
            <button type="submit" class="sa-btn-remove" style="border-radius:10px;padding:.45rem .9rem;font-size:.8rem;">
                <i class="fa-solid fa-trash-can"></i> Clear Logs
            </button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="sl-stats">
        <div class="sl-stat">
            <div class="sl-stat-dot" style="background:#4F46E5;"></div>
            <div>
                <p class="sl-stat-num">{{ count($entries) }}</p>
                <p class="sl-stat-lbl">Total</p>
            </div>
        </div>
        <div class="sl-stat">
            <div class="sl-stat-dot" style="background:#EF4444;"></div>
            <div>
                <p class="sl-stat-num">{{ $stats['ERROR'] }}</p>
                <p class="sl-stat-lbl">Errors</p>
            </div>
        </div>
        <div class="sl-stat">
            <div class="sl-stat-dot" style="background:#F59E0B;"></div>
            <div>
                <p class="sl-stat-num">{{ $stats['WARNING'] }}</p>
                <p class="sl-stat-lbl">Warnings</p>
            </div>
        </div>
        <div class="sl-stat">
            <div class="sl-stat-dot" style="background:#06B6D4;"></div>
            <div>
                <p class="sl-stat-num">{{ $stats['INFO'] }}</p>
                <p class="sl-stat-lbl">Info</p>
            </div>
        </div>
        <div class="sl-stat">
            <div class="sl-stat-dot" style="background:#94A3B8;"></div>
            <div>
                <p class="sl-stat-num">{{ $stats['DEBUG'] }}</p>
                <p class="sl-stat-lbl">Debug</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="sl-filters">
        <input type="text" class="sl-search" id="slSearch" placeholder="Search message…">

        <div class="sl-lvl-btns" id="slLvlBtns">
            <button class="sl-lvl-btn active" data-lvl="ALL">All</button>
            <button class="sl-lvl-btn" data-lvl="ERROR"><i class="fa-solid fa-circle-xmark me-1"></i>Error</button>
            <button class="sl-lvl-btn" data-lvl="WARNING"><i class="fa-solid fa-triangle-exclamation me-1"></i>Warning</button>
            <button class="sl-lvl-btn" data-lvl="INFO"><i class="fa-solid fa-circle-info me-1"></i>Info</button>
            <button class="sl-lvl-btn" data-lvl="DEBUG"><i class="fa-solid fa-bug me-1"></i>Debug</button>
        </div>

        <select class="sl-date-sel" id="slDateSel">
            <option value="all">All time</option>
            <option value="1">Last 24 hours</option>
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
        </select>
    </div>

    {{-- Count --}}
    <p class="sl-count-bar"><strong id="slVisible">{{ count($entries) }}</strong> of {{ count($entries) }} entries shown</p>

    {{-- Entries --}}
    @if(empty($entries))
    <div class="sl-empty">
        <i class="fa-solid fa-clipboard-list"></i>
        <p>No log entries found. The log file is empty or unreadable.</p>
    </div>
    @else
    <div class="sl-list" id="slList">
        @foreach($entries as $i => $entry)
        @php
            $parts   = explode('-', $entry['date']);
            $day     = $parts[2] ?? '??';
            $monNum  = intval($parts[1] ?? 0);
            $months  = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $mon     = $months[$monNum] ?? '???';
            $group   = $entry['group'];
            $hasTrace = !empty(trim($entry['trace']));
        @endphp
        <div class="sl-entry"
             data-group="{{ $group }}"
             data-date="{{ $entry['date'] }}"
             data-msg="{{ strtolower($entry['message']) }}">

            <div class="sl-entry-head" onclick="slToggle({{ $i }})">
                <div class="sl-datetime">
                    <div class="sl-day">{{ $day }}</div>
                    <div class="sl-mon">{{ $mon }}</div>
                    <div class="sl-time">{{ substr($entry['time'], 0, 5) }}</div>
                </div>
                <div class="sl-main">
                    @php
                        $badgeClass = match(true) {
                            in_array($entry['level'], ['ERROR','CRITICAL','EMERGENCY','ALERT']) => 'sl-badge-ERROR',
                            in_array($entry['level'], ['WARNING','NOTICE'])                     => 'sl-badge-WARNING',
                            $entry['level'] === 'INFO'                                          => 'sl-badge-INFO',
                            default                                                             => 'sl-badge-DEBUG',
                        };
                    @endphp
                    <span class="sl-badge {{ $badgeClass }}">
                        <i class="fa-solid {{ $group === 'ERROR' ? 'fa-circle-xmark' : ($group === 'WARNING' ? 'fa-triangle-exclamation' : ($group === 'INFO' ? 'fa-circle-info' : 'fa-bug')) }}"></i>
                        {{ $entry['level'] }}
                    </span>
                    <p class="sl-msg">{{ $entry['message'] }}</p>
                </div>
                @if($hasTrace)
                <div class="sl-expand-btn" id="slBtn{{ $i }}">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
                @endif
            </div>

            @if($hasTrace)
            <div class="sl-trace" id="slTrace{{ $i }}">
                <pre>{{ $entry['trace'] }}</pre>
            </div>
            @endif

        </div>
        @endforeach
    </div>
    @endif

</div>

<script>
(function () {
    const list    = document.getElementById('slList');
    const entries = list ? Array.from(list.querySelectorAll('.sl-entry')) : [];
    const counter = document.getElementById('slVisible');
    let activeLvl = 'ALL';
    let searchQ   = '';
    let dateDays  = 'all';

    function apply() {
        const now = new Date();
        let shown = 0;
        entries.forEach(el => {
            const group = el.dataset.group;
            const msg   = el.dataset.msg || '';
            const date  = el.dataset.date || '';

            const lvlOk = activeLvl === 'ALL' || group === activeLvl;
            const msgOk = !searchQ || msg.includes(searchQ);
            let dateOk  = true;
            if (dateDays !== 'all') {
                const entryDate = new Date(date);
                const diff = (now - entryDate) / 86400000;
                dateOk = diff <= parseInt(dateDays);
            }

            const visible = lvlOk && msgOk && dateOk;
            el.style.display = visible ? '' : 'none';
            if (visible) shown++;
        });
        if (counter) counter.textContent = shown;
    }

    // Level buttons
    document.getElementById('slLvlBtns')?.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-lvl]');
        if (!btn) return;
        this.querySelectorAll('.sl-lvl-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeLvl = btn.dataset.lvl;
        apply();
    });

    // Search
    document.getElementById('slSearch')?.addEventListener('input', function () {
        searchQ = this.value.toLowerCase().trim();
        apply();
    });

    // Date filter
    document.getElementById('slDateSel')?.addEventListener('change', function () {
        dateDays = this.value;
        apply();
    });
})();

function slToggle(i) {
    const trace = document.getElementById('slTrace' + i);
    const btn   = document.getElementById('slBtn' + i);
    if (!trace) return;
    const open = trace.classList.toggle('show');
    btn?.classList.toggle('open', open);
}
</script>

@endsection
