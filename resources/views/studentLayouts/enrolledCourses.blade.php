@extends('applayouts.app')
@section('contents')
<div class="ec-page">

    {{-- ── Header ── --}}
    <div class="ec-header">
        <div>
            <h5 class="ec-title">My Enrolled Modules</h5>
            <p class="ec-subtitle">All courses you are currently enrolled in</p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    @php
        $totalModules    = $courseEnrolled->count();
        $activeCount     = $courseEnrolled->where('status', 'active')->count();
        $completedCount  = $courseEnrolled->where('status', 'completed')->count();
        $liveNowCount    = $liveModuleIds->count();
    @endphp
    <div class="ec-stats">
        <div class="ec-stat">
            <div class="ec-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-book"></i></div>
            <div><p class="ec-stat-num">{{ $totalModules }}</p><p class="ec-stat-lbl">Total Modules</p></div>
        </div>
        <div class="ec-stat">
            <div class="ec-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="ec-stat-num">{{ $completedCount }}</p><p class="ec-stat-lbl">Completed</p></div>
        </div>
        <div class="ec-stat">
            <div class="ec-stat-icon" style="background:#FEE2E2;color:#DC2626;"><i class="fa-solid fa-circle-dot"></i></div>
            <div><p class="ec-stat-num">{{ $liveNowCount }}</p><p class="ec-stat-lbl">Live Right Now</p></div>
        </div>
        <div class="ec-stat">
            <div class="ec-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-layer-group"></i></div>
            <div><p class="ec-stat-num">{{ $courseEnrolled->sum(fn($e) => $e->modules?->lessons?->count() ?? 0) }}</p><p class="ec-stat-lbl">Total Lessons</p></div>
        </div>
    </div>

    {{-- ── Cards Grid ── --}}
    @if($courseEnrolled->isEmpty())
    <div class="ec-empty">
        <i class="fa-solid fa-book-open-reader"></i>
        <p>You are not enrolled in any modules yet.</p>
        <a href="{{ route('courses.index') }}" class="ec-btn-primary" style="margin-top:.75rem;">Browse Courses</a>
    </div>
    @else
    <div class="ec-grid">
        @foreach($courseEnrolled as $enrollment)
        @php
            $module  = $enrollment->modules;
            if (!$module) continue;
            $isLive      = isset($liveModuleIds[$module->id]);
            $lessonCount = $module->lessons?->count() ?? 0;
            $teachers    = $module->teacher;

            $statusColor = match($enrollment->status) {
                'completed' => ['bg'=>'#D1FAE5','txt'=>'#065F46'],
                'active'    => ['bg'=>'#EEF2FF','txt'=>'#4338CA'],
                default     => ['bg'=>'#F1F5F9','txt'=>'#475569'],
            };
        @endphp
        <div class="ec-card {{ $isLive ? 'ec-card--live' : '' }}">

            {{-- Live indicator strip --}}
            @if($isLive)
            <div class="ec-live-strip">
                <span class="ec-live-dot"></span> LIVE NOW
            </div>
            @endif

            <div class="ec-card-body">
                {{-- Top row --}}
                <div class="ec-card-top">
                    <div class="ec-module-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span class="ec-status-pill"
                          style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['txt'] }};">
                        {{ ucfirst($enrollment->status ?? 'enrolled') }}
                    </span>
                </div>

                {{-- Title & category --}}
                <p class="ec-module-title">{{ $module->title }}</p>
                @if($module->category)
                    <span class="ec-category">{{ $module->category }}</span>
                @endif

                {{-- Description --}}
                @if($module->short_description)
                    <p class="ec-desc">{{ Str::limit($module->short_description, 90) }}</p>
                @elseif($module->details)
                    <p class="ec-desc">{{ Str::limit(strip_tags($module->details), 90) }}</p>
                @endif

                {{-- Meta chips --}}
                <div class="ec-meta">
                    <span class="ec-chip"><i class="fa-solid fa-list-check me-1"></i>{{ $lessonCount }} lesson{{ $lessonCount != 1 ? 's' : '' }}</span>
                    @if($module->duration)
                        <span class="ec-chip"><i class="fa-solid fa-clock me-1"></i>{{ $module->duration }} hrs</span>
                    @endif
                    @if($module->price)
                        <span class="ec-chip"><i class="fa-solid fa-tag me-1"></i>PKR {{ number_format($module->price) }}</span>
                    @endif
                </div>

                {{-- Teachers --}}
                @if($teachers->isNotEmpty())
                <div class="ec-teachers">
                    @foreach($teachers as $t)
                    <div class="ec-teacher-row">
                        <div class="ec-teacher-avatar">{{ strtoupper(substr($t->name, 0, 1)) }}</div>
                        <div class="ec-teacher-info">
                            <p class="ec-teacher-name">{{ $t->name }}</p>
                            <p class="ec-teacher-role">{{ $t->designation ?? 'Instructor' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div class="ec-actions">
                    <a href="{{ route('learning.materials.view') }}" class="ec-btn-outline">
                        <i class="fa-solid fa-book-open me-1"></i>Materials
                    </a>
                    @if($isLive)
                        <a href="{{ route('jionClass.view') }}" class="ec-btn-live">
                            <i class="fa-solid fa-video me-1"></i>Join Live
                        </a>
                    @else
                        <a href="{{ route('jionClass.view') }}" class="ec-btn-primary">
                            <i class="fa-solid fa-calendar me-1"></i>Classes
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

<style>
.ec-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

.ec-header { margin-bottom:1.25rem; }
.ec-title    { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.ec-subtitle { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.ec-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.ec-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.ec-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.ec-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.ec-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Grid */
.ec-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }

/* Card */
.ec-card {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:16px;
    box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden;
    display:flex; flex-direction:column; transition:box-shadow .2s, transform .2s;
}
.ec-card:hover { box-shadow:0 6px 20px rgba(79,70,229,.1); transform:translateY(-2px); }
.ec-card--live { border-color:#FECACA; box-shadow:0 0 0 2px rgba(220,38,38,.15); }

/* Live strip */
.ec-live-strip {
    display:flex; align-items:center; gap:.5rem;
    background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff;
    padding:.35rem 1rem; font-size:.7rem; font-weight:800; letter-spacing:.8px;
}
.ec-live-dot {
    width:7px; height:7px; border-radius:50%; background:#fff;
    animation:ec-pulse 1.4s ease-in-out infinite;
    box-shadow:0 0 0 2px rgba(255,255,255,.4);
}
@keyframes ec-pulse { 0%,100%{box-shadow:0 0 0 2px rgba(255,255,255,.4)} 50%{box-shadow:0 0 0 5px rgba(255,255,255,.1)} }

.ec-card-body { padding:1.15rem; display:flex; flex-direction:column; gap:.6rem; flex:1; }

.ec-card-top { display:flex; align-items:flex-start; justify-content:space-between; }
.ec-module-icon {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.ec-status-pill { padding:.2rem .65rem; border-radius:50px; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }

.ec-module-title { font-size:.98rem; font-weight:800; color:#1E293B; margin:0; line-height:1.3; }
.ec-category { display:inline-block; background:#F1F5F9; color:#64748B; padding:.15rem .55rem; border-radius:50px; font-size:.68rem; font-weight:600; }
.ec-desc { font-size:.76rem; color:#94A3B8; margin:0; line-height:1.5; }

/* Meta chips */
.ec-meta { display:flex; flex-wrap:wrap; gap:.4rem; }
.ec-chip { background:#F8FAFF; border:1px solid #E2E8F0; color:#64748B; padding:.2rem .55rem; border-radius:6px; font-size:.7rem; font-weight:600; display:inline-flex; align-items:center; }

/* Teachers */
.ec-teachers { display:flex; flex-direction:column; gap:.45rem; border-top:1px solid #F1F5F9; padding-top:.6rem; margin-top:.1rem; }
.ec-teacher-row { display:flex; align-items:center; gap:.6rem; }
.ec-teacher-avatar {
    width:28px; height:28px; border-radius:8px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.72rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.ec-teacher-name { font-size:.78rem; font-weight:700; color:#1E293B; margin:0; }
.ec-teacher-role { font-size:.68rem; color:#94A3B8; margin:0; }

/* Actions */
.ec-actions { display:flex; gap:.5rem; margin-top:auto; padding-top:.4rem; }
.ec-btn-primary, .ec-btn-outline, .ec-btn-live {
    flex:1; display:inline-flex; align-items:center; justify-content:center;
    padding:.42rem .7rem; border-radius:9px; font-size:.775rem; font-weight:600;
    cursor:pointer; text-decoration:none; transition:all .15s; border:none;
}
.ec-btn-primary { background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff; box-shadow:0 3px 10px rgba(79,70,229,.2); }
.ec-btn-primary:hover { transform:translateY(-1px); color:#fff; }
.ec-btn-outline { background:#F1F5F9; color:#475569; }
.ec-btn-outline:hover { background:#4F46E5; color:#fff; }
.ec-btn-live { background:linear-gradient(135deg,#DC2626,#EF4444); color:#fff; box-shadow:0 3px 10px rgba(220,38,38,.2); }
.ec-btn-live:hover { transform:translateY(-1px); color:#fff; }

/* Empty */
.ec-empty { text-align:center; padding:5rem 1rem; color:#CBD5E1; }
.ec-empty i { font-size:3rem; display:block; margin-bottom:.75rem; }
.ec-empty p { margin:0; font-size:.9rem; }

@media(max-width:1100px) { .ec-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:991.98px) { .ec-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:640px)    { .ec-grid { grid-template-columns:1fr; } .ec-stats { grid-template-columns:repeat(2,1fr); } }
</style>
@endsection
