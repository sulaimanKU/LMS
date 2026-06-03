@extends('applayouts.app')

@section('contents')
<div class="vr-page">

    {{-- ── Header ── --}}
    <div class="vr-header">
        <div class="vr-header-left">
            <div class="vr-live-badge">
                <span class="vr-pulse"></span> Live Monitor
            </div>
            <h5 class="vr-title">Virtual Classroom</h5>
            <p class="vr-subtitle">Real-time view of all ongoing online sessions</p>
        </div>
        <div class="vr-stats-row">
            <div class="vr-stat">
                <span class="vr-stat-num {{ $totalLive > 0 ? 'live-num' : '' }}">{{ $totalLive }}</span>
                <span class="vr-stat-label">Live Now</span>
            </div>
            <div class="vr-stat-divider"></div>
            <div class="vr-stat">
                <span class="vr-stat-num">{{ $totalStudents }}</span>
                <span class="vr-stat-label">Students Joined</span>
            </div>
            <div class="vr-stat-divider"></div>
            <div class="vr-stat">
                <span class="vr-stat-num">{{ $upcomingToday->count() }}</span>
                <span class="vr-stat-label">Upcoming Today</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════
         LIVE CLASSES
    ══════════════════════════════ --}}
    <p class="vr-section-label">
        <span class="vr-pulse vr-pulse-sm"></span> Currently Live
    </p>

    @if($liveClasses->isEmpty())
    <div class="vr-empty">
        <div class="vr-empty-icon"><i class="fa-solid fa-video-slash"></i></div>
        <p class="vr-empty-title">No Live Classes Right Now</p>
        <p class="vr-empty-sub">Sessions started by teachers will appear here automatically.</p>
    </div>
    @else
    <div class="vr-grid">
        @foreach($liveClasses as $class)
        <div class="vr-card vr-card-live">
            {{-- Live header --}}
            <div class="vr-card-header">
                <div class="vr-live-tag">
                    <span class="vr-pulse vr-pulse-sm"></span> LIVE
                </div>
                <span class="vr-started-at">
                    Started {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                    &nbsp;·&nbsp; {{ $class->duration ? $class->duration . ' min' : '—' }}
                </span>
            </div>

            {{-- Title + module --}}
            <h6 class="vr-card-title">{{ $class->title }}</h6>
            <span class="vr-module-pill">
                <i class="fa-solid fa-book-open me-1"></i>{{ $class->module?->title ?? '—' }}
            </span>

            {{-- Teacher block --}}
            <div class="vr-teacher-block">
                <div class="vr-teacher-avatar">
                    @if($class->teacher?->profile_image)
                        <img src="{{ asset('uploads/teachers/' . $class->teacher->profile_image) }}" alt="">
                    @else
                        {{ strtoupper(substr($class->teacher?->name ?? 'T', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <p class="vr-teacher-name">{{ $class->teacher?->name ?? 'Unknown Teacher' }}</p>
                    <p class="vr-teacher-role">{{ $class->teacher?->designation ?? 'Teacher' }}</p>
                </div>
                <div class="vr-attendance-badge">
                    <i class="fa-solid fa-users me-1"></i>{{ $class->attendances_count }} joined
                </div>
            </div>

            {{-- Meeting date --}}
            <div class="vr-date-row">
                <i class="fa-solid fa-calendar-day"></i>
                {{ \Carbon\Carbon::parse($class->class_date)->format('M d, Y') }}
            </div>

            {{-- Join button --}}
            <a href="{{ $class->meeting_link }}" target="_blank" class="vr-join-btn">
                <i class="fa-solid fa-arrow-up-right-from-square me-2"></i>
                Join / Monitor Session
            </a>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ══════════════════════════════
         UPCOMING TODAY
    ══════════════════════════════ --}}
    @if($upcomingToday->isNotEmpty())
    <p class="vr-section-label" style="margin-top:2rem;">
        <i class="fa-solid fa-clock" style="color:#4F46E5;margin-right:6px;"></i>Upcoming Today
    </p>
    <div class="vr-upcoming-list">
        @foreach($upcomingToday as $class)
        <div class="vr-upcoming-row">
            <div class="vr-upcoming-time">
                {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
            </div>
            <div class="vr-upcoming-info">
                <p class="vr-upcoming-title">{{ $class->title }}</p>
                <p class="vr-upcoming-meta">
                    {{ $class->module?->title ?? '—' }}
                    &nbsp;·&nbsp;
                    {{ $class->teacher?->name ?? '—' }}
                    @if($class->duration)
                        &nbsp;·&nbsp; {{ $class->duration }} min
                    @endif
                </p>
            </div>
            <a href="{{ $class->meeting_link }}" target="_blank" class="vr-upcoming-link">
                <i class="fa-solid fa-video me-1"></i>Open Link
            </a>
        </div>
        @endforeach
    </div>
    @endif

</div>

<style>
/* ── Page ── */
.vr-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.vr-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;
}
.vr-live-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #FEE2E2; color: #DC2626;
    padding: .2rem .7rem; border-radius: 50px;
    font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px;
    margin-bottom: .4rem;
}
.vr-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.vr-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }

/* ── Pulse dot ── */
.vr-pulse {
    display: inline-block; width: 8px; height: 8px;
    border-radius: 50%; background: #DC2626;
    animation: vrPulse 1.4s infinite;
}
.vr-pulse-sm { width: 7px; height: 7px; }
@keyframes vrPulse {
    0%,100% { opacity: 1; box-shadow: 0 0 0 0 rgba(220,38,38,.5); }
    50%      { opacity: .7; box-shadow: 0 0 0 5px rgba(220,38,38,0); }
}

/* ── Stats row ── */
.vr-stats-row {
    display: flex; align-items: center; gap: 0;
    background: #fff; border: 1.5px solid #F1F5F9;
    border-radius: 14px; padding: .75rem 1.25rem;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
}
.vr-stat { text-align: center; padding: 0 1.1rem; }
.vr-stat-num   { display: block; font-size: 1.3rem; font-weight: 800; color: #1E293B; }
.vr-stat-num.live-num { color: #DC2626; }
.vr-stat-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: #94A3B8; }
.vr-stat-divider { width: 1px; background: #F1F5F9; height: 36px; }

/* ── Section label ── */
.vr-section-label {
    display: flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .7px; color: #64748B; margin: 0 0 .9rem;
}

/* ── Empty state ── */
.vr-empty {
    text-align: center; padding: 4rem 1rem;
    background: #fff; border-radius: 16px;
    border: 1.5px dashed #E2E8F0;
}
.vr-empty-icon { font-size: 2.5rem; color: #CBD5E1; margin-bottom: .75rem; }
.vr-empty-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin: 0 0 .25rem; }
.vr-empty-sub   { font-size: .8rem; color: #94A3B8; margin: 0; }

/* ── Grid ── */
.vr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }

/* ── Card ── */
.vr-card {
    background: #fff; border-radius: 16px;
    border: 1.5px solid #F1F5F9; padding: 1.2rem 1.25rem;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    display: flex; flex-direction: column; gap: .75rem;
    transition: box-shadow .2s, transform .2s;
}
.vr-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.09); transform: translateY(-2px); }
.vr-card-live { border-color: #FCA5A5; border-left: 4px solid #DC2626; background: linear-gradient(to bottom right,#fff,#FFF5F5); }

/* ── Card header ── */
.vr-card-header { display: flex; align-items: center; justify-content: space-between; }
.vr-live-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: #FEE2E2; color: #DC2626;
    padding: .22rem .65rem; border-radius: 50px;
    font-size: .68rem; font-weight: 800; letter-spacing: .5px;
}
.vr-started-at { font-size: .72rem; color: #94A3B8; }

/* ── Card body ── */
.vr-card-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin: 0; }
.vr-module-pill {
    display: inline-flex; align-items: center;
    background: #EEF2FF; color: #4338CA;
    padding: .18rem .65rem; border-radius: 50px;
    font-size: .72rem; font-weight: 600; width: fit-content;
}

/* ── Teacher block ── */
.vr-teacher-block {
    display: flex; align-items: center; gap: 10px;
    background: #F8FAFF; border-radius: 10px; padding: .7rem .9rem;
    border: 1px solid #F1F5F9;
}
.vr-teacher-avatar {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; font-size: .8rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.vr-teacher-avatar img { width: 100%; height: 100%; object-fit: cover; }
.vr-teacher-name { font-size: .82rem; font-weight: 700; color: #1E293B; margin: 0; }
.vr-teacher-role { font-size: .7rem; color: #94A3B8; margin: .05rem 0 0; }
.vr-attendance-badge {
    margin-left: auto; background: #D1FAE5; color: #065F46;
    padding: .22rem .65rem; border-radius: 50px;
    font-size: .72rem; font-weight: 700; white-space: nowrap;
}

/* ── Date row ── */
.vr-date-row { display: flex; align-items: center; gap: 7px; font-size: .78rem; color: #64748B; }
.vr-date-row i { color: #94A3B8; }

/* ── Join button ── */
.vr-join-btn {
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    padding: .6rem 1rem; border-radius: 10px;
    font-size: .82rem; font-weight: 600; text-decoration: none;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
    margin-top: auto;
}
.vr-join-btn:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }

/* ── Upcoming list ── */
.vr-upcoming-list { display: flex; flex-direction: column; gap: .6rem; }
.vr-upcoming-row {
    display: flex; align-items: center; gap: 1rem;
    background: #fff; border: 1.5px solid #F1F5F9;
    border-radius: 12px; padding: .8rem 1.1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.vr-upcoming-time {
    font-size: .82rem; font-weight: 800; color: #4F46E5;
    white-space: nowrap; min-width: 70px;
}
.vr-upcoming-info { flex: 1; min-width: 0; }
.vr-upcoming-title { font-size: .85rem; font-weight: 700; color: #1E293B; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.vr-upcoming-meta  { font-size: .72rem; color: #94A3B8; margin: .1rem 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.vr-upcoming-link {
    display: inline-flex; align-items: center;
    background: #EEF2FF; color: #4F46E5;
    border: 1px solid #C7D2FE; border-radius: 7px;
    padding: .3rem .75rem; font-size: .75rem; font-weight: 600;
    text-decoration: none; transition: all .15s; white-space: nowrap; flex-shrink: 0;
}
.vr-upcoming-link:hover { background: #4F46E5; color: #fff; }

@media(max-width:767.98px) {
    .vr-grid { grid-template-columns: 1fr; }
    .vr-stats-row { flex-wrap: wrap; gap: .5rem; }
    .vr-stat-divider { display: none; }
}
</style>

@endsection
