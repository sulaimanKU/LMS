@extends('applayouts.app')

@section('contents')
<div class="cr-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #ECFDF5; border-left: 4px solid #10B981 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
                <span class="fw-semibold text-dark">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
                <span class="fw-semibold text-dark">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Page Hero Header ── --}}
    <div class="cr-hero-card mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="cr-hero-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 class="cr-hero-title mb-1">Course & Student Directory</h4>
                        <p class="cr-hero-sub mb-0">Manage courses, monitor enrollments and inspect student details in real-time</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    {{-- Bulk Status Action Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-sliders me-1 text-primary"></i> Bulk Status Action
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2" style="min-width: 260px;">
                            <h6 class="dropdown-header text-uppercase text-muted fw-bold px-2" style="font-size: 0.7rem;">Batch Inactivate / Activate</h6>
                            
                            {{-- Form: Inactivate Workshop 1 --}}
                            <form action="{{ route('course.bulk-status') }}" method="POST" class="d-block mb-1">
                                @csrf
                                <input type="hidden" name="workshop_number" value="1">
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="dropdown-item rounded-2 text-danger fw-semibold py-2 d-flex align-items-center justify-content-between" onclick="return confirm('Deactivate all courses in Workshop #1?')">
                                    <span><i class="fa-solid fa-circle-pause me-2"></i>Inactivate Workshop #1</span>
                                    <span class="badge bg-danger-subtle text-danger small">Batch</span>
                                </button>
                            </form>

                            {{-- Form: Activate Workshop 1 --}}
                            <form action="{{ route('course.bulk-status') }}" method="POST" class="d-block mb-1">
                                @csrf
                                <input type="hidden" name="workshop_number" value="1">
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="dropdown-item rounded-2 text-success fw-semibold py-2 d-flex align-items-center justify-content-between" onclick="return confirm('Activate all courses in Workshop #1?')">
                                    <span><i class="fa-solid fa-circle-check me-2"></i>Activate Workshop #1</span>
                                    <span class="badge bg-success-subtle text-success small">Batch</span>
                                </button>
                            </form>

                            <hr class="dropdown-divider my-1">

                            {{-- Form: Inactivate ALL Courses --}}
                            <form action="{{ route('course.bulk-status') }}" method="POST" class="d-block mb-1">
                                @csrf
                                <input type="hidden" name="workshop_number" value="all">
                                <input type="hidden" name="status" value="inactive">
                                <button type="submit" class="dropdown-item rounded-2 text-danger small py-2" onclick="return confirm('Deactivate ALL courses in the system?')">
                                    <i class="fa-solid fa-power-off me-2"></i>Inactivate ALL Courses
                                </button>
                            </form>

                            {{-- Form: Activate ALL Courses --}}
                            <form action="{{ route('course.bulk-status') }}" method="POST" class="d-block">
                                @csrf
                                <input type="hidden" name="workshop_number" value="all">
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="dropdown-item rounded-2 text-success small py-2" onclick="return confirm('Activate ALL courses in the system?')">
                                    <i class="fa-solid fa-play me-2"></i>Activate ALL Courses
                                </button>
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('course.create') }}" class="cr-btn-primary text-decoration-none">
                        <i class="fa-solid fa-plus me-2"></i>Create New Course
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Executive Stat Cards ── --}}
    <div class="cr-stats-grid mb-4">
        <div class="cr-stat-box">
            <div class="cr-stat-icon-wrap bg-indigo-subtle text-indigo">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="cr-stat-content">
                <span class="cr-stat-val">{{ $totalCourses }}</span>
                <span class="cr-stat-lbl">Total Courses</span>
            </div>
        </div>
        <div class="cr-stat-box">
            <div class="cr-stat-icon-wrap bg-emerald-subtle text-emerald">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="cr-stat-content">
                <span class="cr-stat-val">{{ $activeCourses }}</span>
                <span class="cr-stat-lbl">Active Courses</span>
            </div>
        </div>
        <div class="cr-stat-box">
            <div class="cr-stat-icon-wrap bg-slate-subtle text-slate">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div class="cr-stat-content">
                <span class="cr-stat-val">{{ $inactiveCourses }}</span>
                <span class="cr-stat-lbl">Inactive Courses</span>
            </div>
        </div>
        <div class="cr-stat-box">
            <div class="cr-stat-icon-wrap bg-amber-subtle text-amber">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="cr-stat-content">
                <span class="cr-stat-val">{{ $totalEnrolled }}</span>
                <span class="cr-stat-lbl">Total Enrollments</span>
            </div>
        </div>
    </div>

    {{-- ── Modern Enterprise Filter & Search Toolbar ── --}}
    <div class="cr-toolbar mb-4">
        <div class="row align-items-center g-3">
            {{-- Status Tabs --}}
            <div class="col-12 col-xl-4">
                <div class="cr-nav-pills">
                    <a class="cr-pill {{ $filter === 'all' && !$selectedCourseId ? 'active' : '' }}" href="{{ route('course.index', ['filter' => 'all']) }}">
                        All Courses <span class="cr-pill-badge">{{ $totalCourses }}</span>
                    </a>
                    <a class="cr-pill {{ $filter === 'active' ? 'active' : '' }}" href="{{ route('course.index', ['filter' => 'active']) }}">
                        Active <span class="cr-pill-badge">{{ $activeCourses }}</span>
                    </a>
                    <a class="cr-pill {{ $filter === 'inactive' ? 'active' : '' }}" href="{{ route('course.index', ['filter' => 'inactive']) }}">
                        Inactive <span class="cr-pill-badge">{{ $inactiveCourses }}</span>
                    </a>
                </div>
            </div>

            {{-- Selectors & Unified Search --}}
            <div class="col-12 col-xl-8">
                <div class="d-flex align-items-center justify-content-xl-end gap-2 flex-wrap">
                    
                    {{-- Filter by Course Dropdown --}}
                    <form action="{{ route('course.index') }}" method="GET" class="m-0 flex-grow-1 flex-md-grow-0">
                        @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                        @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
                        @if($category) <input type="hidden" name="category" value="{{ $category }}"> @endif
                        
                        <div class="input-group shadow-sm" style="border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                            <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fa-solid fa-graduation-cap"></i></span>
                            <select name="course_id" class="form-select border-0 py-2 fw-semibold text-dark shadow-none" onchange="this.form.submit()" style="font-size: 0.85rem; max-width: 280px; cursor: pointer;">
                                <option value="">Filter by Course / Workshop...</option>
                                @foreach($allCoursesList as $cList)
                                    <option value="{{ $cList->id }}" {{ $selectedCourseId == $cList->id ? 'selected' : '' }}>
                                        {{ $cList->title }} ({{ $cList->enrollments_count }} Enrolled)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    {{-- Category Filter --}}
                    <form action="{{ route('course.index') }}" method="GET" class="m-0 flex-grow-1 flex-md-grow-0">
                        @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                        @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
                        @if($selectedCourseId) <input type="hidden" name="course_id" value="{{ $selectedCourseId }}"> @endif
                        
                        <div class="input-group shadow-sm" style="border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                            <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fa-solid fa-filter"></i></span>
                            <select name="category" class="form-select border-0 py-2 fw-semibold text-dark shadow-none" onchange="this.form.submit()" style="font-size: 0.85rem; max-width: 180px; cursor: pointer;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    {{-- Global Search Bar (Searches course title, student name, email, mobile phone) --}}
                    <form action="{{ route('course.index') }}" method="GET" class="m-0 flex-grow-1 flex-md-grow-0">
                        @if($category) <input type="hidden" name="category" value="{{ $category }}"> @endif
                        @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                        @if($selectedCourseId) <input type="hidden" name="course_id" value="{{ $selectedCourseId }}"> @endif
                        
                        <div class="input-group shadow-sm" style="border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                            <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search course, email, mobile..." class="form-control border-0 py-2 fw-medium text-dark shadow-none" style="font-size: 0.85rem; min-width: 220px;">
                            @if($search)
                                <a href="{{ route('course.index', array_filter(['filter' => $filter, 'category' => $category, 'course_id' => $selectedCourseId])) }}" class="input-group-text bg-white border-0 text-muted px-3" title="Clear Search">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- ── Enrolled Students Panel (When Course Selected OR Student Search Match) ── --}}
    @if($selectedCourse || $enrolledStudents->isNotEmpty())
        <div class="cr-panel-card mb-5">
            <div class="cr-panel-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="cr-course-badge-avatar">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <div>
                            @if($selectedCourse)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="cr-chip cr-chip-primary">{{ $selectedCourse->category }}</span>
                                    <span class="cr-chip {{ $selectedCourse->status === 'active' ? 'cr-chip-success' : 'cr-chip-muted' }}">
                                        <span class="cr-dot"></span>{{ ucfirst($selectedCourse->status) }}
                                    </span>
                                </div>
                                <h4 class="cr-course-panel-title mb-1">{{ $selectedCourse->title }}</h4>
                                <p class="cr-course-panel-meta mb-0">
                                    <i class="fa-solid fa-chalkboard-user me-1 text-primary"></i>Instructor: <strong>{{ $selectedCourse->teacher->first()?->name ?? 'Unassigned' }}</strong>
                                    &nbsp;&bull;&nbsp; <i class="fa-solid fa-clock me-1 text-primary"></i>Duration: <strong>{{ $selectedCourse->duration ?? 'N/A' }}</strong>
                                </p>
                            @else
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="cr-chip cr-chip-primary">Search Match</span>
                                </div>
                                <h4 class="cr-course-panel-title mb-1">Matching Student Results for "{{ $search }}"</h4>
                                <p class="cr-course-panel-meta mb-0">Found {{ $enrolledStudents->count() }} student record(s) matching your search query</p>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="cr-enrolled-count-chip">
                            <i class="fa-solid fa-users me-2"></i>{{ $enrolledStudents->count() }} Student(s) Listed
                        </div>
                        <a href="{{ route('course.index') }}" class="cr-btn-close-view">
                            <i class="fa-solid fa-xmark me-1"></i>Close Student View
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table cr-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Student Profile</th>
                            <th>Email & Mobile Contact</th>
                            <th>Institution & Field</th>
                            <th class="text-center">Enrolled On</th>
                            <th class="text-center">Activity Metrics</th>
                            <th class="text-center">Certificate Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrolledStudents as $st)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($st->profile_image)
                                            <img src="{{ asset('storage/' . $st->profile_image) }}" class="cr-student-avatar" alt="{{ $st->name }}">
                                        @else
                                            <div class="cr-student-avatar-placeholder">
                                                {{ strtoupper(substr($st->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="cr-student-name">{{ $st->name }}</span>
                                            <span class="cr-status-tag {{ $st->enrollment_status === 'active' ? 'tag-active' : 'tag-completed' }}">
                                                {{ ucfirst($st->enrollment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cr-contact-block">
                                        <div class="text-dark font-medium"><i class="fa-solid fa-envelope me-1 text-muted"></i>{{ $st->email }}</div>
                                        <div class="text-muted small"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $st->phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cr-academic-block">
                                        <div class="fw-semibold text-dark">{{ $st->institution }}</div>
                                        <div class="text-muted small">{{ $st->research_area }}</div>
                                    </div>
                                </td>
                                <td class="text-center text-muted small fw-medium">
                                    {{ $st->enrolled_at }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <span class="cr-metric-badge badge-green">
                                            <i class="fa-solid fa-user-check me-1"></i>{{ $st->attendance_count }} Classes
                                        </span>
                                        <span class="cr-metric-badge badge-amber">
                                            <i class="fa-solid fa-file-lines me-1"></i>{{ $st->submissions_count }} Tasks
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($st->certificate)
                                        <a href="{{ asset('storage/' . $st->certificate->certificate_path) }}" target="_blank" class="cr-cert-link cert-issued">
                                            <i class="fa-solid fa-award me-1"></i>Issued (View)
                                        </a>
                                    @else
                                        <a href="{{ route('admin.certificates.management', ['module_id' => $selectedCourse?->id ?? '']) }}" class="cr-cert-link cert-pending">
                                            <i class="fa-solid fa-clock me-1"></i>Allot Cert
                                        </a>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.student.details', $st->registration_id) }}" class="cr-btn-detail text-decoration-none">
                                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i>Manage & Enroll
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="cr-empty-students py-4 text-center">
                                        <i class="fa-solid fa-user-graduate fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                        <h6 class="fw-bold text-dark">No Enrolled Students Found</h6>
                                        <p class="text-muted small">No students matched your filter or search query.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ── All Courses Grid (Default View) ── --}}
    <div class="cr-grid">
        @forelse($courses as $course)
        <div class="cr-card {{ $course->status === 'inactive' ? 'cr-card-inactive' : '' }}">

            {{-- Image & Badges --}}
            <div class="cr-card-media">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                @else
                    <div class="cr-card-placeholder">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                @endif
                
                <div class="cr-media-badges">
                    <form action="{{ route('course.toggle-status', $course->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="border-0 bg-transparent p-0" title="Click to toggle Active / Inactive" onclick="return confirm('Change status of {{ addslashes($course->title) }} to {{ $course->status === 'active' ? 'Inactive' : 'Active' }}?')">
                            <span class="cr-status-chip {{ $course->status === 'active' ? 'status-active' : 'status-inactive' }}" style="cursor: pointer;">
                                <span class="cr-dot"></span>{{ ucfirst($course->status) }}
                            </span>
                        </button>
                    </form>
                </div>

                <div class="cr-media-actions">
                    <a href="{{ route('course.edit', $course->id) }}" class="cr-icon-btn edit-btn" title="Edit Course">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($course->title) }}\'? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cr-icon-btn delete-btn" title="Delete Course">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="cr-card-content">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="cr-cat-pill">{{ $course->category }}</span>
                    <span class="cr-price-tag">PKR {{ number_format($course->price, 0) }}</span>
                </div>

                <h6 class="cr-card-h">{{ $course->title }}</h6>
                @if($course->short_description)
                    <p class="cr-card-p">{{ Str::limit(strip_tags($course->short_description), 85) }}</p>
                @endif

                <div class="cr-card-specs mb-3">
                    @if($course->duration)
                    <span class="spec-item"><i class="fa-solid fa-clock"></i>{{ $course->duration }}</span>
                    @endif
                    <span class="spec-item"><i class="fa-solid fa-book-bookmark"></i>{{ $course->lessons_count }} Lessons</span>
                    <span class="spec-item"><i class="fa-solid fa-users"></i>{{ $course->enrollments_count }} Enrolled</span>
                </div>

                <div class="cr-card-footer">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        @php $teacher = $course->teacher->first(); @endphp
                        @if($teacher)
                            <div class="cr-teacher-chip">
                                <i class="fa-solid fa-chalkboard-user me-1 text-emerald"></i>{{ Str::limit($teacher->name, 20) }}
                            </div>
                        @else
                            <span class="text-muted extra-small italic">No Instructor</span>
                        @endif
                    </div>

                    <a href="{{ route('course.index', ['course_id' => $course->id]) }}" class="cr-btn-view-students text-decoration-none">
                        <i class="fa-solid fa-user-graduate me-2"></i>View Enrolled Students ({{ $course->enrollments_count }})
                    </a>
                </div>
            </div>

        </div>
        @empty
        <div class="cr-empty-grid text-center py-5">
            <i class="fa-solid fa-book-open-reader fa-3x text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="fw-bold text-dark">No Courses Found</h5>
            <p class="text-muted small">No courses match your current search or filter criteria.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($courses->hasPages())
    <div class="cr-pagination-wrap mt-4">
        {{ $courses->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

<style>
/* ── Global Page Styling ── */
.cr-page {
    padding: 2rem;
    background-color: #F8FAFC;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* ── Hero Card ── */
.cr-hero-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}
.cr-hero-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    flex-shrink: 0;
}
.cr-hero-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.3px;
}
.cr-hero-sub {
    font-size: 0.85rem;
    color: #64748B;
}
.cr-btn-primary {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF !important;
    border-radius: 12px;
    padding: 0.65rem 1.25rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.28);
    transition: all 0.2s ease;
}
.cr-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.38);
}

/* ── Stats Grid ── */
.cr-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
.cr-stat-box {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 1.15rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.02);
    transition: all 0.2s ease;
}
.cr-stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    border-color: #CBD5E1;
}
.cr-stat-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.bg-indigo-subtle { background: #EEF2FF; }
.text-indigo { color: #4F46E5; }
.bg-emerald-subtle { background: #ECFDF5; }
.text-emerald { color: #10B981; }
.bg-slate-subtle { background: #F1F5F9; }
.text-slate { color: #64748B; }
.bg-amber-subtle { background: #FFFBEB; }
.text-amber { color: #D97706; }

.cr-stat-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    display: block;
}
.cr-stat-lbl {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    margin-top: 0.25rem;
    display: block;
}

/* ── Toolbar & Filter Styling ── */
.cr-toolbar {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.cr-nav-pills {
    display: inline-flex;
    background: #F1F5F9;
    padding: 4px;
    border-radius: 12px;
    gap: 4px;
}
.cr-pill {
    padding: 0.45rem 1rem;
    border-radius: 9px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #64748B;
    text-decoration: none;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}
.cr-pill.active {
    background: #FFFFFF;
    color: #4F46E5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.cr-pill-badge {
    font-size: 0.7rem;
    padding: 2px 7px;
    border-radius: 50px;
    background: #F1F5F9;
    color: #475569;
}
.cr-pill.active .cr-pill-badge {
    background: #EEF2FF;
    color: #4F46E5;
}

/* ── Panel Card (Selected Course Students) ── */
.cr-panel-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}
.cr-panel-header {
    padding: 1.5rem 1.75rem;
    border-bottom: 1px solid #F1F5F9;
    background: #FAFAFC;
}
.cr-course-badge-avatar {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #312E81, #4F46E5);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(49, 46, 129, 0.25);
}
.cr-chip {
    padding: 0.2rem 0.7rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.cr-chip-primary { background: #EEF2FF; color: #4F46E5; }
.cr-chip-success { background: #ECFDF5; color: #059669; }
.cr-chip-muted { background: #F1F5F9; color: #64748B; }
.cr-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}
.cr-course-panel-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0F172A;
}
.cr-course-panel-meta {
    font-size: 0.82rem;
    color: #64748B;
}
.cr-enrolled-count-chip {
    background: #EEF2FF;
    color: #4F46E5;
    border: 1px solid #C7D2FE;
    padding: 0.5rem 1.1rem;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 800;
}
.cr-btn-close-view {
    background: #F1F5F9;
    color: #475569;
    border-radius: 50px;
    padding: 0.5rem 1.1rem;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}
.cr-btn-close-view:hover { background: #E2E8F0; color: #0F172A; }

/* ── Table Design ── */
.cr-table {
    margin-bottom: 0;
}
.cr-table thead th {
    background: #F8FAFC;
    color: #64748B;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
}
.cr-table tbody td {
    padding: 1.15rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.cr-table tbody tr:hover {
    background-color: #F8FAFC;
}
.cr-student-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #E2E8F0;
}
.cr-student-avatar-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #FFFFFF;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
}
.cr-student-name {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0F172A;
    display: block;
}
.cr-status-tag {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 50px;
    display: inline-block;
    margin-top: 2px;
}
.tag-active { background: #ECFDF5; color: #059669; }
.tag-completed { background: #EEF2FF; color: #4F46E5; }

.cr-metric-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    border: 1px solid transparent;
}
.badge-green { background: #ECFDF5; color: #059669; border-color: #A7F3D0; }
.badge-amber { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }

.cr-cert-link {
    padding: 0.4rem 0.9rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}
.cert-issued { background: #10B981; color: #FFFFFF !important; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25); }
.cert-issued:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35); }
.cert-pending { background: #F1F5F9; color: #64748B !important; border: 1px solid #CBD5E1; }
.cert-pending:hover { background: #E2E8F0; color: #0F172A !important; }

.cr-btn-detail {
    display: inline-flex;
    align-items: center;
    background: #EEF2FF;
    color: #4F46E5 !important;
    border: 1px solid #C7D2FE;
    border-radius: 50px;
    padding: 0.45rem 1.1rem;
    font-size: 0.78rem;
    font-weight: 800;
    transition: all 0.2s ease;
}
.cr-btn-detail:hover {
    background: #4F46E5;
    color: #FFFFFF !important;
    border-color: #4F46E5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

/* ── Course Grid Cards ── */
.cr-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}
.cr-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    transition: all 0.25s ease;
}
.cr-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    border-color: #CBD5E1;
}
.cr-card-inactive { opacity: 0.75; }

.cr-card-media {
    position: relative;
    width: 100%;
    height: 160px;
    background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
    overflow: hidden;
}
.cr-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cr-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.75rem;
    color: #A5B4FC;
}
.cr-media-badges {
    position: absolute;
    top: 12px;
    left: 12px;
}
.cr-status-chip {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.status-active { background: #ECFDF5; color: #059669; }
.status-inactive { background: #F1F5F9; color: #64748B; }

.cr-media-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    gap: 6px;
}
.cr-icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
}
.edit-btn { background: rgba(255, 255, 255, 0.9); color: #4F46E5 !important; }
.edit-btn:hover { background: #4F46E5; color: #FFFFFF !important; }
.delete-btn { background: rgba(255, 255, 255, 0.9); color: #EF4444; }
.delete-btn:hover { background: #EF4444; color: #FFFFFF; }

.cr-card-content {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}
.cr-cat-pill {
    background: #F1F5F9;
    color: #475569;
    padding: 0.2rem 0.7rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
}
.cr-price-tag {
    font-size: 1rem;
    font-weight: 800;
    color: #4F46E5;
}
.cr-card-h {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 0.4rem;
    line-height: 1.35;
}
.cr-card-p {
    font-size: 0.8rem;
    color: #64748B;
    margin-bottom: 0.75rem;
    line-height: 1.5;
}
.cr-card-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.spec-item {
    font-size: 0.75rem;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 5px;
}
.spec-item i { color: #94A3B8; }

.cr-card-footer {
    margin-top: auto;
    padding-top: 0.85rem;
    border-top: 1px solid #F1F5F9;
}
.cr-teacher-chip {
    font-size: 0.75rem;
    font-weight: 700;
    color: #059669;
    background: #ECFDF5;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
}

.cr-btn-view-students {
    display: block;
    text-align: center;
    background: #F8FAFC;
    color: #4F46E5 !important;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 0.55rem 1rem;
    font-size: 0.8rem;
    font-weight: 800;
    transition: all 0.2s ease;
}
.cr-btn-view-students:hover {
    background: #4F46E5;
    color: #FFFFFF !important;
    border-color: #4F46E5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
}

@media(max-width: 991.98px) {
    .cr-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 575.98px) {
    .cr-stats-grid { grid-template-columns: 1fr; }
    .cr-page { padding: 1rem; }
}
</style>
@endsection
