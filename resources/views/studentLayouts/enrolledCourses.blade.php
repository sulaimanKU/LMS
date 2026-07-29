@extends('applayouts.app')
@section('contents')
<div class="ec-page">

    {{-- ── Header & Toolbar ── --}}
    <div class="ec-header">
        <div>
            <div class="ec-header-badge"><i class="fa-solid fa-graduation-cap me-1.5"></i> Academic Courses</div>
            <h4 class="ec-title"><i class="fa-solid fa-book-open-reader text-indigo me-2"></i>My Enrolled Modules</h4>
            <p class="ec-subtitle">Access your course materials, video lectures, and live classroom sessions</p>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    @php
        $totalModules    = $courseEnrolled->count();
        $activeCount     = $courseEnrolled->where('status', 'active')->count();
        $completedCount  = $courseEnrolled->where('status', 'completed')->count();
        $liveNowCount    = $liveModuleIds->count();
        $totalLessons    = $courseEnrolled->sum(fn($e) => $e->modules?->lessons?->count() ?? 0);
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="ec-stat-card ec-stat-indigo">
                <div class="ec-stat-icon"><i class="fa-solid fa-book"></i></div>
                <div>
                    <h3 class="ec-stat-num">{{ $totalModules }}</h3>
                    <span class="ec-stat-lbl">Enrolled Modules</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ec-stat-card ec-stat-emerald">
                <div class="ec-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <h3 class="ec-stat-num">{{ $completedCount }}</h3>
                    <span class="ec-stat-lbl">Completed</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ec-stat-card ec-stat-red">
                <div class="ec-stat-icon"><i class="fa-solid fa-circle-dot"></i></div>
                <div>
                    <h3 class="ec-stat-num">{{ $liveNowCount }}</h3>
                    <span class="ec-stat-lbl">Live Right Now</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ec-stat-card ec-stat-amber">
                <div class="ec-stat-icon"><i class="fa-solid fa-play-circle"></i></div>
                <div>
                    <h3 class="ec-stat-num">{{ $totalLessons }}</h3>
                    <span class="ec-stat-lbl">Total Lessons</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Search & Filter Control Bar ── --}}
    @if(!$courseEnrolled->isEmpty())
    <div class="ec-toolbar mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-6 col-12">
                <div class="ec-search-wrap">
                    <i class="fa-solid fa-magnifying-glass ec-search-icon"></i>
                    <input type="text" id="ecSearchInput" class="ec-search-input" placeholder="Search course title, category, or instructor name...">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <select id="ecStatusFilter" class="form-select ec-filter-select">
                    <option value="">All Statuses & Modes</option>
                    <option value="live">Live Now Only</option>
                    <option value="active">Active Enrolled</option>
                    <option value="completed">Completed Courses</option>
                </select>
            </div>
            <div class="col-md-2 col-6 text-end">
                <span id="ecResultBadge" class="badge bg-light text-dark border px-2.5 py-2 font-monospace" style="font-size: 0.75rem;">
                    Showing {{ $totalModules }} courses
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Cards Grid ── --}}
    @if($courseEnrolled->isEmpty())
    <div class="ec-empty">
        <i class="fa-solid fa-book-open-reader"></i>
        <h5>No Enrolled Courses Yet</h5>
        <p>Browse our catalog to find workshops and degree modules to join.</p>
        <a href="{{ route('courses.index') }}" class="btn btn-primary rounded-pill px-4 py-2 mt-3 fw-bold" style="background:linear-gradient(135deg,#4F46E5,#7C3AED); border:none;">
            <i class="fa-solid fa-compass me-1.5"></i>Browse Available Courses
        </a>
    </div>
    @else
    <div class="ec-grid" id="ecCoursesGrid">
        @foreach($courseEnrolled as $enrollment)
        @php
            $module  = $enrollment->modules;
            if (!$module) continue;
            $isLive      = isset($liveModuleIds[$module->id]);
            $lessonCount = $module->lessons?->count() ?? 0;
            $teachers    = $module->teacher;
            $teacherNames = $teachers->pluck('name')->implode(' ');

            $statusText = $enrollment->status ?? 'active';
        @endphp
        <div class="ec-card {{ $isLive ? 'ec-card--live' : '' }} ec-course-item"
             data-title="{{ strtolower($module->title) }}"
             data-category="{{ strtolower($module->category ?? '') }}"
             data-teachers="{{ strtolower($teacherNames) }}"
             data-status="{{ $statusText }}"
             data-islive="{{ $isLive ? 'live' : 'offline' }}">

            {{-- Live indicator strip --}}
            @if($isLive)
            <div class="ec-live-strip">
                <span class="ec-live-dot"></span> LIVE CLASS IN PROGRESS
            </div>
            @endif

            <div class="ec-card-body">
                {{-- Top row --}}
                <div class="ec-card-top">
                    <div class="ec-module-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span class="ec-status-pill {{ $statusText === 'completed' ? 'status-completed' : ($statusText === 'active' ? 'status-active' : 'status-default') }}">
                        <i class="fa-solid fa-circle me-1" style="font-size:0.45rem;"></i>{{ ucfirst($statusText) }}
                    </span>
                </div>

                {{-- Title & category --}}
                <h5 class="ec-module-title">{{ $module->title }}</h5>
                @if($module->category)
                    <div>
                        <span class="ec-category"><i class="fa-solid fa-tag me-1"></i>{{ $module->category }}</span>
                    </div>
                @endif

                {{-- Description --}}
                @if($module->short_description)
                    <p class="ec-desc">{{ Str::limit($module->short_description, 95) }}</p>
                @elseif($module->details)
                    <p class="ec-desc">{{ Str::limit(strip_tags($module->details), 95) }}</p>
                @endif

                {{-- Meta chips --}}
                <div class="ec-meta">
                    <span class="ec-chip"><i class="fa-solid fa-play-circle text-primary me-1"></i>{{ $lessonCount }} lesson{{ $lessonCount != 1 ? 's' : '' }}</span>
                    @if($module->duration)
                        <span class="ec-chip"><i class="fa-regular fa-clock text-amber me-1"></i>{{ $module->duration }} hrs</span>
                    @endif
                    @if($module->price)
                        <span class="ec-chip"><i class="fa-solid fa-sack-dollar text-emerald me-1"></i>PKR {{ number_format($module->price) }}</span>
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
                            <p class="ec-teacher-role">{{ $t->designation ?? 'Faculty Instructor' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div class="ec-actions">
                    <a href="{{ route('learning.materials.view') }}?module={{ $module->id }}" class="ec-btn-outline">
                        <i class="fa-solid fa-folder-open me-1.5"></i>Materials
                    </a>
                    @if($isLive)
                        <a href="{{ route('jionClass.view') }}" class="ec-btn-live">
                            <i class="fa-solid fa-video me-1.5"></i>Join Room
                        </a>
                    @else
                        <a href="{{ route('jionClass.view') }}" class="ec-btn-primary">
                            <i class="fa-solid fa-calendar-days me-1.5"></i>Schedule
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div id="ecNoMatch" class="ec-empty d-none">
        <i class="fa-solid fa-filter-circle-xmark text-muted mb-2"></i>
        <h5>No Matching Courses</h5>
        <p>Try clearing your search term or select a different filter status.</p>
    </div>
    @endif

</div>

<style>
/* ── Page Layout ── */
.ec-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.ec-header { margin-bottom: 1.25rem; }
.ec-header-badge {
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
.ec-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.ec-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

/* ── Stat Cards ── */
.ec-stat-card {
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
.ec-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}
.ec-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.ec-stat-indigo .ec-stat-icon  { background: #EEF2FF; color: #4F46E5; }
.ec-stat-emerald .ec-stat-icon { background: #ECFDF5; color: #10B981; }
.ec-stat-red .ec-stat-icon     { background: #FEF2F2; color: #EF4444; }
.ec-stat-amber .ec-stat-icon   { background: #FFFBEB; color: #F59E0B; }

.ec-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.ec-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* ── Toolbar ── */
.ec-toolbar {
    background: #fff;
    border-radius: 14px;
    padding: 0.85rem 1rem;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}

.ec-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.ec-search-icon {
    position: absolute;
    left: 12px;
    color: #94A3B8;
    font-size: 0.85rem;
    pointer-events: none;
}
.ec-search-input {
    width: 100%;
    padding: 0.45rem 1rem 0.45rem 2.3rem;
    font-size: 0.84rem;
    border-radius: 9px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.ec-search-input:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.ec-filter-select {
    font-size: 0.83rem;
    padding: 0.45rem 2rem 0.45rem 0.75rem;
    border-radius: 9px;
    border: 1.5px solid #E2E8F0;
    background-color: #F8FAFF;
    color: #334155;
    font-weight: 500;
    cursor: pointer;
}
.ec-filter-select:focus {
    background-color: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

/* ── Grid ── */
.ec-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }

/* ── Cards ── */
.ec-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}
.ec-card:hover {
    box-shadow: 0 8px 24px rgba(79,70,229,0.12);
    transform: translateY(-3px);
}
.ec-card--live {
    border-color: #FCA5A5;
    box-shadow: 0 0 0 2px rgba(239,68,68,0.2);
}

/* Live Strip */
.ec-live-strip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #DC2626, #EF4444);
    color: #fff;
    padding: 0.4rem 1.1rem;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.8px;
}
.ec-live-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #fff;
    animation: ec-pulse 1.4s ease-in-out infinite;
    box-shadow: 0 0 0 2px rgba(255,255,255,0.4);
}
@keyframes ec-pulse { 0%,100%{box-shadow:0 0 0 2px rgba(255,255,255,0.4)} 50%{box-shadow:0 0 0 6px rgba(255,255,255,0.1)} }

.ec-card-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }

.ec-card-top { display: flex; align-items: flex-start; justify-content: space-between; }
.ec-module-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    flex-shrink: 0;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
}

.ec-status-pill {
    padding: 0.22rem 0.65rem;
    border-radius: 50px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.status-completed { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
.status-active    { background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; }
.status-default   { background: #F1F5F9; color: #64748B; }

.ec-module-title { font-size: 1rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.35; }
.ec-category { display: inline-block; background: #F1F5F9; color: #475569; padding: 0.15rem 0.6rem; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
.ec-desc { font-size: 0.78rem; color: #64748B; margin: 0; line-height: 1.5; }

/* Meta chips */
.ec-meta { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.ec-chip { background: #F8FAFF; border: 1.5px solid #E2E8F0; color: #475569; padding: 0.22rem 0.6rem; border-radius: 8px; font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; }

/* Teachers */
.ec-teachers { display: flex; flex-direction: column; gap: 0.5rem; border-top: 1.5px solid #F1F5F9; padding-top: 0.75rem; margin-top: 0.2rem; }
.ec-teacher-row { display: flex; align-items: center; gap: 0.65rem; }
.ec-teacher-avatar {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    flex-shrink: 0;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ec-teacher-name { font-size: 0.8rem; font-weight: 700; color: #1E293B; margin: 0; }
.ec-teacher-role { font-size: 0.68rem; color: #94A3B8; margin: 0; }

/* Actions */
.ec-actions { display: flex; gap: 0.6rem; margin-top: auto; padding-top: 0.5rem; }
.ec-btn-primary, .ec-btn-outline, .ec-btn-live {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.52rem 0.85rem;
    border-radius: 10px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
}
.ec-btn-primary { background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff; box-shadow: 0 4px 12px rgba(79,70,229,0.22); }
.ec-btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,0.32); }
.ec-btn-outline { background: #F8FAFF; border: 1.5px solid #E2E8F0; color: #475569; }
.ec-btn-outline:hover { background: #4F46E5; border-color: #4F46E5; color: #fff; }
.ec-btn-live { background: linear-gradient(135deg,#DC2626,#EF4444); color: #fff; box-shadow: 0 4px 12px rgba(220,38,38,0.22); }
.ec-btn-live:hover { transform: translateY(-1px); color: #fff; }

/* Empty state */
.ec-empty { text-align: center; padding: 4.5rem 1rem; color: #94A3B8; }
.ec-empty i { font-size: 2.8rem; display: block; margin-bottom: 0.75rem; color: #CBD5E1; }
.ec-empty h5 { font-size: 1.05rem; font-weight: 800; color: #1E293B; margin-bottom: 0.2rem; }
.ec-empty p  { margin: 0; font-size: 0.84rem; }

@media(max-width:1100px) { .ec-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:991.98px) { .ec-stats { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:640px)    { .ec-grid { grid-template-columns: 1fr; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput  = document.getElementById('ecSearchInput');
    const statusFilter = document.getElementById('ecStatusFilter');
    const courseItems  = document.querySelectorAll('.ec-course-item');
    const noMatchBox   = document.getElementById('ecNoMatch');
    const resultBadge  = document.getElementById('ecResultBadge');

    function filterCourses() {
        const query     = (searchInput ? searchInput.value : '').toLowerCase().trim();
        const statusVal = statusFilter ? statusFilter.value : '';

        let visibleCount = 0;
        const totalCount = courseItems.length;

        courseItems.forEach(item => {
            const title    = item.dataset.title || '';
            const category = item.dataset.category || '';
            const teachers = item.dataset.teachers || '';
            const status   = item.dataset.status || '';
            const isLive   = item.dataset.islive || '';

            const matchesQuery = !query || 
                title.includes(query) || 
                category.includes(query) || 
                teachers.includes(query);

            let matchesStatus = true;
            if (statusVal === 'live') {
                matchesStatus = isLive === 'live';
            } else if (statusVal) {
                matchesStatus = status === statusVal;
            }

            if (matchesQuery && matchesStatus) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        if (noMatchBox) {
            noMatchBox.classList.toggle('d-none', visibleCount > 0);
        }

        if (resultBadge) {
            resultBadge.textContent = `Showing ${visibleCount} of ${totalCount} courses`;
        }
    }

    if (searchInput)  searchInput.addEventListener('input', filterCourses);
    if (statusFilter) statusFilter.addEventListener('change', filterCourses);
});
</script>
@endsection
