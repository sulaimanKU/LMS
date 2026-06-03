@extends('applayouts.app')
@section('contents')
<div class="jc-page">

    {{-- ── Header ── --}}
    <div class="jc-header">
        <div>
            <h5 class="jc-title">My Classes</h5>
            <p class="jc-subtitle">Live, upcoming, and past sessions for your enrolled modules</p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="jc-stats">
        <div class="jc-stat">
            <div class="jc-stat-icon" style="background:{{ $liveClass ? '#FEE2E2' : '#F1F5F9' }};color:{{ $liveClass ? '#DC2626' : '#94A3B8' }};">
                <i class="fa-solid fa-circle-dot"></i>
            </div>
            <div>
                <p class="jc-stat-num">{{ $liveClass ? '1' : '0' }}</p>
                <p class="jc-stat-lbl">Live Now</p>
            </div>
        </div>
        <div class="jc-stat">
            <div class="jc-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-calendar-days"></i></div>
            <div>
                <p class="jc-stat-num">{{ $upcomingCount }}</p>
                <p class="jc-stat-lbl">Upcoming</p>
            </div>
        </div>
        <div class="jc-stat">
            <div class="jc-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <p class="jc-stat-num">{{ $finishedCount }}</p>
                <p class="jc-stat-lbl">Completed</p>
            </div>
        </div>
        <div class="jc-stat">
            <div class="jc-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-video"></i></div>
            <div>
                <p class="jc-stat-num">{{ $upcomingCount + $finishedCount + ($liveClass ? 1 : 0) }}</p>
                <p class="jc-stat-lbl">Total Sessions</p>
            </div>
        </div>
    </div>

    {{-- ── Live Banner ── --}}
    @if($liveClass)
    <div class="jc-live-card">
        <div class="jc-live-left">
            <div class="jc-live-pulse-wrap">
                <div class="jc-live-pulse"></div>
                <i class="fa-solid fa-video jc-live-icon"></i>
            </div>
            <div class="jc-live-info">
                <div class="jc-live-badge"><span class="jc-live-dot-sm"></span> LIVE NOW</div>
                <p class="jc-live-title">{{ $liveClass->title }}</p>
                <div class="jc-live-meta">
                    <span><i class="fa-solid fa-book me-1"></i>{{ $liveClass->module?->title ?? '—' }}</span>
                    <span><i class="fa-solid fa-user me-1"></i>{{ $liveClass->teacher?->name ?? '—' }}</span>
                    <span><i class="fa-solid fa-clock me-1"></i>{{ \Carbon\Carbon::parse($liveClass->start_time)->format('h:i A') }}</span>
                    @if($liveClass->duration)
                        <span><i class="fa-solid fa-hourglass me-1"></i>{{ $liveClass->duration }} min</span>
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('student.joinClassAction', $liveClass->id) }}" class="jc-live-btn">
            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Enter Class
        </a>
    </div>
    @endif

    {{-- ── Upcoming ── --}}
    <div class="jc-section-label"><i class="fa-solid fa-calendar-days me-2"></i>Upcoming Sessions</div>

    @if($upcomingClasses->isEmpty())
    <div class="jc-empty-box">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No upcoming sessions scheduled for your enrolled modules.</p>
    </div>
    @else
    <div class="jc-grid">
        @foreach($upcomingClasses as $cls)
        @php $isToday = \Carbon\Carbon::parse($cls->class_date)->isToday(); @endphp
        <div class="jc-card {{ $isToday ? 'jc-card--today' : '' }}">
            <div class="jc-card-top">
                <div class="jc-date-box {{ $isToday ? 'today' : '' }}">
                    <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i') }}</span>
                    <span class="jc-date-ampm">{{ \Carbon\Carbon::parse($cls->start_time)->format('A') }}</span>
                    <span class="jc-date-day">{{ $isToday ? 'TODAY' : \Carbon\Carbon::parse($cls->class_date)->format('d M') }}</span>
                </div>
                <span class="jc-status-pill jc-pill-upcoming">Scheduled</span>
            </div>
            <p class="jc-card-title">{{ $cls->title }}</p>
            <span class="jc-module-pill">{{ $cls->module?->title ?? '—' }}</span>
            <div class="jc-teacher-row">
                <div class="jc-teacher-av">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                <span class="jc-teacher-name">{{ $cls->teacher?->name ?? '—' }}</span>
            </div>
            <div class="jc-card-foot">
                <button class="jc-btn-disabled" disabled>
                    <i class="fa-solid fa-lock me-1"></i>Not Started
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Finished ── --}}
    @if($finishedClasses->isNotEmpty())
    <div class="jc-section-label" style="margin-top:1.5rem;"><i class="fa-solid fa-circle-check me-2"></i>Completed Sessions</div>
    <div class="jc-grid">
        @foreach($finishedClasses as $cls)
        <div class="jc-card jc-card--done">
            <div class="jc-card-top">
                <div class="jc-date-box done">
                    <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                    <span class="jc-date-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d M Y') }}</span>
                </div>
                <span class="jc-status-pill jc-pill-done"><i class="fa-solid fa-check me-1"></i>Done</span>
            </div>
            <p class="jc-card-title">{{ $cls->title }}</p>
            <span class="jc-module-pill">{{ $cls->module?->title ?? '—' }}</span>
            <div class="jc-teacher-row">
                <div class="jc-teacher-av" style="background:#DCFCE7;color:#16A34A;">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                <span class="jc-teacher-name">{{ $cls->teacher?->name ?? '—' }}</span>
            </div>
            <div class="jc-card-foot">
                <span class="jc-btn-ended"><i class="fa-solid fa-flag-checkered me-1"></i>Session Ended</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Cancelled ── --}}
    @if($cancelledClasses->isNotEmpty())
    <div class="jc-section-label" style="margin-top:1.5rem;color:#DC2626;"><i class="fa-solid fa-ban me-2"></i>Cancelled Sessions</div>
    <div class="jc-grid">
        @foreach($cancelledClasses as $cls)
        <div class="jc-card jc-card--cancelled">
            <div class="jc-card-top">
                <div class="jc-date-box cancelled">
                    <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                    <span class="jc-date-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d M Y') }}</span>
                </div>
                <span class="jc-status-pill jc-pill-cancelled"><i class="fa-solid fa-ban me-1"></i>Cancelled</span>
            </div>
            <p class="jc-card-title" style="color:#94A3B8;">{{ $cls->title }}</p>
            <span class="jc-module-pill">{{ $cls->module?->title ?? '—' }}</span>
            <div class="jc-teacher-row">
                <div class="jc-teacher-av" style="background:#FEE2E2;color:#DC2626;">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                <span class="jc-teacher-name" style="color:#94A3B8;">{{ $cls->teacher?->name ?? '—' }}</span>
            </div>
            <div class="jc-card-foot">
                <span class="jc-btn-ended" style="color:#DC2626;border-color:#FECACA;">Session Revoked</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- WebSocket: silent live-class notification (no alert popup) --}}
@vite(['resources/js/app.js'])
<div id="jc-ws-toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;
    background:linear-gradient(135deg,#DC2626,#EF4444);color:#fff;border-radius:14px;
    padding:.9rem 1.25rem;box-shadow:0 8px 24px rgba(220,38,38,.35);
    font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.75rem;max-width:340px;">
    <i class="fa-solid fa-video fa-lg"></i>
    <div>
        <div id="jc-ws-msg">A class has started!</div>
        <button onclick="window.location.reload()" style="margin-top:.35rem;background:rgba(255,255,255,.2);
            border:1px solid rgba(255,255,255,.4);color:#fff;border-radius:7px;
            padding:.25rem .7rem;font-size:.75rem;font-weight:700;cursor:pointer;">
            Refresh to join
        </button>
    </div>
    <button onclick="document.getElementById('jc-ws-toast').style.display='none'"
            style="background:none;border:none;color:rgba(255,255,255,.7);font-size:1rem;cursor:pointer;padding:0;margin-left:auto;">✕</button>
</div>

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    window.Echo.channel('online-classes').listen('.class.started', (data) => {
        const toast = document.getElementById('jc-ws-toast');
        document.getElementById('jc-ws-msg').textContent =
            '"' + (data.onlineClass?.title ?? 'A class') + '" has started!';
        toast.style.display = 'flex';
    });
});
</script>

<style>
.jc-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

.jc-header { margin-bottom:1.25rem; }
.jc-title    { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.jc-subtitle { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.jc-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.jc-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.jc-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.jc-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.jc-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Live Card */
.jc-live-card {
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;
    background:linear-gradient(135deg,#1E1B4B,#3730A3); color:#fff;
    border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem;
    box-shadow:0 8px 24px rgba(55,48,163,.3);
}
.jc-live-left { display:flex; align-items:center; gap:1.1rem; flex:1; min-width:0; }
.jc-live-pulse-wrap { position:relative; width:52px; height:52px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
.jc-live-pulse {
    position:absolute; inset:0; border-radius:50%;
    background:rgba(239,68,68,.3); animation:jc-pulse-ring 1.6s ease-out infinite;
}
@keyframes jc-pulse-ring { 0%{transform:scale(.8);opacity:1} 100%{transform:scale(1.6);opacity:0} }
.jc-live-icon { position:relative; font-size:1.4rem; color:#FCA5A5; z-index:1; }
.jc-live-info { flex:1; min-width:0; }
.jc-live-badge { display:inline-flex; align-items:center; gap:.4rem; background:rgba(239,68,68,.25); color:#FCA5A5; padding:.2rem .7rem; border-radius:50px; font-size:.68rem; font-weight:800; letter-spacing:.8px; margin-bottom:.35rem; }
.jc-live-dot-sm { width:6px; height:6px; border-radius:50%; background:#FCA5A5; animation:jc-blink 1.2s ease-in-out infinite; }
@keyframes jc-blink { 0%,100%{opacity:1} 50%{opacity:.3} }
.jc-live-title { font-size:1.05rem; font-weight:800; margin:0 0 .35rem; color:#fff; }
.jc-live-meta { display:flex; flex-wrap:wrap; gap:.6rem; font-size:.74rem; color:rgba(255,255,255,.65); }
.jc-live-meta span { display:inline-flex; align-items:center; }
.jc-live-btn {
    display:inline-flex; align-items:center;
    background:linear-gradient(135deg,#EF4444,#DC2626); color:#fff;
    border:none; border-radius:11px; padding:.65rem 1.4rem;
    font-size:.85rem; font-weight:700; text-decoration:none; white-space:nowrap;
    box-shadow:0 4px 14px rgba(239,68,68,.4); transition:all .2s;
}
.jc-live-btn:hover { transform:translateY(-1px); color:#fff; }

/* Section label */
.jc-section-label { font-size:.78rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.6px; margin-bottom:.85rem; display:flex; align-items:center; }

/* Grid */
.jc-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:.5rem; }

/* Card */
.jc-card { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.05); padding:1.1rem; display:flex; flex-direction:column; gap:.55rem; transition:box-shadow .2s; }
.jc-card:hover { box-shadow:0 4px 14px rgba(0,0,0,.09); }
.jc-card--today { border-color:#C7D2FE; box-shadow:0 0 0 2px rgba(79,70,229,.12); }
.jc-card--done { background:#FAFFFE; border-color:#A7F3D0; }
.jc-card--cancelled { background:#FFFAFA; border-color:#FECACA; opacity:.85; }

.jc-card-top { display:flex; align-items:flex-start; justify-content:space-between; gap:.5rem; }
.jc-date-box { background:#F8FAFF; border:1px solid #E2E8F0; border-radius:9px; padding:.4rem .6rem; text-align:center; min-width:62px; }
.jc-date-box.today { background:#EEF2FF; border-color:#C7D2FE; }
.jc-date-box.done  { background:#ECFDF5; border-color:#A7F3D0; }
.jc-date-box.cancelled { background:#FFF5F5; border-color:#FECACA; }
.jc-date-time { display:block; font-size:.8rem; font-weight:800; color:#1E293B; }
.jc-date-ampm { display:block; font-size:.62rem; color:#94A3B8; font-weight:600; }
.jc-date-day  { display:block; font-size:.62rem; font-weight:800; color:#4F46E5; letter-spacing:.4px; border-top:1px solid #E2E8F0; margin-top:.2rem; padding-top:.2rem; }

.jc-status-pill { padding:.18rem .6rem; border-radius:50px; font-size:.68rem; font-weight:700; white-space:nowrap; }
.jc-pill-upcoming  { background:#EEF2FF; color:#4338CA; }
.jc-pill-done      { background:#D1FAE5; color:#065F46; }
.jc-pill-cancelled { background:#FEE2E2; color:#DC2626; }

.jc-card-title { font-size:.9rem; font-weight:700; color:#1E293B; margin:0; }
.jc-module-pill { display:inline-block; background:#F1F5F9; color:#64748B; padding:.15rem .55rem; border-radius:50px; font-size:.68rem; font-weight:600; }

.jc-teacher-row { display:flex; align-items:center; gap:.55rem; }
.jc-teacher-av { width:26px; height:26px; border-radius:7px; background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff; font-size:.68rem; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.jc-teacher-name { font-size:.77rem; font-weight:600; color:#475569; }

.jc-card-foot { margin-top:auto; padding-top:.3rem; }
.jc-btn-disabled, .jc-btn-ended {
    display:flex; align-items:center; justify-content:center;
    width:100%; padding:.4rem; border-radius:8px;
    font-size:.74rem; font-weight:600; border:1.5px dashed #E2E8F0; color:#94A3B8;
    background:transparent; cursor:default;
}
.jc-btn-ended { border-style:dashed; }

/* Empty */
.jc-empty-box { text-align:center; background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:3rem 1rem; color:#CBD5E1; margin-bottom:1rem; }
.jc-empty-box i { font-size:2.2rem; display:block; margin-bottom:.6rem; }
.jc-empty-box p { margin:0; font-size:.85rem; }

@media(max-width:991.98px) { .jc-stats { grid-template-columns:repeat(2,1fr); } .jc-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:640px)    { .jc-grid { grid-template-columns:1fr; } .jc-stats { grid-template-columns:repeat(2,1fr); } }
</style>
@endsection
