@extends('applayouts.app')
@section('contents')
<div class="sd-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Hero Header ── --}}
    <div class="sd-hero-card mb-4">
        <div class="sd-hero-content">
            <div class="sd-hero-user">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}" class="sd-hero-avatar" alt="{{ $user->name }}">
                @else
                    <div class="sd-hero-avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
                <div>
                    @php
                        $hour = (int) now()->format('H');
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    <div class="sd-hero-badge"><i class="fa-solid fa-graduation-cap me-1.5"></i> Student Workspace</div>
                    <h3 class="sd-hero-title">{{ $greeting }}, {{ explode(' ', $user->name)[0] }}! 👋</h3>
                    <p class="sd-hero-subtitle">
                        <i class="fa-regular fa-calendar me-1"></i> {{ now()->format('l, F j, Y') }} 
                        <span class="mx-2">•</span> 
                        Ready to continue your learning journey?
                    </p>
                </div>
            </div>
            <div class="sd-hero-actions">
                <a href="{{ route('assigments.upload.view') }}" class="sd-btn-hero-secondary">
                    <i class="fa-solid fa-file-lines me-2"></i>Assignments
                    @if($pendingCount > 0)
                        <span class="sd-hero-pill-badge bg-amber">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('jionClass.view') }}" class="sd-btn-hero-primary">
                    <i class="fa-solid fa-video me-2"></i>Join Live Class
                </a>
            </div>
        </div>
    </div>

    {{-- ── KPI Analytics Row ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="sd-kpi-card sd-kpi-indigo">
                <div class="sd-kpi-icon"><i class="fa-solid fa-book-open"></i></div>
                <div>
                    <h3 class="sd-kpi-value">{{ $courseCount }}</h3>
                    <span class="sd-kpi-label">Enrolled Modules</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="sd-kpi-card sd-kpi-emerald">
                <div class="sd-kpi-icon"><i class="fa-solid fa-user-check"></i></div>
                <div>
                    <h3 class="sd-kpi-value">{{ $attendanceCount }}</h3>
                    <span class="sd-kpi-label">Classes Attended</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="sd-kpi-card sd-kpi-amber">
                <div class="sd-kpi-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <h3 class="sd-kpi-value">{{ $pendingCount }}</h3>
                    <span class="sd-kpi-label">
                        Pending Tasks
                        @if(($overdueCount ?? 0) > 0)
                            <span class="badge bg-danger text-white ms-1" style="font-size:0.65rem;">{{ $overdueCount }} overdue</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('student.certificates.view') }}" class="sd-kpi-card sd-kpi-purple text-decoration-none">
                <div class="sd-kpi-icon"><i class="fa-solid fa-award"></i></div>
                <div>
                    <h3 class="sd-kpi-value">{{ $certificatesCount ?? 0 }}</h3>
                    <span class="sd-kpi-label">My Certificates</span>
                </div>
            </a>
        </div>
    </div>

    {{-- ── Live Class Banner ── --}}
    @if($liveClass)
    <div class="sd-live-banner mb-4">
        <div class="sd-live-pulse-wrap">
            <span class="sd-live-pulse"></span>
            <span class="sd-live-dot"></span>
        </div>
        <div class="sd-live-text">
            <span class="badge bg-white text-danger fw-bold me-2 px-2 py-1" style="font-size:0.7rem;">LIVE NOW</span>
            <strong>{{ $liveClass->module?->title }}</strong> — {{ $liveClass->title }}
            <span class="sd-live-teacher ms-2 opacity-75"><i class="fa-solid fa-chalkboard-user me-1"></i>{{ $liveClass->teacher?->name ?? 'Instructor' }}</span>
        </div>
        <a href="{{ route('student.joinClassAction', $liveClass->id) }}" class="sd-live-btn">
            <i class="fa-solid fa-video me-1.5"></i>Join Room Now
        </a>
    </div>
    @endif

    {{-- ── Main Body Grid ── --}}
    <div class="sd-body">

        {{-- Left Column: Learning Content & Enrolled Modules --}}
        <div class="sd-left-col">

            {{-- Latest Lessons --}}
            <div class="sd-section-card mb-4">
                <div class="sd-section-header">
                    <div class="sd-section-title">
                        <i class="fa-solid fa-circle-play text-danger me-2"></i>Latest Learning Lessons
                    </div>
                    <a href="{{ route('learning.materials.view') }}" class="sd-link-btn">
                        View All Lessons <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                
                <div class="sd-lessons-list">
                    @forelse($latestLessons as $lesson)
                    <div class="sd-lesson-item">
                        <div class="sd-lesson-icon">
                            <i class="fa-solid fa-file-video"></i>
                        </div>
                        <div class="sd-lesson-info">
                            <p class="sd-lesson-name">{{ $lesson->title }}</p>
                            <div class="sd-lesson-meta">
                                <span class="sd-pill-tag"><i class="fa-solid fa-book me-1"></i>{{ $lesson->module?->title }}</span>
                                <span class="ms-2 text-muted" style="font-size:0.72rem;"><i class="fa-solid fa-clock me-1"></i>{{ $lesson->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <a href="{{ route('learning.materials.view') }}?module={{ $lesson->module_id }}" class="sd-lesson-play" title="Watch Lesson">
                            <i class="fa-solid fa-play"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fa-solid fa-film text-muted opacity-50 mb-2" style="font-size:2rem;"></i>
                        <p class="text-muted small mb-0">No new video lessons uploaded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Study Resources --}}
            <div class="sd-section-card mb-4">
                <div class="sd-section-header">
                    <div class="sd-section-title">
                        <i class="fa-solid fa-folder-open text-primary me-2"></i>Recent Study Materials & Downloads
                    </div>
                </div>
                
                <div class="sd-lessons-list">
                    @forelse($latestResources as $res)
                    @php
                        $ext = strtolower(pathinfo($res->file_path, PATHINFO_EXTENSION));
                        $isPdf = $ext === 'pdf';
                        $iconClass = $isPdf ? 'fa-file-pdf text-danger' : ($ext === 'docx' || $ext === 'doc' ? 'fa-file-word text-primary' : 'fa-file-lines text-secondary');
                        $bgClass = $isPdf ? '#FEF2F2' : '#EFF6FF';
                    @endphp
                    <div class="sd-lesson-item">
                        <div class="sd-lesson-icon" style="background: {{ $bgClass }};">
                            <i class="fa-solid {{ $iconClass }}"></i>
                        </div>
                        <div class="sd-lesson-info">
                            <p class="sd-lesson-name">{{ $res->title }}</p>
                            <div class="sd-lesson-meta">
                                <span class="sd-pill-tag"><i class="fa-solid fa-layer-group me-1"></i>{{ $res->lesson?->title }}</span>
                                <span class="badge bg-light text-dark border ms-2 uppercase" style="font-size:0.65rem;">{{ strtoupper($ext) }}</span>
                            </div>
                        </div>
                        <a href="{{ asset('storage/'.$res->file_path) }}" target="_blank" class="sd-download-btn" title="Download Resource">
                            <i class="fa-solid fa-download me-1"></i> Download
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fa-solid fa-file-arrow-up text-muted opacity-50 mb-2" style="font-size:2rem;"></i>
                        <p class="text-muted small mb-0">No study documents available.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Enrolled Modules --}}
            <div class="sd-section-card">
                <div class="sd-section-header">
                    <div class="sd-section-title">
                        <i class="fa-solid fa-book-bookmark text-indigo me-2"></i>My Enrolled Courses & Modules
                    </div>
                    <a href="{{ route('enrolledCourses.view') }}" class="sd-link-btn">
                        View All Courses <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="sd-courses-list">
                    @forelse($enrollments as $enrollment)
                    <div class="sd-course-card">
                        <div class="sd-course-header">
                            <div class="sd-course-icon-wrap">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="sd-course-name">{{ $enrollment->modules?->title ?? 'Untitled Module' }}</h6>
                                <div class="sd-course-instructor">
                                    @forelse($enrollment->modules?->teacher ?? [] as $t)
                                        <span class="sd-teacher-chip"><i class="fa-solid fa-user-tie me-1"></i>{{ $t->name }}</span>
                                    @empty
                                        <span class="sd-teacher-chip text-muted"><i class="fa-solid fa-user-slash me-1"></i>Unassigned</span>
                                    @endforelse
                                </div>
                            </div>
                            <span class="sd-status-badge {{ $enrollment->status === 'active' ? 'status-active' : 'status-default' }}">
                                <i class="fa-solid fa-circle me-1" style="font-size:0.45rem;"></i>{{ ucfirst($enrollment->status ?? 'Enrolled') }}
                            </span>
                        </div>
                        <div class="sd-course-footer mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted small" style="font-size:0.75rem;"><i class="fa-solid fa-graduation-cap me-1"></i>Active Course</span>
                            <a href="{{ route('learning.materials.view') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="font-size:0.75rem; background:#4F46E5; border:none;">
                                Access Materials <i class="fa-solid fa-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="sd-empty-box">
                        <i class="fa-solid fa-book-open-reader mb-2"></i>
                        <h6>No Enrolled Courses Found</h6>
                        <p>You are not currently enrolled in any academic modules.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Column: Upcoming Classes, Submissions & Feedback --}}
        <div class="sd-right-col">

            {{-- Upcoming Classes --}}
            <div class="sd-widget-card mb-4">
                <div class="sd-widget-header">
                    <span class="sd-widget-title"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Upcoming Live Classes</span>
                    <a href="{{ route('jionClass.view') }}" class="sd-link-sm">View Calendar</a>
                </div>

                <div class="sd-widget-body">
                    @forelse($upcomingClasses as $cls)
                    <div class="sd-class-item">
                        <div class="sd-class-date-box">
                            <span class="sd-class-day">{{ \Carbon\Carbon::parse($cls->class_date)->format('d') }}</span>
                            <span class="sd-class-month">{{ \Carbon\Carbon::parse($cls->class_date)->format('M') }}</span>
                        </div>
                        <div class="sd-class-details">
                            <h6 class="sd-class-title">{{ $cls->title }}</h6>
                            <p class="sd-class-meta">
                                <span><i class="fa-solid fa-book me-1"></i>{{ Str::limit($cls->module?->title ?? '—', 22) }}</span><br>
                                <span class="text-primary fw-bold"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted" style="font-size:0.8rem;">
                        <i class="fa-solid fa-calendar-check mb-1 d-block opacity-50" style="font-size:1.5rem;"></i>
                        No scheduled upcoming classes.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Submissions --}}
            <div class="sd-widget-card mb-4">
                <div class="sd-widget-header">
                    <span class="sd-widget-title"><i class="fa-solid fa-cloud-arrow-up text-indigo me-2"></i>Recent Submissions</span>
                    <a href="{{ route('assigments.upload.view') }}" class="sd-link-sm">All Submissions</a>
                </div>

                <div class="sd-widget-body">
                    @forelse($recentSubmissions as $sub)
                    @php $isGraded = $sub->status === 'graded'; @endphp
                    <div class="sd-sub-item">
                        <div>
                            <p class="sd-sub-name">{{ $sub->assignment?->title ?? 'Assignment' }}</p>
                            <span class="sd-sub-time"><i class="fa-regular fa-clock me-1"></i>{{ $sub->submitted_at ? $sub->submitted_at->format('d M, h:i A') : '—' }}</span>
                        </div>
                        <div>
                            @if($isGraded)
                                <span class="badge bg-emerald-light text-emerald font-monospace px-2 py-1 rounded-pill" style="font-size:0.72rem; font-weight:700;">
                                    {{ $sub->grade }} / {{ $sub->assignment?->total_points ?? 100 }}
                                </span>
                            @else
                                <span class="badge bg-amber-light text-amber px-2 py-1 rounded-pill" style="font-size:0.7rem; font-weight:600;">
                                    Pending Grade
                                </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted" style="font-size:0.8rem;">
                        <i class="fa-solid fa-inbox mb-1 d-block opacity-50" style="font-size:1.5rem;"></i>
                        No assignments submitted yet.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Testimonial / Feedback Form --}}
            <div class="sd-widget-card sd-feedback-widget">
                <div class="sd-widget-header border-0 pb-0">
                    <span class="sd-widget-title"><i class="fa-solid fa-star text-amber me-2"></i>Rate Your Experience</span>
                </div>
                <div class="sd-widget-body pt-2">
                    <p class="text-muted" style="font-size:0.78rem;">
                        Enjoying your courses? Share your thoughts with us to help improve the learning platform!
                    </p>
                    <form action="{{ route('student.review.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="sd-rating-stars">
                                <input type="radio" name="rating" value="5" id="r5" checked><label for="r5"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="4" id="r4"><label for="r4"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="3" id="r3"><label for="r3"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="2" id="r2"><label for="r2"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="1" id="r1"><label for="r1"><i class="fa-solid fa-star"></i></label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="content" class="form-control form-control-sm" rows="3" placeholder="Write brief feedback..." required style="border-radius:10px; border:1.5px solid #E2E8F0; font-size:0.82rem;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" style="border-radius:9px; background:linear-gradient(135deg,#4F46E5,#7C3AED); border:none; padding:0.55rem;">
                            Submit Testimonial <i class="fa-solid fa-paper-plane ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
/* ── Main Layout ── */
.sd-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Hero Card Banner ── */
.sd-hero-card {
    background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
    border-radius: 18px;
    padding: 1.8rem 2rem;
    color: #fff;
    box-shadow: 0 10px 30px rgba(49, 46, 129, 0.2);
    position: relative;
    overflow: hidden;
}
.sd-hero-card::after {
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

.sd-hero-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.2rem;
    position: relative;
    z-index: 2;
}
.sd-hero-user {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.sd-hero-avatar {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.sd-hero-avatar-initial {
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

.sd-hero-badge {
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
.sd-hero-title {
    font-size: 1.45rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    line-height: 1.2;
}
.sd-hero-subtitle {
    font-size: 0.82rem;
    color: #A5B4FC;
    margin: 0.3rem 0 0;
}

.sd-hero-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.sd-btn-hero-primary {
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
.sd-btn-hero-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
    color: #3730A3;
}
.sd-btn-hero-secondary {
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
.sd-btn-hero-secondary:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #fff;
}
.sd-hero-pill-badge {
    margin-left: 0.4rem;
    font-size: 0.7rem;
    padding: 0.15rem 0.45rem;
    border-radius: 50px;
    background: #F59E0B;
    color: #fff;
    font-weight: 800;
}

/* ── KPI Analytics Row ── */
.sd-kpi-card {
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
.sd-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}
.sd-kpi-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.sd-kpi-indigo .sd-kpi-icon { background: #EEF2FF; color: #4F46E5; }
.sd-kpi-emerald .sd-kpi-icon { background: #ECFDF5; color: #10B981; }
.sd-kpi-amber .sd-kpi-icon   { background: #FFFBEB; color: #F59E0B; }
.sd-kpi-purple .sd-kpi-icon  { background: #F3E8FF; color: #9333EA; }

.sd-kpi-value { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.sd-kpi-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* ── Live Room Banner ── */
.sd-live-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #DC2626, #EF4444);
    color: #fff;
    border-radius: 14px;
    padding: 0.9rem 1.35rem;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.25);
}
.sd-live-pulse-wrap {
    position: relative;
    width: 14px;
    height: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sd-live-dot { width: 10px; height: 10px; border-radius: 50%; background: #fff; z-index: 2; }
.sd-live-pulse {
    position: absolute;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.6);
    animation: sd-live-ping 1.4s cubic-bezier(0, 0, 0.2, 1) infinite;
}
@keyframes sd-live-ping {
    75%, 100% { transform: scale(2); opacity: 0; }
}

.sd-live-text { flex: 1; font-size: 0.86rem; }
.sd-live-btn {
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
.sd-live-btn:hover { background: #fff; color: #DC2626; }

/* ── Main Layout Grid ── */
.sd-body { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; }
.sd-left-col { min-width: 0; }
.sd-right-col { min-width: 0; display: flex; flex-direction: column; }

/* ── Section Cards ── */
.sd-section-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    padding: 1.35rem;
}
.sd-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.sd-section-title { font-size: 0.9rem; font-weight: 800; color: #0F172A; }
.sd-link-btn { font-size: 0.78rem; font-weight: 700; color: #4F46E5; text-decoration: none; transition: color 0.15s; }
.sd-link-btn:hover { color: #3730A3; text-decoration: underline; }

/* ── Lessons & Resource List ── */
.sd-lessons-list { display: flex; flex-direction: column; gap: 0.75rem; }
.sd-lesson-item {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 0.8rem 1rem;
    border-radius: 12px;
    background: #F8FAFF;
    border: 1.5px solid #EDF2F7;
    transition: all 0.2s;
}
.sd-lesson-item:hover {
    background: #fff;
    border-color: #4F46E5;
    transform: translateX(3px);
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.08);
}
.sd-lesson-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #FEE2E2;
    color: #DC2626;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.sd-lesson-info { flex: 1; min-width: 0; }
.sd-lesson-name { font-size: 0.86rem; font-weight: 700; color: #1E293B; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sd-lesson-meta { display: flex; align-items: center; font-size: 0.72rem; color: #64748B; margin-top: 0.15rem; }
.sd-pill-tag { background: #EEF2FF; color: #4F46E5; padding: 0.12rem 0.5rem; border-radius: 50px; font-weight: 600; font-size: 0.68rem; }

.sd-lesson-play {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}
.sd-lesson-play:hover { background: #4F46E5; color: #fff; transform: scale(1.08); }

.sd-download-btn {
    display: inline-flex;
    align-items: center;
    background: #ECFDF5;
    color: #059669;
    border: 1px solid #A7F3D0;
    padding: 0.35rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
    flex-shrink: 0;
}
.sd-download-btn:hover { background: #10B981; color: #fff; border-color: #10B981; }

/* ── Enrolled Course Cards ── */
.sd-courses-list { display: flex; flex-direction: column; gap: 0.85rem; }
.sd-course-card {
    background: #F8FAFF;
    border: 1.5px solid #EDF2F7;
    border-radius: 14px;
    padding: 1rem 1.15rem;
    transition: all 0.2s;
}
.sd-course-card:hover {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 4px 16px rgba(79, 70, 229, 0.08);
}
.sd-course-header { display: flex; align-items: flex-start; gap: 0.85rem; }
.sd-course-icon-wrap {
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
.sd-course-name { font-size: 0.9rem; font-weight: 800; color: #0F172A; margin: 0 0 0.25rem; }
.sd-course-instructor { display: flex; flex-wrap: wrap; gap: 0.3rem; }
.sd-teacher-chip { background: #fff; border: 1px solid #E2E8F0; color: #475569; padding: 0.12rem 0.55rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
.sd-status-badge { padding: 0.2rem 0.65rem; border-radius: 50px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
.status-active { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
.status-default { background: #F1F5F9; color: #64748B; }

/* ── Sidebar Widget Cards ── */
.sd-widget-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    overflow: hidden;
}
.sd-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.25rem 0.75rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.sd-widget-title { font-size: 0.86rem; font-weight: 800; color: #0F172A; }
.sd-link-sm { font-size: 0.74rem; font-weight: 700; color: #4F46E5; text-decoration: none; }
.sd-link-sm:hover { text-decoration: underline; }
.sd-widget-body { padding: 1rem 1.25rem; }

/* Upcoming Class Item */
.sd-class-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.7rem 0;
    border-bottom: 1px dashed #F1F5F9;
}
.sd-class-item:last-child { border-bottom: none; }
.sd-class-date-box {
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
.sd-class-day { font-size: 1rem; font-weight: 800; line-height: 1; }
.sd-class-month { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; margin-top: 0.1rem; }
.sd-class-details { flex: 1; min-width: 0; }
.sd-class-title { font-size: 0.83rem; font-weight: 700; color: #1E293B; margin: 0; }
.sd-class-meta { font-size: 0.7rem; color: #64748B; margin: 0.15rem 0 0; line-height: 1.3; }

/* Submissions Item */
.sd-sub-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.65rem 0;
    border-bottom: 1px dashed #F1F5F9;
}
.sd-sub-item:last-child { border-bottom: none; }
.sd-sub-name { font-size: 0.83rem; font-weight: 700; color: #1E293B; margin: 0; }
.sd-sub-time { font-size: 0.7rem; color: #94A3B8; }
.bg-emerald-light { background: #ECFDF5; }
.text-emerald { color: #047857; }
.bg-amber-light { background: #FFFBEB; }
.text-amber { color: #D97706; }

/* Feedback Widget */
.sd-feedback-widget {
    background: linear-gradient(to bottom, #FFFFFF, #F5F7FF);
    border: 1.5px solid #C7D2FE;
}

/* Star Rating Controls */
.sd-rating-stars { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 0.4rem; }
.sd-rating-stars input { display: none; }
.sd-rating-stars label { font-size: 1.2rem; color: #CBD5E1; cursor: pointer; transition: color 0.15s; margin: 0; }
.sd-rating-stars label:hover,
.sd-rating-stars label:hover ~ label,
.sd-rating-stars input:checked ~ label { color: #F59E0B; }

.sd-empty-box { text-align: center; padding: 2.5rem 1rem; color: #94A3B8; }
.sd-empty-box i { font-size: 2rem; color: #CBD5E1; display: block; }
.sd-empty-box h6 { font-size: 0.9rem; font-weight: 700; color: #334155; margin: 0.5rem 0 0.2rem; }
.sd-empty-box p { font-size: 0.78rem; margin: 0; }

@media(max-width:1100px) { .sd-body { grid-template-columns: 1fr; } }
@media(max-width:767.98px) {
    .sd-hero-card { padding: 1.35rem 1.25rem; }
    .sd-hero-content { flex-direction: column; align-items: flex-start; }
    .sd-hero-actions { width: 100%; justify-content: flex-start; }
}
</style>
@endsection
