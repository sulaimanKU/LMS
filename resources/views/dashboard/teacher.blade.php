@extends('applayouts.app')
@section('contents')
<div class="td-page">

    {{-- ── Header ── --}}
    <div class="td-header">
        <div class="td-welcome">
            <div class="td-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h5 class="td-title">Welcome back, {{ explode(' ', $user->name)[0] }}!</h5>
                <p class="td-subtitle">{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
        <div class="td-header-actions">
            <a href="{{ route('assignmentReviews.view') }}" class="td-btn-outline">
                <i class="fa-solid fa-clipboard-check me-2"></i>Review Assignments
            </a>
            <a href="{{ route('teacherClass.view') }}" class="td-btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Manage Classes
            </a>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="td-stats">
        <div class="td-stat">
            <div class="td-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-book"></i></div>
            <div>
                <p class="td-stat-num">{{ $coursesCount }}</p>
                <p class="td-stat-lbl">My Courses</p>
            </div>
        </div>
        <div class="td-stat">
            <div class="td-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-user-graduate"></i></div>
            <div>
                <p class="td-stat-num">{{ $studentsCount }}</p>
                <p class="td-stat-lbl">Students</p>
            </div>
        </div>
        <div class="td-stat">
            <div class="td-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <p class="td-stat-num">{{ $pendingGrading }}</p>
                <p class="td-stat-lbl">Pending Grading</p>
            </div>
        </div>
        <div class="td-stat">
            <div class="td-stat-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-file-lines"></i></div>
            <div>
                <p class="td-stat-num">{{ $totalAssignments }}</p>
                <p class="td-stat-lbl">Assignments</p>
            </div>
        </div>
    </div>

    {{-- ── Live Banner ── --}}
    @if($liveClasses->isNotEmpty())
    <div class="td-live-banner">
        <div class="td-live-dot"></div>
        <div class="td-live-text">
            <strong>{{ $liveClasses->count() }} class{{ $liveClasses->count() > 1 ? 'es' : '' }} live right now:</strong>
            {{ $liveClasses->pluck('title')->join(', ') }}
        </div>
        <a href="{{ $liveClasses->first()->meeting_link }}" target="_blank" class="td-live-btn">
            <i class="fa-solid fa-video me-1"></i>Join Now
        </a>
    </div>
    @endif

    {{-- ── Two-column body ── --}}
    <div class="td-body">

        {{-- Upcoming Classes --}}
        <div class="td-card">
            <div class="td-card-head">
                <span><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Upcoming Classes</span>
                <a href="{{ route('teacherClass.view') }}" class="td-link">View all</a>
            </div>
            @forelse($upcomingClasses as $cls)
            <div class="td-class-row">
                <div class="td-class-info">
                    <p class="td-class-title">{{ $cls->title }}</p>
                    <p class="td-class-meta">
                        <span><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                        <span><i class="fa-solid fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($cls->class_date)->format('d M') }}</span>
                        <span><i class="fa-solid fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                    </p>
                </div>
                <a href="{{ $cls->meeting_link }}" target="_blank" class="td-join-btn">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
            @empty
            <div class="td-card-empty">
                <i class="fa-solid fa-calendar-xmark"></i>
                <p>No upcoming classes scheduled.</p>
            </div>
            @endforelse
        </div>

        {{-- Recent Submissions --}}
        <div class="td-card">
            <div class="td-card-head">
                <span><i class="fa-solid fa-inbox me-2 text-primary"></i>Recent Submissions</span>
                @if($pendingGrading > 0)
                    <span class="td-warn-badge">{{ $pendingGrading }} pending</span>
                @endif
            </div>
            @forelse($recentSubs as $sub)
            @php $isGraded = $sub->status === 'graded'; @endphp
            <div class="td-sub-row">
                <div class="td-sub-avatar">{{ strtoupper(substr($sub->user?->name ?? 'S', 0, 1)) }}</div>
                <div class="td-sub-info">
                    <p class="td-sub-student">{{ $sub->user?->name ?? '—' }}</p>
                    <p class="td-sub-assign">{{ $sub->assignment?->title ?? '—' }}</p>
                </div>
                <div class="td-sub-right">
                    @if($isGraded)
                        <span class="td-pill td-pill-graded">{{ $sub->grade }}/{{ $sub->assignment?->total_points ?? 100 }}</span>
                    @else
                        <span class="td-pill td-pill-pending">Pending</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="td-card-empty">
                <i class="fa-solid fa-inbox"></i>
                <p>No submissions yet.</p>
            </div>
            @endforelse
            @if($recentSubs->isNotEmpty())
            <div class="td-card-foot">
                <a href="{{ route('assignmentReviews.view') }}" class="td-link">Grade all submissions →</a>
            </div>
            @endif
        </div>

        {{-- ── Assigned Modules (full width) ── --}}
        <div class="td-card td-card-full">
            <div class="td-card-head">
                <span><i class="fa-solid fa-layer-group me-2 text-primary"></i>Assigned Modules</span>
                <a href="{{ route('manageLessons.view') }}" class="td-link">Manage →</a>
            </div>

            @forelse($assignedModules as $mod)
            <div class="td-mod-row">
                <div class="td-mod-icon">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div class="td-mod-info">
                    <p class="td-mod-title">{{ $mod->title }}</p>
                    <p class="td-mod-meta">
                        <span><i class="fa-solid fa-tag me-1"></i>{{ $mod->category ?? '—' }}</span>
                        <span><i class="fa-solid fa-users me-1"></i>{{ $mod->students_count }} student{{ $mod->students_count !== 1 ? 's' : '' }}</span>
                        <span><i class="fa-solid fa-clock me-1"></i>{{ $mod->duration ?? '—' }}</span>
                        @if(!empty($mod->short_description))
                            <span><i class="fa-solid fa-align-left me-1"></i>{{ Str::limit($mod->short_description, 60) }}</span>
                        @endif
                    </p>
                </div>
                <div class="td-mod-right">
                    <span class="td-mod-status td-mod-status-{{ $mod->status ?? 'active' }}">
                        {{ ucfirst($mod->status ?? 'Active') }}
                    </span>
                </div>
            </div>
            @empty
            <div class="td-card-empty">
                <i class="fa-solid fa-layer-group"></i>
                <p>No modules assigned yet.</p>
            </div>
            @endforelse
        </div>

    </div>{{-- end .td-body --}}

    {{-- ── Quick Links ── --}}
    <div class="td-quick">
        <a href="{{ route('manageLessons.view') }}" class="td-quick-item">
            <div class="td-quick-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-chalkboard"></i></div>
            <span>Manage Lessons</span>
        </a>
        <a href="{{ route('teacher.assignments.uplodaView') }}" class="td-quick-item">
            <div class="td-quick-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-file-arrow-up"></i></div>
            <span>Upload Assignment</span>
        </a>
        <a href="{{ route('uploadMaterials.view') }}" class="td-quick-item">
            <div class="td-quick-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-folder-open"></i></div>
            <span>Upload Materials</span>
        </a>
        <a href="{{ route('assignmentReviews.view') }}" class="td-quick-item">
            <div class="td-quick-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-clipboard-check"></i></div>
            <span>Assignment Reviews</span>
        </a>
    </div>

</div>

<style>
.td-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

/* Header */
.td-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.td-welcome { display:flex; align-items:center; gap:.9rem; }
.td-avatar {
    width:48px; height:48px; border-radius:14px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:1.2rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.td-title    { font-size:1.15rem; font-weight:800; color:#1E293B; margin:0; }
.td-subtitle { font-size:.78rem; color:#94A3B8; margin:.1rem 0 0; }

.td-header-actions { display:flex; gap:.6rem; flex-wrap:wrap; }
.td-btn-primary {
    display:inline-flex; align-items:center;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    border:none; border-radius:10px; padding:.5rem 1rem;
    font-size:.82rem; font-weight:600; cursor:pointer; text-decoration:none;
    box-shadow:0 4px 12px rgba(79,70,229,.25); transition:all .2s;
}
.td-btn-primary:hover { transform:translateY(-1px); color:#fff; }
.td-btn-outline {
    display:inline-flex; align-items:center;
    background:#fff; color:#475569;
    border:1.5px solid #E2E8F0; border-radius:10px; padding:.5rem 1rem;
    font-size:.82rem; font-weight:600; text-decoration:none; transition:all .15s;
}
.td-btn-outline:hover { border-color:#7C3AED; color:#4F46E5; }

/* Stats */
.td-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.td-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.td-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.td-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.td-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Live Banner */
.td-live-banner {
    display:flex; align-items:center; gap:.85rem; flex-wrap:wrap;
    background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff;
    border-radius:12px; padding:.75rem 1.25rem; margin-bottom:1.25rem;
    box-shadow:0 4px 14px rgba(220,38,38,.25);
}
.td-live-dot {
    width:10px; height:10px; border-radius:50%; background:#fff; flex-shrink:0;
    box-shadow:0 0 0 3px rgba(255,255,255,.35);
    animation:td-pulse 1.4s ease-in-out infinite;
}
@keyframes td-pulse { 0%,100%{box-shadow:0 0 0 3px rgba(255,255,255,.35)} 50%{box-shadow:0 0 0 7px rgba(255,255,255,.1)} }
.td-live-text { flex:1; font-size:.82rem; }
.td-live-btn {
    background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4);
    color:#fff; border-radius:8px; padding:.35rem .8rem;
    font-size:.78rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all .15s;
}
.td-live-btn:hover { background:rgba(255,255,255,.35); color:#fff; }

/* Body */
.td-body { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }

/* Cards */
.td-card { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; }
.td-card-full { grid-column: 1 / -1; } /* ← spans both columns */
.td-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.25rem; border-bottom:1px solid #F1F5F9;
    font-size:.84rem; font-weight:700; color:#1E293B;
}
.td-link { font-size:.75rem; color:#4F46E5; text-decoration:none; font-weight:600; }
.td-link:hover { text-decoration:underline; }
.td-warn-badge { background:#FEF3C7; color:#92400E; padding:.18rem .6rem; border-radius:50px; font-size:.7rem; font-weight:700; }

.td-class-row { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.td-class-row:last-child { border-bottom:none; }
.td-class-info { flex:1; min-width:0; }
.td-class-title { font-size:.875rem; font-weight:700; color:#1E293B; margin:0; }
.td-class-meta  { display:flex; flex-wrap:wrap; gap:.6rem; font-size:.72rem; color:#64748B; margin:.2rem 0 0; }
.td-class-meta span { display:inline-flex; align-items:center; }
.td-join-btn {
    width:30px; height:30px; border-radius:8px; background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.75rem;
    text-decoration:none; flex-shrink:0; transition:all .15s;
}
.td-join-btn:hover { background:#4F46E5; color:#fff; }

.td-sub-row { display:flex; align-items:center; gap:.85rem; padding:.85rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.td-sub-row:last-child { border-bottom:none; }
.td-sub-avatar {
    width:34px; height:34px; border-radius:9px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.8rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.td-sub-info { flex:1; min-width:0; }
.td-sub-student { font-size:.84rem; font-weight:700; color:#1E293B; margin:0; }
.td-sub-assign  { font-size:.72rem; color:#94A3B8; margin:.1rem 0 0; }
.td-sub-right   { flex-shrink:0; }
.td-pill { padding:.18rem .6rem; border-radius:50px; font-size:.7rem; font-weight:700; }
.td-pill-graded  { background:#D1FAE5; color:#065F46; }
.td-pill-pending { background:#FEF3C7; color:#92400E; }

.td-card-empty { text-align:center; padding:2.5rem 1rem; color:#CBD5E1; }
.td-card-empty i { font-size:1.75rem; display:block; margin-bottom:.4rem; }
.td-card-empty p { margin:0; font-size:.8rem; }
.td-card-foot { padding:.6rem 1.25rem; border-top:1px solid #F8FAFF; text-align:right; }

/* ── Assigned Modules ── */
.td-mod-row {
    display:flex; align-items:center; gap:.9rem;
    padding:.85rem 1.25rem; border-bottom:1px solid #F8FAFF;
    transition:background .15s;
}
.td-mod-row:last-child { border-bottom:none; }
.td-mod-row:hover { background:#FAFBFF; }
.td-mod-icon {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.td-mod-info  { flex:1; min-width:0; }
.td-mod-title { font-size:.875rem; font-weight:700; color:#1E293B; margin:0; }
.td-mod-meta  {
    display:flex; flex-wrap:wrap; gap:.6rem;
    font-size:.72rem; color:#64748B; margin:.25rem 0 0;
}
.td-mod-meta span { display:inline-flex; align-items:center; gap:.2rem; }
.td-mod-right { flex-shrink:0; }
.td-mod-status {
    padding:.2rem .65rem; border-radius:50px; font-size:.7rem; font-weight:700;
}
.td-mod-status-active, .td-mod-status-published {
    background:#D1FAE5; color:#065F46;
}
.td-mod-status-inactive, .td-mod-status-draft {
    background:#F1F5F9; color:#64748B;
}
.td-mod-status-pending {
    background:#FEF3C7; color:#92400E;
}

/* Quick Links */
.td-quick { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-top:0; }
.td-quick-item {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:14px;
    padding:1rem 1.1rem; display:flex; align-items:center; gap:.75rem;
    text-decoration:none; color:#1E293B; font-size:.82rem; font-weight:600;
    box-shadow:0 1px 4px rgba(0,0,0,.04); transition:all .2s;
}
.td-quick-item:hover { border-color:#C7D2FE; box-shadow:0 4px 12px rgba(79,70,229,.1); color:#4F46E5; transform:translateY(-1px); }
.td-quick-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }

@media(max-width:991.98px) { .td-stats { grid-template-columns:repeat(2,1fr); } .td-quick { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) { .td-body { grid-template-columns:1fr; } .td-stats { grid-template-columns:repeat(2,1fr); } .td-quick { grid-template-columns:repeat(2,1fr); } }
</style>
@endsection
