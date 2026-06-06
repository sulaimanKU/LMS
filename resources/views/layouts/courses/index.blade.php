@extends('applayouts.app')

@section('contents')
<div class="cr-page">

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

    {{-- ── Header ── --}}
    <div class="cr-header">
        <div>
            <h5 class="cr-title">Course Management</h5>
            <p class="cr-subtitle">Create, edit and manage all modules offered by MyLMS</p>
        </div>
        <a href="{{ route('course.create') }}" class="cr-btn-add text-decoration-none">
            <i class="fa-solid fa-plus me-2"></i>Add New Course
        </a>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="cr-stats">
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#EEF2FF;color:#4F46E5;">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalCourses }}</p>
                <p class="cr-stat-label">Total Courses</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#D1FAE5;color:#065F46;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $activeCourses }}</p>
                <p class="cr-stat-label">Active</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#F1F5F9;color:#64748B;">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $inactiveCourses }}</p>
                <p class="cr-stat-label">Inactive</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#FEF3C7;color:#92400E;">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalEnrolled }}</p>
                <p class="cr-stat-label">Enrolled Students</p>
            </div>
        </div>
    </div>

    {{-- ── Filter tabs ── --}}
    <div class="cr-tabs">
        <a class="cr-tab {{ $filter === 'all'      ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all',      'page' => 1]) }}">All ({{ $totalCourses }})</a>
        <a class="cr-tab {{ $filter === 'active'   ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'active',   'page' => 1]) }}">Active ({{ $activeCourses }})</a>
        <a class="cr-tab {{ $filter === 'inactive' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'inactive', 'page' => 1]) }}">Inactive ({{ $inactiveCourses }})</a>
    </div>

    {{-- ── Course grid ── --}}
    <div class="cr-grid">
        @forelse($courses as $course)
        <div class="cr-card {{ $course->status === 'inactive' ? 'cr-card-inactive' : '' }}">

            {{-- Course image --}}
            <div class="cr-card-img">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                @else
                    <div class="cr-card-img-placeholder">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                @endif
                <span class="cr-status-badge cr-status-overlay {{ $course->status === 'active' ? 'cr-badge-active' : 'cr-badge-inactive' }}">
                    {{ ucfirst($course->status) }}
                </span>
                <div class="cr-card-actions cr-actions-overlay">
                    <a href="{{ route('course.edit', $course->id) }}" class="cr-action-edit text-decoration-none" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($course->title) }}\'? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cr-action-delete" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card body --}}
            <div class="cr-card-body">
                <span class="cr-category-pill">{{ $course->category }}</span>

                <h6 class="cr-card-title">{{ $course->title }}</h6>
                @if($course->short_description)
                    <p class="cr-card-desc">{{ Str::limit(strip_tags($course->short_description), 80) }}</p>
                @endif

                <div class="cr-meta">
                    @if($course->duration)
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-clock"></i> {{ $course->duration }}
                    </span>
                    @endif
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-book"></i> {{ $course->lessons_count }} lesson{{ $course->lessons_count != 1 ? 's' : '' }}
                    </span>
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-users"></i> {{ $course->enrollments_count }} enrolled
                    </span>
                </div>

                <div class="cr-card-foot">
                    <span class="cr-price">PKR {{ number_format($course->price, 0) }}</span>
                    @php $teacher = $course->teacher->first(); @endphp
                    @if($teacher)
                        <span class="cr-teacher-tag">
                            <i class="fa-solid fa-chalkboard-user me-1"></i>{{ Str::limit($teacher->name, 18) }}
                        </span>
                    @else
                        <span class="cr-no-teacher">No teacher assigned</span>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="cr-empty">
            <i class="fa-solid fa-book-open-reader"></i>
            <p>No courses found{{ $filter !== 'all' ? ' for filter "' . $filter . '"' : '' }}.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($courses->hasPages())
    <div class="cr-pagination">{{ $courses->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

<style>
/* ── Page ── */
.cr-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.cr-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.cr-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }
.cr-btn-add {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff !important;
    border: none; border-radius: 10px; padding: .55rem 1.1rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cr-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }

/* ── Stats ── */
.cr-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
.cr-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 14px;
    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.cr-stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; }
.cr-stat-num   { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; line-height: 1; }
.cr-stat-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; margin: .15rem 0 0; }

/* ── Tabs ── */
.cr-tabs { display: flex; gap: 4px; background: #EEF2FF; padding: 4px; border-radius: 10px; width: fit-content; margin-bottom: 1.25rem; }
.cr-tab { padding: .32rem .9rem; border-radius: 7px; font-size: .8rem; font-weight: 600; color: #64748B; text-decoration: none; transition: all .15s; }
.cr-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.cr-tab:hover:not(.active) { background: rgba(255,255,255,.6); }

/* ── Grid ── */
.cr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }

/* ── Card ── */
.cr-card {
    background: #fff; border-radius: 14px; border: 1.5px solid #F1F5F9;
    padding: 0; display: flex; flex-direction: column; gap: 0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05); transition: box-shadow .2s, transform .2s;
    overflow: hidden;
}
.cr-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
.cr-card-inactive { opacity: .7; background: #F8FAFF; }

/* ── Card image area ── */
.cr-card-img {
    position: relative; width: 100%; height: 150px; overflow: hidden;
    background: linear-gradient(135deg,#EEF2FF,#E0E7FF); flex-shrink: 0;
}
.cr-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.cr-card-img-placeholder {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: #A5B4FC;
}
.cr-status-overlay {
    position: absolute; top: .55rem; left: .65rem;
}
.cr-actions-overlay {
    position: absolute; top: .45rem; right: .55rem;
}
.cr-card-body {
    padding: .85rem 1.2rem .9rem;
    display: flex; flex-direction: column; gap: .55rem;
    flex: 1;
}

.cr-card-top { display: flex; align-items: center; justify-content: space-between; }
.cr-status-badge { padding: .2rem .65rem; border-radius: 50px; font-size: .7rem; font-weight: 700; }
.cr-badge-active   { background: #D1FAE5; color: #065F46; }
.cr-badge-inactive { background: #F1F5F9; color: #64748B; }

.cr-card-actions { display: flex; gap: 5px; }
.cr-action-edit, .cr-action-delete {
    width: 28px; height: 28px; border-radius: 7px; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer; transition: all .15s;
}
.cr-action-edit   { background: #EEF2FF; color: #4F46E5 !important; }
.cr-action-edit:hover   { background: #4F46E5; color: #fff !important; }
.cr-action-delete { background: #FEE2E2; color: #DC2626; }
.cr-action-delete:hover { background: #DC2626; color: #fff; }

.cr-category-pill {
    display: inline-block; background: #F1F5F9; color: #475569;
    padding: .15rem .6rem; border-radius: 50px; font-size: .68rem; font-weight: 600; width: fit-content;
}
.cr-card-title { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; line-height: 1.3; }
.cr-card-desc  { font-size: .76rem; color: #64748B; margin: 0; line-height: 1.5; }

.cr-meta { display: flex; flex-wrap: wrap; gap: .5rem; }
.cr-meta-item { display: flex; align-items: center; gap: 5px; font-size: .73rem; color: #64748B; }
.cr-meta-item i { color: #94A3B8; font-size: .68rem; }

.cr-card-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: .6rem; border-top: 1px solid #F1F5F9; }
.cr-price { font-size: .95rem; font-weight: 800; color: #4F46E5; }
.cr-teacher-tag { font-size: .72rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: .18rem .6rem; border-radius: 50px; }
.cr-no-teacher  { font-size: .72rem; color: #CBD5E1; font-style: italic; }

@media(max-width:991.98px) { .cr-stats { grid-template-columns: repeat(2,1fr); } }
@media(max-width:767.98px) {
    .cr-grid { grid-template-columns: 1fr; }
    .cr-stats { grid-template-columns: repeat(2,1fr); }
}
</style>
@endsection
