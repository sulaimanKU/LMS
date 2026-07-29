@extends('applayouts.app')
@section('contents')

<div class="lh-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="lh-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="lh-header-badge"><i class="fa-solid fa-video me-1.5"></i> Faculty Live Hub</div>
                <h4 class="lh-title"><i class="fa-solid fa-chalkboard-user text-indigo me-2"></i>Virtual Classrooms & Scheduler</h4>
                <p class="lh-subtitle">Monitor live rooms, launch scheduled lectures, or create new virtual sessions</p>
            </div>
            <div>
                <button class="btn lh-btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                    <i class="fa-solid fa-plus me-1.5"></i>Schedule New Class
                </button>
            </div>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="lh-stat-card {{ $liveClasses->isNotEmpty() ? 'lh-stat-red-active' : 'lh-stat-gray' }}">
                <div class="lh-stat-icon">
                    <i class="fa-solid fa-circle-dot {{ $liveClasses->isNotEmpty() ? 'lh-pulse-anim' : '' }}"></i>
                </div>
                <div>
                    <h3 class="lh-stat-num">{{ $liveClasses->count() }}</h3>
                    <span class="lh-stat-lbl">Live Right Now</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="lh-stat-card lh-stat-indigo">
                <div class="lh-stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div>
                    <h3 class="lh-stat-num">{{ $upcomingClasses->count() }}</h3>
                    <span class="lh-stat-lbl">Upcoming Scheduled</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="lh-stat-card lh-stat-emerald">
                <div class="lh-stat-icon"><i class="fa-solid fa-book"></i></div>
                <div>
                    <h3 class="lh-stat-num">{{ $teacher_courses->count() }}</h3>
                    <span class="lh-stat-lbl">Assigned Modules</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Featured Live Rooms ── --}}
    @if($liveClasses->isNotEmpty())
        <div class="mb-4">
            <div class="lh-section-label mb-2 text-danger"><i class="fa-solid fa-circle text-danger me-2"></i>Active Live Sessions</div>
            @foreach($liveClasses as $class)
                <div class="lh-live-banner-card mb-3">
                    <div class="lh-live-banner-left">
                        <div class="lh-live-pulse-container">
                            <div class="lh-live-ring"></div>
                            <div class="lh-live-icon-box">
                                <i class="fa-solid fa-video"></i>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill" style="font-size:0.68rem;">
                                    <span class="lh-blink-dot"></span> LIVE NOW
                                </span>
                                <h5 class="lh-live-title mb-0">{{ $class->title }}</h5>
                            </div>
                            <div class="lh-live-meta d-flex flex-wrap gap-3 text-muted" style="font-size:0.78rem;">
                                <span><i class="fa-solid fa-book me-1 text-indigo"></i>{{ $class->module->title ?? 'General' }}</span>
                                <span><i class="fa-regular fa-clock me-1 text-amber"></i>Started {{ \Carbon\Carbon::parse($class->class_date.' '.$class->start_time)->format('h:i A') }}</span>
                                @if($class->duration)
                                    <span><i class="fa-solid fa-hourglass-half me-1 text-emerald"></i>{{ $class->duration }} mins</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($class->meeting_link)
                            <a href="{{ $class->meeting_link }}" target="_blank" class="btn btn-danger text-white rounded-pill px-3 py-2 fw-bold" style="font-size:0.8rem;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1.5"></i>Enter Live Room
                            </a>
                        @endif
                        <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" name="end" class="btn btn-dark rounded-pill px-3 py-2 fw-bold" style="font-size:0.8rem;">
                                <i class="fa-solid fa-stop me-1.5"></i>End Session
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Upcoming Sessions Grid ── --}}
    <div class="lh-card mb-4">
        <div class="lh-card-head d-flex align-items-center justify-content-between">
            <span class="lh-card-title"><i class="fa-solid fa-calendar-days me-2 text-indigo"></i>Upcoming Scheduled Classes</span>
            <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace" style="font-size:0.75rem;">
                {{ $upcomingClasses->count() }} Sessions Scheduled
            </span>
        </div>

        <div class="p-3">
            @forelse($upcomingClasses as $class)
                @php
                    $time = \Carbon\Carbon::parse($class->start_time);
                    $isToday = \Carbon\Carbon::parse($class->class_date)->isToday();
                @endphp
                <div class="lh-oc-row mb-2.5 p-3 rounded-3" style="background:#F8FAFF; border:1.5px solid #EDF2F7;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="lh-time-box {{ $isToday ? 'today' : '' }}">
                                <span class="lh-time-val">{{ $time->format('h:i') }}</span>
                                <span class="lh-time-ampm">{{ $time->format('A') }}</span>
                            </div>
                            <div>
                                <h6 class="lh-oc-title mb-1">{{ $class->title }}</h6>
                                <div class="d-flex flex-wrap gap-2 text-muted" style="font-size:0.75rem;">
                                    <span><i class="fa-solid fa-book me-1 text-indigo"></i>{{ $class->module->title ?? 'General' }}</span>
                                    <span><i class="fa-regular fa-calendar me-1 text-primary"></i>{{ \Carbon\Carbon::parse($class->class_date)->format('d M Y') }}</span>
                                    @if($isToday)
                                        <span class="badge bg-amber-light text-amber border border-warning-subtle font-monospace" style="font-size:0.68rem;">TODAY</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST" class="d-inline-flex gap-1.5">
                                @csrf @method('PATCH')
                                <button type="submit" name="start" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="font-size:0.78rem;">
                                    <i class="fa-solid fa-play me-1"></i>Start Class
                                </button>
                                <button type="submit" name="cancel" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" style="font-size:0.78rem;"
                                    onclick="return confirm('Cancel this scheduled class?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                @if($liveClasses->isEmpty())
                    <div class="py-5 text-center">
                        <i class="fa-solid fa-calendar-xmark text-muted opacity-25 mb-2" style="font-size:2.5rem;"></i>
                        <h6 class="text-dark fw-bold mb-1">No Upcoming Classes Scheduled</h6>
                        <p class="text-muted small mb-3">Schedule your virtual lectures and share meeting links with students.</p>
                        <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                            <i class="fa-solid fa-plus me-1"></i>Schedule First Class
                        </button>
                    </div>
                @else
                    <div class="p-3 text-muted small bg-light rounded-3">
                        <i class="fa-solid fa-circle-check text-emerald me-2"></i>No upcoming classes scheduled after the current live session.
                    </div>
                @endif
            @endforelse
        </div>
    </div>

</div>

{{-- ── SCHEDULE CLASS MODAL ── --}}
<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-video me-2 text-indigo"></i>Schedule New Virtual Class</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('teacher.online-classes.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE / COURSE *</label>
                            <select class="form-select lh-input" name="module_id" required>
                                <option value="" disabled selected>Select Course Module...</option>
                                @forelse($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @empty
                                    <option disabled>No modules assigned yet</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS TOPIC / TITLE *</label>
                            <input type="text" name="title" class="lh-input" required placeholder="e.g. Introduction to SPSS Analytics">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS DATE *</label>
                            <input type="date" name="class_date" class="lh-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">START TIME *</label>
                            <input type="time" name="start_time" class="lh-input" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">MEETING ROOM LINK (ZOOM / GOOGLE MEET) *</label>
                            <input type="url" name="meeting_link" class="lh-input" required placeholder="https://meet.google.com/xyz-abc">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">DURATION (MINUTES)</label>
                            <input type="number" name="duration" class="lh-input" placeholder="60" min="1">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">MEETING ID (OPTIONAL)</label>
                            <input type="text" name="meeting_id" class="lh-input" placeholder="123 456 789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">PASSCODE (OPTIONAL)</label>
                            <input type="text" name="meeting_password" class="lh-input" placeholder="Optional passcode">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">CLASS DESCRIPTION / AGENDA</label>
                            <textarea name="description" class="lh-input" rows="2" placeholder="Brief outline of topics to cover..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa-solid fa-plus me-1.5"></i>Create Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ── Page Layout ── */
.lh-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.lh-header-badge {
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
.lh-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.lh-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

.lh-btn-primary {
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 0.55rem 1.2rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    transition: all 0.2s;
}
.lh-btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,0.3); }

/* ── Stat Cards ── */
.lh-stat-card {
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
.lh-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.lh-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.lh-stat-red-active { border-color: #FCA5A5; }
.lh-stat-red-active .lh-stat-icon { background: #FEF2F2; color: #EF4444; }
.lh-stat-gray .lh-stat-icon       { background: #F1F5F9; color: #94A3B8; }
.lh-stat-indigo .lh-stat-icon     { background: #EEF2FF; color: #4F46E5; }
.lh-stat-emerald .lh-stat-icon    { background: #ECFDF5; color: #10B981; }

.lh-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.lh-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* Pulse Animation */
.lh-pulse-anim { animation: lh-pulse-dot 1.2s infinite; }
@keyframes lh-pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* Live Banner Card */
.lh-live-banner-card {
    background: #fff;
    border: 1.5px solid #FECACA;
    border-radius: 16px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.08);
}
.lh-live-banner-left { display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0; }
.lh-live-pulse-container { position: relative; width: 46px; height: 46px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.lh-live-ring {
    position: absolute; inset: 0; border-radius: 50%;
    background: rgba(239, 68, 68, 0.3); animation: lh-ring-expand 1.6s ease-out infinite;
}
@keyframes lh-ring-expand { 0%{transform:scale(0.8);opacity:1} 100%{transform:scale(1.5);opacity:0} }

.lh-live-icon-box {
    position: relative; width: 38px; height: 38px; border-radius: 50%;
    background: #EF4444; color: #fff; display: flex; align-items: center;
    justify-content: center; font-size: 1rem; z-index: 2;
}
.lh-blink-dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #fff; margin-right: 4px; animation: lh-blink 1s infinite; }
@keyframes lh-blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

/* Time Box */
.lh-time-box {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1.5px solid #C7D2FE;
}
.lh-time-box.today { background: #FFFBEB; border-color: #FDE68A; color: #D97706; }

.lh-time-val  { font-size: 1.05rem; font-weight: 800; line-height: 1; }
.lh-time-ampm { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; margin-top: 0.1rem; }

.lh-oc-title { font-size: 0.92rem; font-weight: 800; color: #0F172A; margin: 0; }

/* Card Wrapper */
.lh-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}
.lh-card-head {
    padding: 1.1rem 1.25rem 0.85rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.lh-card-title { font-size: 0.9rem; font-weight: 800; color: #0F172A; }

.lh-input {
    width: 100%;
    padding: 0.5rem 0.9rem;
    font-size: 0.84rem;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.lh-input:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.bg-indigo-light { background: #EEF2FF; }
.text-indigo     { color: #4F46E5; }
.bg-amber-light  { background: #FFFBEB; }
.text-amber      { color: #D97706; }
.text-emerald    { color: #047857; }
</style>
@endsection

