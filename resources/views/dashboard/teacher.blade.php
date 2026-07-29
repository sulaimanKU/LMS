@extends('applayouts.app')
@section('contents')
<div class="td-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Hero Header Banner ── --}}
    <div class="td-hero-card mb-4">
        <div class="td-hero-content">
            <div class="td-hero-user">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}" class="td-hero-avatar" alt="{{ $user->name }}">
                @else
                    <div class="td-hero-avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
                <div>
                    @php
                        $hour = (int) now()->format('H');
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    <div class="td-hero-badge"><i class="fa-solid fa-chalkboard-user me-1.5"></i> Faculty Portal</div>
                    <h3 class="td-hero-title">{{ $greeting }}, {{ $user->name }}! 👨‍🏫</h3>
                    <p class="td-hero-subtitle">
                        <i class="fa-regular fa-calendar me-1"></i> {{ now()->format('l, F j, Y') }} 
                        <span class="mx-2">•</span> 
                        Manage your teaching schedule and student submissions
                    </p>
                </div>
            </div>
            <div class="td-hero-actions">
                <a href="{{ route('assignmentReviews.view') }}" class="td-btn-hero-secondary">
                    <i class="fa-solid fa-clipboard-check me-2"></i>Review Submissions
                    @if($pendingGrading > 0)
                        <span class="td-hero-pill-badge bg-amber">{{ $pendingGrading }}</span>
                    @endif
                </a>
                <a href="{{ route('teacherClass.view') }}" class="td-btn-hero-primary">
                    <i class="fa-solid fa-video me-2"></i>Manage Classes
                </a>
            </div>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="td-kpi-card td-kpi-indigo">
                <div class="td-kpi-icon"><i class="fa-solid fa-book"></i></div>
                <div>
                    <h3 class="td-kpi-num">{{ $coursesCount }}</h3>
                    <span class="td-kpi-lbl">Assigned Courses</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="td-kpi-card td-kpi-emerald">
                <div class="td-kpi-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <div>
                    <h3 class="td-kpi-num">{{ $studentsCount }}</h3>
                    <span class="td-kpi-lbl">Enrolled Students</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="td-kpi-card td-kpi-amber">
                <div class="td-kpi-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <h3 class="td-kpi-num">{{ $pendingGrading }}</h3>
                    <span class="td-kpi-lbl">Pending Grading</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="td-kpi-card td-kpi-purple">
                <div class="td-kpi-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <h3 class="td-kpi-num">{{ $totalAssignments }}</h3>
                    <span class="td-kpi-lbl">Assignments Created</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Live Class Banner ── --}}
    @if($liveClasses->isNotEmpty())
    <div class="td-live-banner mb-4">
        <div class="td-live-pulse-wrap">
            <span class="td-live-pulse"></span>
            <span class="td-live-dot"></span>
        </div>
        <div class="td-live-text">
            <span class="badge bg-white text-danger fw-bold me-2 px-2 py-1" style="font-size:0.7rem;">LIVE ROOM ACTIVE</span>
            <strong>{{ $liveClasses->count() }} class{{ $liveClasses->count() > 1 ? 'es' : '' }} live right now:</strong>
            {{ $liveClasses->pluck('title')->join(', ') }}
        </div>
        <a href="{{ $liveClasses->first()->meeting_link }}" target="_blank" class="td-live-btn">
            <i class="fa-solid fa-video me-1.5"></i>Join Room Now
        </a>
    </div>
    @endif

    {{-- ── Two-column body ── --}}
    <div class="td-body mb-4">

        {{-- Upcoming Classes --}}
        <div class="td-section-card">
            <div class="td-card-head">
                <span class="td-card-title"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Upcoming Scheduled Classes</span>
                <a href="{{ route('teacherClass.view') }}" class="td-link-sm">View Schedule <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="p-3">
                @forelse($upcomingClasses as $cls)
                <div class="td-class-row">
                    <div class="td-class-date-box">
                        <span class="td-class-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d') }}</span>
                        <span class="td-class-month">{{ \Carbon\Carbon::parse($cls->class_date)->format('M') }}</span>
                    </div>
                    <div class="td-class-info">
                        <h6 class="td-class-title">{{ $cls->title }}</h6>
                        <p class="td-class-meta">
                            <span><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                            <span class="ms-2 text-primary fw-bold"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                        </p>
                    </div>
                    <a href="{{ $cls->meeting_link }}" target="_blank" class="td-join-btn" title="Open Meeting Room">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                @empty
                <div class="td-card-empty py-4 text-center">
                    <i class="fa-solid fa-calendar-check text-muted opacity-50 mb-2" style="font-size:2rem;"></i>
                    <p class="text-muted small mb-0">No upcoming classes scheduled.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Submissions --}}
        <div class="td-section-card">
            <div class="td-card-head">
                <span class="td-card-title"><i class="fa-solid fa-inbox text-indigo me-2"></i>Recent Submissions</span>
                @if($pendingGrading > 0)
                    <span class="badge bg-amber-light text-amber font-monospace px-2 py-1 rounded-pill" style="font-size:0.7rem; font-weight:700;">
                        {{ $pendingGrading }} pending
                    </span>
                @endif
            </div>
            <div class="p-3">
                @forelse($recentSubs as $sub)
                @php $isGraded = $sub->status === 'graded'; @endphp
                <div class="td-sub-row">
                    <div class="td-sub-avatar">{{ strtoupper(substr($sub->user?->name ?? 'S', 0, 1)) }}</div>
                    <div class="td-sub-info">
                        <h6 class="td-sub-student">{{ $sub->user?->name ?? 'Student' }}</h6>
                        <p class="td-sub-assign">
                            <span class="fw-bold">{{ $sub->assignment?->title ?? 'Assignment' }}</span> 
                            <span class="text-muted">• {{ $sub->assignment?->module?->title ?? '' }}</span>
                        </p>
                    </div>
                    <div class="td-sub-right">
                        @if($isGraded)
                            <span class="badge bg-emerald-light text-emerald font-monospace px-2 py-1 rounded-pill" style="font-size:0.72rem; font-weight:700;">
                                {{ $sub->grade }}/{{ $sub->assignment?->total_points ?? 100 }}
                            </span>
                        @else
                            <span class="badge bg-amber-light text-amber px-2 py-1 rounded-pill" style="font-size:0.7rem; font-weight:600;">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="td-card-empty py-4 text-center">
                    <i class="fa-solid fa-inbox text-muted opacity-50 mb-2" style="font-size:2rem;"></i>
                    <p class="text-muted small mb-0">No assignment submissions yet.</p>
                </div>
                @endforelse
                @if($recentSubs->isNotEmpty())
                <div class="pt-3 mt-2 border-top text-end">
                    <a href="{{ route('assignmentReviews.view') }}" class="td-link-sm fw-bold">Review & Grade Submissions <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- end .td-body --}}

    {{-- ── Assigned Modules (Full Width) ── --}}
    <div class="td-section-card mb-4">
        <div class="td-card-head">
            <span class="td-card-title"><i class="fa-solid fa-layer-group text-primary me-2"></i>Assigned Modules & Enrolled Students</span>
            <a href="{{ route('manageLessons.view') }}" class="td-link-sm">Manage Modules <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="p-3">
            @forelse($assignedModules as $mod)
            <div class="td-mod-wrapper mb-3 p-3 rounded-3" style="background:#F8FAFF; border:1.5px solid #EDF2F7;">
                <div class="td-mod-row d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="td-mod-icon">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <div>
                            <h6 class="td-mod-title mb-1">{{ $mod->title }}</h6>
                            <div class="td-mod-meta d-flex flex-wrap gap-2 text-muted" style="font-size:0.75rem;">
                                <span><i class="fa-solid fa-tag me-1 text-primary"></i>{{ $mod->category ?? 'General' }}</span>
                                <span><i class="fa-solid fa-users me-1 text-emerald"></i>{{ $mod->students_count }} student{{ $mod->students_count !== 1 ? 's' : '' }}</span>
                                @if($mod->duration)
                                    <span><i class="fa-regular fa-clock me-1 text-amber"></i>{{ $mod->duration }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-emerald-light text-emerald px-2.5 py-1.5 rounded-pill" style="font-size:0.7rem; font-weight:700;">
                            <i class="fa-solid fa-circle me-1" style="font-size:0.45rem;"></i>{{ ucfirst($mod->status ?? 'Active') }}
                        </span>
                    </div>
                </div>

                {{-- Enrolled Students Chips --}}
                <div class="td-mod-students pt-2 border-top">
                    @if($mod->enrolled_students->isNotEmpty())
                        <div class="td-student-list d-flex flex-wrap gap-1.5 align-items-center">
                            <span class="text-muted small me-1" style="font-size:0.72rem;">Students:</span>
                            @foreach($mod->enrolled_students as $std)
                                <div class="td-student-chip" title="{{ $std->email }}">
                                    <div class="td-student-avatar-sm">
                                        {{ strtoupper(substr($std->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $std->name }}</span>
                                </div>
                            @endforeach
                            @if($mod->students_count > 5)
                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size:0.7rem;">+{{ $mod->students_count - 5 }} more</span>
                            @endif
                        </div>
                    @else
                        <p class="td-no-students text-muted small mb-0" style="font-size:0.75rem; font-style:italic;">No students enrolled in this module yet.</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="td-card-empty py-4 text-center">
                <i class="fa-solid fa-layer-group text-muted opacity-50 mb-2" style="font-size:2rem;"></i>
                <p class="text-muted small mb-0">No modules assigned to your profile yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Quick Shortcuts Bar ── --}}
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <a href="{{ route('manageLessons.view') }}" class="td-quick-card text-decoration-none">
                <div class="td-quick-icon bg-indigo-light text-indigo"><i class="fa-solid fa-chalkboard"></i></div>
                <span class="td-quick-title">Manage Lessons</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('teacher.assignments.uplodaView') }}" class="td-quick-card text-decoration-none">
                <div class="td-quick-icon bg-amber-light text-amber"><i class="fa-solid fa-file-arrow-up"></i></div>
                <span class="td-quick-title">Create Assignment</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('uploadMaterials.view') }}" class="td-quick-card text-decoration-none">
                <div class="td-quick-icon bg-emerald-light text-emerald"><i class="fa-solid fa-folder-open"></i></div>
                <span class="td-quick-title">Upload Materials</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('assignmentReviews.view') }}" class="td-quick-card text-decoration-none">
                <div class="td-quick-icon bg-purple-light text-purple"><i class="fa-solid fa-clipboard-check"></i></div>
                <span class="td-quick-title">Grade Submissions</span>
            </a>
        </div>
    </div>

</div>

<style>
/* ── Page Layout ── */
.td-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Hero Banner ── */
.td-hero-card {
    background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
    border-radius: 18px;
    padding: 1.8rem 2rem;
    color: #fff;
    box-shadow: 0 10px 30px rgba(49, 46, 129, 0.2);
    position: relative;
    overflow: hidden;
}
.td-hero-card::after {
    content: "";
    position: absolute;
    right: -40px;
    top: -40px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.3) 0%, rgba(255, 255, 255, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}

.td-hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.2rem;
    position: relative;
    z-index: 2;
}
.td-hero-user { display: flex; align-items: center; gap: 1.25rem; }
.td-hero-avatar {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.td-hero-avatar-initial {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    color: #fff;
    font-size: 1.6rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.td-hero-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(4px);
    color: #C7D2FE;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    margin-bottom: 0.4rem;
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.td-hero-title { font-size: 1.45rem; font-weight: 800; color: #fff; margin: 0; line-height: 1.2; }
.td-hero-subtitle { font-size: 0.82rem; color: #A5B4FC; margin: 0.3rem 0 0; }

.td-hero-actions { display: flex; align-items: center; gap: 0.75rem; }
.td-btn-hero-primary {
    display: inline-flex;
    align-items: center;
    background: #fff;
    color: #4338CA;
    border: none;
    border-radius: 12px;
    padding: 0.65rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: all 0.2s;
}
.td-btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.2); color: #3730A3; }
.td-btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    border-radius: 12px;
    padding: 0.65rem 1.15rem;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.td-btn-hero-secondary:hover { background: rgba(255, 255, 255, 0.22); color: #fff; }
.td-hero-pill-badge { margin-left: 0.4rem; font-size: 0.7rem; padding: 0.15rem 0.45rem; border-radius: 50px; background: #F59E0B; color: #fff; font-weight: 800; }

/* ── KPI Stat Cards ── */
.td-kpi-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.td-kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.06); }
.td-kpi-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.td-kpi-indigo .td-kpi-icon { background: #EEF2FF; color: #4F46E5; }
.td-kpi-emerald .td-kpi-icon { background: #ECFDF5; color: #10B981; }
.td-kpi-amber .td-kpi-icon   { background: #FFFBEB; color: #F59E0B; }
.td-kpi-purple .td-kpi-icon  { background: #F3E8FF; color: #9333EA; }

.td-kpi-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.td-kpi-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* ── Live Banner ── */
.td-live-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #DC2626, #EF4444);
    color: #fff;
    border-radius: 14px;
    padding: 0.9rem 1.35rem;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.25);
}
.td-live-pulse-wrap { position: relative; width: 14px; height: 14px; display: flex; align-items: center; justify-content: center; }
.td-live-dot { width: 10px; height: 10px; border-radius: 50%; background: #fff; z-index: 2; }
.td-live-pulse {
    position: absolute; width: 18px; height: 18px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    animation: td-live-ping 1.4s cubic-bezier(0, 0, 0.2, 1) infinite;
}
@keyframes td-live-ping { 75%, 100% { transform: scale(2); opacity: 0; } }

.td-live-text { flex: 1; font-size: 0.86rem; }
.td-live-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1.5px solid rgba(255, 255, 255, 0.4);
    color: #fff;
    border-radius: 10px;
    padding: 0.45rem 1rem;
    font-size: 0.8rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}
.td-live-btn:hover { background: #fff; color: #DC2626; }

/* ── Body Grid ── */
.td-body { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

/* ── Section Cards ── */
.td-section-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}
.td-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem 0.85rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.td-card-title { font-size: 0.88rem; font-weight: 800; color: #0F172A; }
.td-link-sm { font-size: 0.76rem; font-weight: 700; color: #4F46E5; text-decoration: none; }
.td-link-sm:hover { text-decoration: underline; }

/* Upcoming Class Row */
.td-class-row {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 0.75rem 0;
    border-bottom: 1px dashed #F1F5F9;
}
.td-class-row:last-child { border-bottom: none; }
.td-class-date-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.td-class-day { font-size: 1rem; font-weight: 800; line-height: 1; }
.td-class-month { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; margin-top: 0.1rem; }
.td-class-info { flex: 1; min-width: 0; }
.td-class-title { font-size: 0.85rem; font-weight: 700; color: #1E293B; margin: 0; }
.td-class-meta { font-size: 0.72rem; color: #64748B; margin-top: 0.15rem; }
.td-join-btn {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    text-decoration: none;
    transition: all 0.2s;
}
.td-join-btn:hover { background: #4F46E5; color: #fff; transform: scale(1.08); }

/* Submissions Row */
.td-sub-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 0;
    border-bottom: 1px dashed #F1F5F9;
}
.td-sub-row:last-child { border-bottom: none; }
.td-sub-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.td-sub-info { flex: 1; min-width: 0; }
.td-sub-student { font-size: 0.85rem; font-weight: 700; color: #1E293B; margin: 0; }
.td-sub-assign  { font-size: 0.72rem; color: #64748B; margin-top: 0.1rem; }

/* Assigned Module Icon */
.td-mod-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.td-mod-title { font-size: 0.92rem; font-weight: 800; color: #0F172A; }

/* Student Chips */
.td-student-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #fff;
    border: 1px solid #E2E8F0;
    padding: 0.18rem 0.55rem 0.18rem 0.2rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #334155;
}
.td-student-avatar-sm {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #4F46E5;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    font-weight: 800;
}

/* Quick Action Cards */
.td-quick-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: all 0.2s;
}
.td-quick-card:hover {
    border-color: #4F46E5;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.1);
}
.td-quick-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.bg-indigo-light { background: #EEF2FF; }
.text-indigo     { color: #4F46E5; }
.bg-amber-light  { background: #FFFBEB; }
.text-amber      { color: #D97706; }
.bg-emerald-light{ background: #ECFDF5; }
.text-emerald    { color: #047857; }
.bg-purple-light { background: #F3E8FF; }
.text-purple     { color: #9333EA; }

.td-quick-title { font-size: 0.82rem; font-weight: 700; color: #1E293B; }

@media(max-width:991.98px) { .td-body { grid-template-columns: 1fr; } }
@media(max-width:767.98px) {
    .td-hero-card { padding: 1.35rem 1.25rem; }
    .td-hero-content { flex-direction: column; align-items: flex-start; }
    .td-hero-actions { width: 100%; justify-content: flex-start; }
}
</style>
@endsection
