@extends('applayouts.app')
@section('contents')
<div class="jc-page">

    {{-- ── Header ── --}}
    <div class="jc-header">
        <div>
            <div class="jc-header-badge"><i class="fa-solid fa-video me-1.5"></i> Virtual Classroom</div>
            <h4 class="jc-title"><i class="fa-solid fa-chalkboard-user text-indigo me-2"></i>My Classes & Live Sessions</h4>
            <p class="jc-subtitle">Join live video rooms, view upcoming schedules, and track past lectures</p>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="jc-stat-card {{ $liveClass ? 'jc-stat-red-active' : 'jc-stat-gray' }}">
                <div class="jc-stat-icon">
                    <i class="fa-solid fa-circle-dot {{ $liveClass ? 'jc-pulse-anim' : '' }}"></i>
                </div>
                <div>
                    <h3 class="jc-stat-num">{{ $liveClass ? '1' : '0' }}</h3>
                    <span class="jc-stat-lbl">Live Right Now</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="jc-stat-card jc-stat-indigo">
                <div class="jc-stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div>
                    <h3 class="jc-stat-num">{{ $upcomingCount }}</h3>
                    <span class="jc-stat-lbl">Upcoming Classes</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="jc-stat-card jc-stat-emerald">
                <div class="jc-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <h3 class="jc-stat-num">{{ $finishedCount }}</h3>
                    <span class="jc-stat-lbl">Completed</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="jc-stat-card jc-stat-amber">
                <div class="jc-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <h3 class="jc-stat-num">{{ $upcomingCount + $finishedCount + ($liveClass ? 1 : 0) }}</h3>
                    <span class="jc-stat-lbl">Total Sessions</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Featured Live Class Banner Card ── --}}
    @if($liveClass)
    <div class="jc-live-banner-card mb-4">
        <div class="jc-live-banner-left">
            <div class="jc-live-pulse-container">
                <div class="jc-live-ring"></div>
                <div class="jc-live-icon-box">
                    <i class="fa-solid fa-video"></i>
                </div>
            </div>
            <div class="jc-live-info">
                <div class="d-flex align-items-center gap-2 mb-1.5">
                    <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill" style="font-size:0.7rem; letter-spacing:0.8px;">
                        <span class="jc-blink-dot"></span> LIVE NOW
                    </span>
                    <span class="text-white-50 small" style="font-size:0.75rem;"><i class="fa-regular fa-clock me-1"></i>Started {{ \Carbon\Carbon::parse($liveClass->start_time)->format('h:i A') }}</span>
                </div>
                <h4 class="jc-live-title">{{ $liveClass->title }}</h4>
                <div class="jc-live-meta">
                    <span class="badge bg-white text-dark border me-2"><i class="fa-solid fa-book text-indigo me-1"></i>{{ $liveClass->module?->title ?? 'Module' }}</span>
                    <span class="text-white opacity-90"><i class="fa-solid fa-user-tie me-1"></i>Instructor: {{ $liveClass->teacher?->name ?? 'Faculty Member' }}</span>
                    @if($liveClass->duration)
                        <span class="ms-2 text-white-50"><i class="fa-solid fa-hourglass-half me-1"></i>{{ $liveClass->duration }} min session</span>
                    @endif
                </div>
            </div>
        </div>
        <a href="{{ route('student.joinClassAction', $liveClass->id) }}" class="jc-live-join-btn">
            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Enter Live Room
        </a>
    </div>
    @endif

    {{-- ── Filter & Search Toolbar ── --}}
    <div class="jc-toolbar mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-6 col-12">
                <div class="jc-search-wrap">
                    <i class="fa-solid fa-magnifying-glass jc-search-icon"></i>
                    <input type="text" id="jcSearchInput" class="jc-search-input" placeholder="Search class title, module name, or teacher...">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <select id="jcStatusFilter" class="form-select jc-filter-select">
                    <option value="">All Session Categories</option>
                    <option value="upcoming">Upcoming & Scheduled</option>
                    <option value="finished">Completed Sessions</option>
                    @if($cancelledClasses->isNotEmpty())
                        <option value="cancelled">Cancelled Sessions</option>
                    @endif
                </select>
            </div>
            <div class="col-md-2 col-6 text-end">
                <span id="jcResultBadge" class="badge bg-light text-dark border px-2.5 py-2 font-monospace" style="font-size: 0.75rem;">
                    All Sessions
                </span>
            </div>
        </div>
    </div>

    {{-- ── Upcoming Sessions Grid ── --}}
    <div class="jc-session-group" data-group="upcoming">
        <div class="jc-section-label"><i class="fa-solid fa-calendar-days text-indigo me-2"></i>Upcoming & Scheduled Sessions</div>

        @if($upcomingClasses->isEmpty())
        <div class="jc-empty-box mb-4">
            <i class="fa-solid fa-calendar-check mb-2"></i>
            <h6>No Upcoming Classes Scheduled</h6>
            <p>Check back later or view your course materials in enrolled modules.</p>
        </div>
        @else
        <div class="jc-grid mb-4">
            @foreach($upcomingClasses as $cls)
            @php $isToday = \Carbon\Carbon::parse($cls->class_date)->isToday(); @endphp
            <div class="jc-card {{ $isToday ? 'jc-card--today' : '' }} jc-class-item"
                 data-title="{{ strtolower($cls->title) }}"
                 data-module="{{ strtolower($cls->module?->title ?? '') }}"
                 data-teacher="{{ strtolower($cls->teacher?->name ?? '') }}"
                 data-status="upcoming">
                <div class="jc-card-top">
                    <div class="jc-date-box {{ $isToday ? 'today' : '' }}">
                        <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i') }}</span>
                        <span class="jc-date-ampm">{{ \Carbon\Carbon::parse($cls->start_time)->format('A') }}</span>
                        <span class="jc-date-day">{{ $isToday ? 'TODAY' : \Carbon\Carbon::parse($cls->class_date)->format('d M') }}</span>
                    </div>
                    <span class="jc-status-pill {{ $isToday ? 'pill-today' : 'pill-upcoming' }}">
                        <i class="fa-solid fa-clock me-1"></i>{{ $isToday ? 'Starts Today' : 'Scheduled' }}
                    </span>
                </div>
                <h6 class="jc-card-title">{{ $cls->title }}</h6>
                <div>
                    <span class="jc-module-pill"><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                </div>
                <div class="jc-teacher-row">
                    <div class="jc-teacher-av">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                    <span class="jc-teacher-name">{{ $cls->teacher?->name ?? 'Instructor' }}</span>
                </div>
                <div class="jc-card-foot mt-2 pt-2 border-top">
                    <button class="jc-btn-disabled" disabled>
                        <i class="fa-solid fa-lock me-1.5"></i>Room Opens at Start Time
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── Completed Sessions Grid ── --}}
    @if($finishedClasses->isNotEmpty())
    <div class="jc-session-group" data-group="finished">
        <div class="jc-section-label mt-4"><i class="fa-solid fa-circle-check text-emerald me-2"></i>Completed Sessions</div>
        <div class="jc-grid mb-4">
            @foreach($finishedClasses as $cls)
            <div class="jc-card jc-card--done jc-class-item"
                 data-title="{{ strtolower($cls->title) }}"
                 data-module="{{ strtolower($cls->module?->title ?? '') }}"
                 data-teacher="{{ strtolower($cls->teacher?->name ?? '') }}"
                 data-status="finished">
                <div class="jc-card-top">
                    <div class="jc-date-box done">
                        <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                        <span class="jc-date-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d M Y') }}</span>
                    </div>
                    <span class="jc-status-pill pill-done"><i class="fa-solid fa-check me-1"></i>Completed</span>
                </div>
                <h6 class="jc-card-title">{{ $cls->title }}</h6>
                <div>
                    <span class="jc-module-pill"><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                </div>
                <div class="jc-teacher-row">
                    <div class="jc-teacher-av" style="background:#ECFDF5;color:#047857;">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                    <span class="jc-teacher-name">{{ $cls->teacher?->name ?? 'Instructor' }}</span>
                </div>
                <div class="jc-card-foot mt-2 pt-2 border-top">
                    <span class="jc-btn-ended"><i class="fa-solid fa-flag-checkered me-1"></i>Session Concluded</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Cancelled Sessions Grid ── --}}
    @if($cancelledClasses->isNotEmpty())
    <div class="jc-session-group" data-group="cancelled">
        <div class="jc-section-label mt-4 text-danger"><i class="fa-solid fa-ban me-2"></i>Cancelled Sessions</div>
        <div class="jc-grid mb-4">
            @foreach($cancelledClasses as $cls)
            <div class="jc-card jc-card--cancelled jc-class-item"
                 data-title="{{ strtolower($cls->title) }}"
                 data-module="{{ strtolower($cls->module?->title ?? '') }}"
                 data-teacher="{{ strtolower($cls->teacher?->name ?? '') }}"
                 data-status="cancelled">
                <div class="jc-card-top">
                    <div class="jc-date-box cancelled">
                        <span class="jc-date-time">{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                        <span class="jc-date-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d M Y') }}</span>
                    </div>
                    <span class="jc-status-pill pill-cancelled"><i class="fa-solid fa-ban me-1"></i>Cancelled</span>
                </div>
                <h6 class="jc-card-title text-muted">{{ $cls->title }}</h6>
                <div>
                    <span class="jc-module-pill"><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                </div>
                <div class="jc-teacher-row">
                    <div class="jc-teacher-av" style="background:#FEF2F2;color:#DC2626;">{{ strtoupper(substr($cls->teacher?->name ?? 'T', 0, 1)) }}</div>
                    <span class="jc-teacher-name text-muted">{{ $cls->teacher?->name ?? 'Instructor' }}</span>
                </div>
                <div class="jc-card-foot mt-2 pt-2 border-top">
                    <span class="jc-btn-ended text-danger border-danger-subtle">Session Revoked</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div id="jcNoMatch" class="jc-empty-box d-none">
        <i class="fa-solid fa-filter-circle-xmark text-muted mb-2"></i>
        <h6>No Sessions Found</h6>
        <p>No class sessions match your current search or filter criteria.</p>
    </div>

</div>

{{-- WebSocket: Toast notification when a live class starts --}}
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
            Refresh to Join
        </button>
    </div>
    <button onclick="document.getElementById('jc-ws-toast').style.display='none'"
            style="background:none;border:none;color:rgba(255,255,255,.7);font-size:1rem;cursor:pointer;padding:0;margin-left:auto;">✕</button>
</div>

<script type="module">
document.addEventListener('DOMContentLoaded', function () {
    if (window.Echo) {
        window.Echo.channel('online-classes').listen('.class.started', (data) => {
            const toast = document.getElementById('jc-ws-toast');
            document.getElementById('jc-ws-msg').textContent =
                '"' + (data.onlineClass?.title ?? 'A class') + '" is live now!';
            toast.style.display = 'flex';
        });
    }

    const searchInput  = document.getElementById('jcSearchInput');
    const statusFilter = document.getElementById('jcStatusFilter');
    const classItems   = document.querySelectorAll('.jc-class-item');
    const noMatchBox   = document.getElementById('jcNoMatch');
    const resultBadge  = document.getElementById('jcResultBadge');

    function filterClasses() {
        const query     = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const statusVal = statusFilter ? statusFilter.value : '';

        let visibleCount = 0;

        classItems.forEach(item => {
            const title   = item.dataset.title || '';
            const module  = item.dataset.module || '';
            const teacher = item.dataset.teacher || '';
            const status  = item.dataset.status || '';

            const matchesQuery  = !query || title.includes(query) || module.includes(query) || teacher.includes(query);
            const matchesStatus = !statusVal || status === statusVal;

            if (matchesQuery && matchesStatus) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        // Hide empty group headers if all items in that group are hidden
        document.querySelectorAll('.jc-session-group').forEach(group => {
            const visibleInGroup = group.querySelectorAll('.jc-class-item:not(.d-none)').length;
            const hasEmptyBox    = group.querySelector('.jc-empty-box');
            if (!hasEmptyBox) {
                group.classList.toggle('d-none', visibleInGroup === 0 && (query || statusVal));
            }
        });

        if (noMatchBox) {
            noMatchBox.classList.toggle('d-none', visibleCount > 0);
        }

        if (resultBadge) {
            resultBadge.textContent = query || statusVal ? `Showing ${visibleCount} sessions` : 'All Sessions';
        }
    }

    if (searchInput)  searchInput.addEventListener('input', filterClasses);
    if (statusFilter) statusFilter.addEventListener('change', filterClasses);
});
</script>

<style>
/* ── Page Layout ── */
.jc-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.jc-header { margin-bottom: 1.25rem; }
.jc-header-badge {
    display: inline-block;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    margin-bottom: 0.35rem;
}
.jc-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.jc-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

/* ── Stat Cards ── */
.jc-stat-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1.1rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.jc-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}
.jc-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.jc-stat-red-active { border-color: #FCA5A5; }
.jc-stat-red-active .jc-stat-icon { background: #FEF2F2; color: #EF4444; }
.jc-stat-gray .jc-stat-icon       { background: #F1F5F9; color: #94A3B8; }
.jc-stat-indigo .jc-stat-icon     { background: #EEF2FF; color: #4F46E5; }
.jc-stat-emerald .jc-stat-icon    { background: #ECFDF5; color: #10B981; }
.jc-stat-amber .jc-stat-icon      { background: #FFFBEB; color: #F59E0B; }

.jc-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.jc-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

.jc-pulse-anim { animation: jc-pulse-dot 1.2s infinite; }
@keyframes jc-pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* ── Live Banner Card ── */
.jc-live-banner-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.2rem;
    background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
    color: #fff;
    border-radius: 18px;
    padding: 1.4rem 1.65rem;
    box-shadow: 0 10px 30px rgba(49, 46, 129, 0.25);
    position: relative;
    overflow: hidden;
}
.jc-live-banner-left { display: flex; align-items: center; gap: 1.2rem; flex: 1; min-width: 0; }
.jc-live-pulse-container { position: relative; width: 54px; height: 54px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.jc-live-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.4);
    animation: jc-ring-expand 1.6s ease-out infinite;
}
@keyframes jc-ring-expand { 0%{transform:scale(0.8);opacity:1} 100%{transform:scale(1.5);opacity:0} }

.jc-live-icon-box {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #EF4444;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    z-index: 2;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}
.jc-blink-dot {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #fff;
    margin-right: 4px;
    animation: jc-blink 1s ease-in-out infinite;
}
@keyframes jc-blink { 0%,100%{opacity:1} 50%{opacity:0.2} }

.jc-live-title { font-size: 1.15rem; font-weight: 800; margin: 0 0 0.35rem; color: #fff; line-height: 1.2; }
.jc-live-meta { font-size: 0.8rem; }
.jc-live-join-btn {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #EF4444, #DC2626);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 0.7rem 1.45rem;
    font-size: 0.86rem;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    transition: all 0.2s;
}
.jc-live-join-btn:hover { transform: translateY(-2px); color: #fff; box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5); }

/* ── Toolbar ── */
.jc-toolbar {
    background: #fff;
    border-radius: 14px;
    padding: 0.85rem 1rem;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}

.jc-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.jc-search-icon {
    position: absolute;
    left: 12px;
    color: #94A3B8;
    font-size: 0.85rem;
    pointer-events: none;
}
.jc-search-input {
    width: 100%;
    padding: 0.45rem 1rem 0.45rem 2.3rem;
    font-size: 0.84rem;
    border-radius: 9px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.jc-search-input:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.jc-filter-select {
    font-size: 0.83rem;
    padding: 0.45rem 2rem 0.45rem 0.75rem;
    border-radius: 9px;
    border: 1.5px solid #E2E8F0;
    background-color: #F8FAFF;
    color: #334155;
    font-weight: 500;
    cursor: pointer;
}
.jc-filter-select:focus {
    background-color: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

/* ── Section Label ── */
.jc-section-label { font-size: 0.82rem; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.85rem; display: flex; align-items: center; }

/* ── Grid ── */
.jc-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.15rem; }

/* ── Session Cards ── */
.jc-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    padding: 1.15rem;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    transition: box-shadow 0.2s, transform 0.2s;
}
.jc-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.08); transform: translateY(-2px); }
.jc-card--today { border-color: #C7D2FE; background: #FAF5FF; }
.jc-card--done  { background: #FAFFFE; border-color: #A7F3D0; }
.jc-card--cancelled { background: #FFF5F5; border-color: #FECACA; opacity: 0.88; }

.jc-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem; }
.jc-date-box {
    background: #F8FAFF;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 0.45rem 0.65rem;
    text-align: center;
    min-width: 66px;
}
.jc-date-box.today { background: #EEF2FF; border-color: #C7D2FE; }
.jc-date-box.done  { background: #ECFDF5; border-color: #A7F3D0; }
.jc-date-box.cancelled { background: #FFF5F5; border-color: #FECACA; }

.jc-date-time { display: block; font-size: 0.82rem; font-weight: 800; color: #0F172A; line-height: 1; }
.jc-date-ampm { display: block; font-size: 0.62rem; color: #64748B; font-weight: 700; margin-top: 0.1rem; }
.jc-date-day  { display: block; font-size: 0.65rem; font-weight: 800; color: #4F46E5; letter-spacing: 0.4px; border-top: 1px solid #E2E8F0; margin-top: 0.25rem; padding-top: 0.25rem; }

.jc-status-pill { padding: 0.22rem 0.65rem; border-radius: 50px; font-size: 0.68rem; font-weight: 700; white-space: nowrap; }
.pill-today     { background: #FEF3C7; color: #D97706; border: 1px solid #FDE68A; }
.pill-upcoming  { background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; }
.pill-done      { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
.pill-cancelled { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

.jc-card-title { font-size: 0.92rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.35; }
.jc-module-pill { display: inline-block; background: #F1F5F9; color: #475569; padding: 0.15rem 0.6rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }

.jc-teacher-row { display: flex; align-items: center; gap: 0.6rem; }
.jc-teacher-av {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.jc-teacher-name { font-size: 0.78rem; font-weight: 600; color: #334155; }

.jc-btn-disabled, .jc-btn-ended {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 0.45rem;
    border-radius: 9px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1.5px dashed #E2E8F0;
    color: #94A3B8;
    background: transparent;
    cursor: default;
}

.jc-empty-box { text-align: center; background: #fff; border: 1.5px solid #F1F5F9; border-radius: 16px; padding: 3rem 1rem; color: #94A3B8; }
.jc-empty-box i { font-size: 2.5rem; color: #CBD5E1; display: block; margin-bottom: 0.5rem; }
.jc-empty-box h6 { font-size: 0.95rem; font-weight: 800; color: #1E293B; margin: 0 0 0.2rem; }
.jc-empty-box p  { margin: 0; font-size: 0.82rem; }

@media(max-width:1100px) { .jc-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:640px)    { .jc-grid { grid-template-columns: 1fr; } }
</style>
@endsection
