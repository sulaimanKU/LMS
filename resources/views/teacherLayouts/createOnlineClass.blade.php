@extends('applayouts.app')
@section('contents')

<style>
:root { --primary: #6366f1; }

#live-hub { padding: 1.5rem; background: #f8fafc; min-height: 100%; }

/* Header */
.hub-header { margin-bottom: 1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem; }
.hub-title  { font-size:1.2rem; font-weight:800; color:#1e293b; margin:0; }
.hub-sub    { font-size:.8rem; color:#94a3b8; margin:.1rem 0 0; }

/* Section label */
.hub-section-label { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.8px; color:#94a3b8; margin:0 0 .6rem; }

/* Live banner */
.live-banner {
    background: linear-gradient(135deg,#fef2f2,#fff5f5);
    border: 1.5px solid #fecaca;
    border-radius: 16px;
    padding: 1.1rem 1.25rem;
    margin-bottom: 1rem;
    display: flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
}
.live-banner-left { display:flex; align-items:center; gap:.9rem; }
.live-dot-wrap { position:relative; width:38px; height:38px; flex-shrink:0; }
.live-dot { width:38px; height:38px; border-radius:50%; background:#ef4444; display:flex; align-items:center; justify-content:center; color:#fff; font-size:.85rem; }
.live-ring { position:absolute; inset:0; border-radius:50%; border:2px solid #ef4444; animation: lring 1.4s ease-out infinite; }
@keyframes lring { 0%{transform:scale(1);opacity:.8} 100%{transform:scale(1.7);opacity:0} }
.live-title  { font-size:.95rem; font-weight:800; color:#1e293b; margin:0; }
.live-meta   { font-size:.75rem; color:#64748b; margin:.15rem 0 0; }
.live-badge  { background:#ef4444; color:#fff; font-size:.65rem; font-weight:800; padding:.15rem .55rem; border-radius:50px; text-transform:uppercase; }
.live-actions { display:flex; gap:.5rem; flex-wrap:wrap; }

/* Upcoming card */
.oc-card {
    background:#fff; border:1.5px solid #f1f5f9; border-radius:14px;
    padding:.95rem 1.1rem; margin-bottom:.75rem;
    display:flex; align-items:center; gap:.9rem; flex-wrap:wrap;
    box-shadow:0 1px 4px rgba(0,0,0,.04); transition:box-shadow .2s;
}
.oc-card:hover { box-shadow:0 4px 14px rgba(0,0,0,.08); border-color:#c7d2fe; }
.oc-time { min-width:64px; text-align:center; background:#f8faff; border-radius:10px; padding:.45rem .5rem; flex-shrink:0; border:1px solid #e2e8f0; }
.oc-time-val  { font-size:.95rem; font-weight:800; color:#1e293b; line-height:1; }
.oc-time-ampm { font-size:.62rem; font-weight:700; color:#94a3b8; text-transform:uppercase; }
.oc-info { flex:1; min-width:0; }
.oc-title    { font-size:.88rem; font-weight:700; color:#1e293b; margin:0; }
.oc-module   { font-size:.73rem; color:#64748b; margin:.1rem 0 0; }
.oc-date     { font-size:.7rem; color:#94a3b8; margin:.1rem 0 0; }
.oc-actions  { display:flex; gap:.4rem; align-items:center; flex-shrink:0; }

/* Buttons */
.btn-live-join {
    background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
    border:none; padding:.45rem 1rem; border-radius:9px;
    font-size:.78rem; font-weight:700; cursor:pointer; text-decoration:none;
    display:inline-flex; align-items:center; gap:.35rem;
    box-shadow:0 3px 10px rgba(79,70,229,.25); transition:.15s;
}
.btn-live-join:hover { transform:translateY(-1px); color:#fff; }
.btn-end {
    background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca;
    padding:.4rem .85rem; border-radius:9px; font-size:.78rem; font-weight:700;
    cursor:pointer; display:inline-flex; align-items:center; gap:.35rem; transition:.15s;
}
.btn-end:hover { background:#fee2e2; }
.btn-start {
    background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
    border:none; padding:.4rem .9rem; border-radius:9px;
    font-size:.78rem; font-weight:700; cursor:pointer;
    display:inline-flex; align-items:center; gap:.35rem;
    box-shadow:0 3px 8px rgba(79,70,229,.2); transition:.15s;
}
.btn-start:hover { transform:translateY(-1px); }
.btn-cancel-cls {
    background:#f8fafc; color:#64748b; border:1.5px solid #e2e8f0;
    padding:.4rem .75rem; border-radius:9px; font-size:.78rem; font-weight:600;
    cursor:pointer; display:inline-flex; align-items:center; gap:.35rem; transition:.15s;
}
.btn-cancel-cls:hover { background:#fee2e2; color:#dc2626; border-color:#fecaca; }
.btn-new-class {
    background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
    border:none; padding:.5rem 1.15rem; border-radius:10px;
    font-size:.82rem; font-weight:700; cursor:pointer;
    display:inline-flex; align-items:center; gap:.4rem;
    box-shadow:0 3px 10px rgba(79,70,229,.25); transition:.15s;
}
.btn-new-class:hover { transform:translateY(-1px); color:#fff; }

/* Empty state */
.hub-empty { text-align:center; padding:3.5rem 1rem; background:#fff; border:1.5px solid #f1f5f9; border-radius:16px; color:#cbd5e1; }
.hub-empty i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
.hub-empty p { margin:0; font-size:.88rem; }
</style>

<div id="live-hub">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="hub-header">
        <div>
            <h5 class="hub-title">Live Sessions</h5>
            <p class="hub-sub">Manage your online classes — start, end, or schedule new sessions</p>
        </div>
        <button class="btn-new-class" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <i class="fa-solid fa-plus"></i> Schedule Class
        </button>
    </div>

    {{-- ── LIVE NOW ── --}}
    @if($liveClasses->isNotEmpty())
    <p class="hub-section-label"><i class="fa-solid fa-circle text-danger me-1"></i>Live Right Now</p>
    @foreach($liveClasses as $class)
    <div class="live-banner">
        <div class="live-banner-left">
            <div class="live-dot-wrap">
                <div class="live-ring"></div>
                <div class="live-dot"><i class="fa-solid fa-video"></i></div>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <p class="live-title">{{ $class->title }}</p>
                    <span class="live-badge">Live</span>
                </div>
                <p class="live-meta">
                    <i class="fa-solid fa-book me-1"></i>{{ $class->module->title ?? '—' }}
                    &nbsp;·&nbsp;
                    <i class="fa-solid fa-clock me-1"></i>Started {{ \Carbon\Carbon::parse($class->class_date.' '.$class->start_time)->format('h:i A') }}
                    @if($class->duration) &nbsp;·&nbsp; {{ $class->duration }} min @endif
                </p>
                @if($class->meeting_link)
                <p class="live-meta mt-1">
                    <i class="fa-solid fa-link me-1 text-primary"></i>
                    <span style="font-family:monospace;font-size:.72rem;">{{ $class->meeting_link }}</span>
                </p>
                @endif
            </div>
        </div>
        <div class="live-actions">
            @if($class->meeting_link)
            <a href="{{ $class->meeting_link }}" target="_blank" class="btn-live-join">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Room
            </a>
            @endif
            <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" name="end" class="btn-end">
                    <i class="fa-solid fa-stop"></i> End Class
                </button>
            </form>
        </div>
    </div>
    @endforeach
    @endif

    {{-- ── UPCOMING ── --}}
    <p class="hub-section-label mt-3"><i class="fa-solid fa-calendar-days me-1"></i>Upcoming Classes</p>

    @forelse($upcomingClasses as $class)
    @php
        $time = \Carbon\Carbon::parse($class->start_time);
    @endphp
    <div class="oc-card">
        <div class="oc-time">
            <div class="oc-time-val">{{ $time->format('h:i') }}</div>
            <div class="oc-time-ampm">{{ $time->format('A') }}</div>
        </div>
        <div class="oc-info">
            <p class="oc-title">{{ $class->title }}</p>
            <p class="oc-module"><i class="fa-solid fa-book me-1"></i>{{ $class->module->title ?? '—' }}</p>
            <p class="oc-date"><i class="fa-solid fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($class->class_date)->format('d M Y') }} @if($class->duration) &nbsp;·&nbsp; {{ $class->duration }} min @endif</p>
        </div>
        <div class="oc-actions">
            <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST" class="d-flex gap-2">
                @csrf @method('PATCH')
                <button type="submit" name="start" class="btn-start">
                    <i class="fa-solid fa-play"></i> Start
                </button>
                <button type="submit" name="cancel" class="btn-cancel-cls"
                    onclick="return confirm('Cancel this class?')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    @if($liveClasses->isEmpty())
    <div class="hub-empty">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No classes scheduled yet.<br><span style="font-size:.78rem;">Click <strong>Schedule Class</strong> to add one.</span></p>
    </div>
    @else
    <div style="padding:.75rem 1rem; color:#94a3b8; font-size:.8rem; background:#fff; border:1.5px solid #f1f5f9; border-radius:12px;">
        <i class="fa-solid fa-check me-2 text-success"></i>No upcoming classes — you're all caught up.
    </div>
    @endif
    @endforelse

</div>

{{-- ── SCHEDULE CLASS MODAL ── --}}
<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:18px;overflow:hidden;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:1.25rem 1.5rem;">
                <div>
                    <h5 class="modal-title fw-bold text-white mb-0"><i class="fa-solid fa-video me-2"></i>Schedule New Class</h5>
                    <p class="text-white-50 mb-0" style="font-size:.78rem;">Fill in the details to create an online session</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('teacher.online-classes.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted mb-1">Module <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="module_id" required>
                                <option value="" disabled selected>Select module…</option>
                                @forelse($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @empty
                                    <option disabled>No modules assigned yet</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted mb-1">Class Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm" required placeholder="e.g. Intro to Laravel">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted mb-1">Date <span class="text-danger">*</span></label>
                            <input type="date" name="class_date" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted mb-1">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted mb-1">Meeting Link <span class="text-danger">*</span></label>
                            <input type="url" name="meeting_link" class="form-control form-control-sm" required placeholder="https://meet.google.com/…">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted mb-1">Duration (min)</label>
                            <input type="number" name="duration" class="form-control form-control-sm" placeholder="60" min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted mb-1">Meeting ID</label>
                            <input type="text" name="meeting_id" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted mb-1">Password</label>
                            <input type="text" name="meeting_password" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted mb-1">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Optional short summary…"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold mt-3 shadow-sm">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Create Class
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
