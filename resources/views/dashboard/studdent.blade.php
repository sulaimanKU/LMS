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

    {{-- ── Header ── --}}
    <div class="sd-header">
        <div class="sd-welcome">
            @if($user->profile_image)
                <img src="{{ asset('storage/'.$user->profile_image) }}" class="sd-avatar" style="object-fit: cover;">
            @else
                <div class="sd-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div>
                <h5 class="sd-title">Welcome back, {{ explode(' ', $user->name)[0] }}!</h5>
                <p class="sd-subtitle">{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
        <div class="sd-header-actions">
            <a href="{{ route('assigments.upload.view') }}" class="sd-btn-outline">
                <i class="fa-solid fa-file-lines me-2"></i>My Assignments
            </a>
            <a href="{{ route('jionClass.view') }}" class="sd-btn-primary">
                <i class="fa-solid fa-video me-2"></i>Join Class
            </a>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="sd-stats">
        <div class="sd-stat">
            <div class="sd-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-book"></i></div>
            <div>
                <p class="sd-stat-num">{{ $courseCount }}</p>
                <p class="sd-stat-lbl">Enrolled Modules</p>
            </div>
        </div>
        <div class="sd-stat">
            <div class="sd-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-user-check"></i></div>
            <div>
                <p class="sd-stat-num">{{ $attendanceCount }}</p>
                <p class="sd-stat-lbl">Classes Attended</p>
            </div>
        </div>
        <div class="sd-stat">
            <div class="sd-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <p class="sd-stat-num">{{ $pendingCount }}</p>
                <p class="sd-stat-lbl">Pending Assignments</p>
            </div>
        </div>
        <div class="sd-stat">
            <div class="sd-stat-icon" style="background:{{ $overdueCount > 0 ? '#FEE2E2' : '#D1FAE5' }};color:{{ $overdueCount > 0 ? '#DC2626' : '#065F46' }};"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <p class="sd-stat-num">{{ $overdueCount }}</p>
                <p class="sd-stat-lbl">Overdue</p>
            </div>
        </div>
    </div>

    {{-- ── Live Banner ── --}}
    @if($liveClass)
    <div class="sd-live-banner">
        <div class="sd-live-dot"></div>
        <div class="sd-live-text">
            <strong>{{ $liveClass->module?->title }}</strong> class is live now —
            {{ $liveClass->title }}
            <span class="sd-live-teacher">by {{ $liveClass->teacher?->name ?? 'your instructor' }}</span>
        </div>
        <a href="{{ route('student.joinClassAction', $liveClass->id) }}" class="sd-live-btn">
            <i class="fa-solid fa-video me-1"></i>Join Now
        </a>
    </div>
    @endif

    {{-- ── Body ── --}}
    <div class="sd-body">

        {{-- Left: Enrolled Courses --}}
        <div class="sd-card sd-card-wide">
            <div class="sd-card-head">
                <span><i class="fa-solid fa-book-open me-2 text-primary"></i>My Enrolled Modules</span>
                <a href="{{ route('enrolledCourses.view') }}" class="sd-link">View all</a>
            </div>

            @forelse($enrollments as $enrollment)
            <div class="sd-course-row">
                <div class="sd-course-icon">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="sd-course-info">
                    <p class="sd-course-title">{{ $enrollment->modules?->title ?? 'Untitled Module' }}</p>
                    <div class="sd-course-teachers">
                        @forelse($enrollment->modules?->teacher ?? [] as $t)
                            <span class="sd-teacher-pill"><i class="fa-solid fa-user me-1"></i>{{ $t->name }}</span>
                        @empty
                            <span class="sd-teacher-pill" style="color:#94A3B8;">No instructor assigned</span>
                        @endforelse
                    </div>
                </div>
                <div class="sd-course-right">
                    <span class="sd-enroll-badge">
                        {{ ucfirst($enrollment->status ?? 'enrolled') }}
                    </span>
                    <a href="{{ route('learning.materials.view') }}" class="sd-btn-sm">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="sd-card-empty">
                <i class="fa-solid fa-book-open-reader"></i>
                <p>You are not enrolled in any modules yet.</p>
            </div>
            @endforelse
        </div>

        {{-- Right column --}}
        <div class="sd-right-col">

            {{-- Upcoming Classes --}}
            <div class="sd-card">
                <div class="sd-card-head">
                    <span><i class="fa-solid fa-calendar-days me-2 text-primary"></i>Upcoming Classes</span>
                    <a href="{{ route('jionClass.view') }}" class="sd-link">See all</a>
                </div>

                @forelse($upcomingClasses as $cls)
                <div class="sd-cls-row">
                    <div class="sd-cls-info">
                        <p class="sd-cls-title">{{ $cls->title }}</p>
                        <p class="sd-cls-meta">
                            <span><i class="fa-solid fa-book me-1"></i>{{ $cls->module?->title ?? '—' }}</span>
                            <span><i class="fa-solid fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($cls->class_date)->format('d M') }}</span>
                            <span><i class="fa-solid fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cls->start_time)->format('h:i A') }}</span>
                        </p>
                    </div>
                </div>
                @empty
                <div class="sd-card-empty" style="padding:1.5rem 1.25rem;">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>No upcoming classes.</p>
                </div>
                @endforelse
            </div>

            {{-- Recent Submissions --}}
            <div class="sd-card" style="margin-top:1rem;">
                <div class="sd-card-head">
                    <span><i class="fa-solid fa-cloud-arrow-up me-2 text-primary"></i>Recent Submissions</span>
                    <a href="{{ route('assigments.upload.view') }}" class="sd-link">See all</a>
                </div>

                @forelse($recentSubmissions as $sub)
                @php $isGraded = $sub->status === 'graded'; @endphp
                <div class="sd-sub-row">
                    <div class="sd-sub-info">
                        <p class="sd-sub-title">{{ $sub->assignment?->title ?? '—' }}</p>
                        <p class="sd-sub-date"><i class="fa-solid fa-clock me-1"></i>{{ $sub->submitted_at ? $sub->submitted_at->format('d M, h:i A') : '—' }}</p>
                    </div>
                    @if($isGraded)
                        <span class="sd-grade-pill">{{ $sub->grade }}/{{ $sub->assignment?->total_points ?? 100 }}</span>
                    @else
                        <span class="sd-pending-pill">Pending</span>
                    @endif
                </div>
                @empty
                <div class="sd-card-empty" style="padding:1.5rem 1.25rem;">
                    <i class="fa-solid fa-inbox"></i>
                    <p>No submissions yet.</p>
                </div>
                @endforelse
            </div>

            {{-- Submit a Review --}}
            <div class="sd-card" style="margin-top:1rem; border:1.5px solid #E0E7FF; background:linear-gradient(to bottom, #FFFFFF, #F5F7FF);">
                <div class="sd-card-head">
                    <span><i class="fa-solid fa-star me-2 text-warning"></i>Share Your Experience</span>
                </div>
                <div style="padding:1.25rem;">
                    <p style="font-size:.78rem; color:#64748B; margin-bottom:1rem;">
                        How is your learning journey going? Share a testimonial to be featured on our home page!
                    </p>
                    <form action="{{ route('student.review.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Your Rating</label>
                            <div class="sd-rating-stars">
                                <input type="radio" name="rating" value="5" id="r5" checked><label for="r5"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="4" id="r4"><label for="r4"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="3" id="r3"><label for="r3"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="2" id="r2"><label for="r2"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" name="rating" value="1" id="r1"><label for="r1"><i class="fa-solid fa-star"></i></label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="content" class="form-control form-control-sm" rows="3" placeholder="Write your feedback here..." required style="border-radius:8px; border:1.5px solid #E2E8F0;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold" style="border-radius:8px; background:#4F46E5; border:none; padding:.5rem;">
                            Submit Testimonial
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
.sd-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

/* Header */
.sd-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.sd-welcome { display:flex; align-items:center; gap:.9rem; }
.sd-avatar {
    width:48px; height:48px; border-radius:14px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:1.2rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.sd-title    { font-size:1.15rem; font-weight:800; color:#1E293B; margin:0; }
.sd-subtitle { font-size:.78rem; color:#94A3B8; margin:.1rem 0 0; }

.sd-header-actions { display:flex; gap:.6rem; flex-wrap:wrap; }
.sd-btn-primary {
    display:inline-flex; align-items:center;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    border:none; border-radius:10px; padding:.5rem 1rem;
    font-size:.82rem; font-weight:600; text-decoration:none;
    box-shadow:0 4px 12px rgba(79,70,229,.25); transition:all .2s;
}
.sd-btn-primary:hover { transform:translateY(-1px); color:#fff; }
.sd-btn-outline {
    display:inline-flex; align-items:center;
    background:#fff; color:#475569;
    border:1.5px solid #E2E8F0; border-radius:10px; padding:.5rem 1rem;
    font-size:.82rem; font-weight:600; text-decoration:none; transition:all .15s;
}
.sd-btn-outline:hover { border-color:#7C3AED; color:#4F46E5; }

/* Stats */
.sd-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.sd-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.sd-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.sd-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.sd-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Live Banner */
.sd-live-banner {
    display:flex; align-items:center; gap:.85rem; flex-wrap:wrap;
    background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff;
    border-radius:12px; padding:.75rem 1.25rem; margin-bottom:1.25rem;
    box-shadow:0 4px 14px rgba(220,38,38,.25);
}
.sd-live-dot {
    width:10px; height:10px; border-radius:50%; background:#fff; flex-shrink:0;
    animation:sd-pulse 1.4s ease-in-out infinite;
    box-shadow:0 0 0 3px rgba(255,255,255,.35);
}
@keyframes sd-pulse { 0%,100%{box-shadow:0 0 0 3px rgba(255,255,255,.35)} 50%{box-shadow:0 0 0 7px rgba(255,255,255,.1)} }
.sd-live-text { flex:1; font-size:.82rem; }
.sd-live-teacher { opacity:.75; font-size:.78rem; }
.sd-live-btn {
    background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4);
    color:#fff; border-radius:8px; padding:.35rem .8rem;
    font-size:.78rem; font-weight:700; text-decoration:none; white-space:nowrap; transition:all .15s;
}
.sd-live-btn:hover { background:rgba(255,255,255,.35); color:#fff; }

/* Body layout */
.sd-body { display:grid; grid-template-columns:1fr 340px; gap:1rem; }
.sd-card-wide { grid-column:1; }
.sd-right-col { grid-column:2; display:flex; flex-direction:column; }

/* Cards */
.sd-card { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; }
.sd-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:.85rem 1.25rem; border-bottom:1px solid #F1F5F9;
    font-size:.84rem; font-weight:700; color:#1E293B;
}
.sd-link { font-size:.75rem; color:#4F46E5; text-decoration:none; font-weight:600; }
.sd-link:hover { text-decoration:underline; }

/* Course rows */
.sd-course-row { display:flex; align-items:center; gap:.9rem; padding:.95rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.sd-course-row:last-child { border-bottom:none; }
.sd-course-icon {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.sd-course-info { flex:1; min-width:0; }
.sd-course-title { font-size:.88rem; font-weight:700; color:#1E293B; margin:0; }
.sd-course-teachers { display:flex; flex-wrap:wrap; gap:.3rem; margin-top:.25rem; }
.sd-teacher-pill { background:#F1F5F9; color:#475569; padding:.12rem .5rem; border-radius:50px; font-size:.68rem; font-weight:600; }
.sd-course-right { display:flex; align-items:center; gap:.5rem; flex-shrink:0; }
.sd-enroll-badge { background:#D1FAE5; color:#065F46; padding:.15rem .55rem; border-radius:50px; font-size:.68rem; font-weight:700; text-transform:uppercase; }
.sd-btn-sm {
    width:28px; height:28px; border-radius:7px; background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.75rem;
    text-decoration:none; transition:all .15s;
}
.sd-btn-sm:hover { background:#4F46E5; color:#fff; }

/* Upcoming classes */
.sd-cls-row { padding:.85rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.sd-cls-row:last-child { border-bottom:none; }
.sd-cls-title { font-size:.84rem; font-weight:700; color:#1E293B; margin:0; }
.sd-cls-meta  { display:flex; flex-wrap:wrap; gap:.5rem; font-size:.7rem; color:#64748B; margin:.2rem 0 0; }
.sd-cls-meta span { display:inline-flex; align-items:center; }

/* Submissions */
.sd-sub-row { display:flex; align-items:center; justify-content:space-between; gap:.75rem; padding:.85rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.sd-sub-row:last-child { border-bottom:none; }
.sd-sub-title { font-size:.84rem; font-weight:700; color:#1E293B; margin:0; }
.sd-sub-date  { font-size:.7rem; color:#94A3B8; margin:.15rem 0 0; }
.sd-grade-pill   { background:#D1FAE5; color:#065F46; padding:.18rem .6rem; border-radius:50px; font-size:.72rem; font-weight:800; white-space:nowrap; }
.sd-pending-pill { background:#FEF3C7; color:#92400E; padding:.18rem .6rem; border-radius:50px; font-size:.7rem; font-weight:700; white-space:nowrap; }

/* Empty state */
.sd-card-empty { text-align:center; padding:2.5rem 1rem; color:#CBD5E1; }
.sd-card-empty i { font-size:1.75rem; display:block; margin-bottom:.4rem; }
.sd-card-empty p { margin:0; font-size:.8rem; }

/* Star Rating */
.sd-rating-stars { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: .4rem; }
.sd-rating-stars input { display: none; }
.sd-rating-stars label { font-size: 1.1rem; color: #94A3B8; cursor: pointer; transition: color .15s; margin: 0; }
.sd-rating-stars label:hover,
.sd-rating-stars label:hover ~ label,
.sd-rating-stars input:checked ~ label { color: #F59E0B; }

@media(max-width:1100px) { .sd-body { grid-template-columns:1fr; } .sd-card-wide,.sd-right-col { grid-column:1; } }
@media(max-width:991.98px) { .sd-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) { .sd-stats { grid-template-columns:repeat(2,1fr); } }
</style>
@endsection
