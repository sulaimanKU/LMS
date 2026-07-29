@extends('applayouts.app')

@section('contents')
<div class="sd-page-wrap">

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

    {{-- ── Header Navigation ── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('course.index') }}" class="sd-btn-back">
                <i class="fa-solid fa-arrow-left me-2"></i>Back to Courses
            </a>
            <div>
                <h4 class="sd-page-title mb-0">Student Profile & Academic Record</h4>
                <p class="sd-page-sub mb-0">Comprehensive enrollment, payment history and performance profile</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="mailto:{{ $registration->email }}" class="sd-btn-outline">
                <i class="fa-solid fa-envelope me-2"></i>Email Student
            </a>
            @if(isset($user))
                <button type="button" class="sd-btn-primary" data-bs-toggle="modal" data-bs-target="#enrollCourseModal">
                    <i class="fa-solid fa-user-plus me-2"></i>Enroll in New Course / Workshop
                </button>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- ── Left Column: Student Bio & Academic Profile ── --}}
        <div class="col-12 col-lg-4">
            <div class="sd-card mb-4 text-center">
                <div class="sd-card-banner"></div>
                <div class="sd-avatar-container">
                    @if(isset($user) && $user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="sd-profile-avatar" alt="{{ $registration->name }}">
                    @else
                        <div class="sd-profile-avatar-placeholder">
                            {{ strtoupper(substr($registration->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="px-4 pb-4 pt-2">
                    <h4 class="fw-extrabold text-dark mb-1">{{ $registration->name }}</h4>
                    <p class="text-muted small mb-3"><i class="fa-solid fa-envelope me-1"></i>{{ $registration->email }}</p>
                    
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                        <span class="sd-badge {{ $registration->status == 'approved' ? 'sd-badge-success' : 'sd-badge-warning' }}">
                            <span class="sd-dot"></span>{{ ucfirst($registration->status ?? 'Approved') }} Student
                        </span>
                    </div>

                    <div class="sd-info-list text-start">
                        <div class="sd-info-item">
                            <span class="sd-info-label"><i class="fa-solid fa-phone me-2 text-primary"></i>Mobile / Phone</span>
                            <span class="sd-info-value">{{ $registration->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="sd-info-item">
                            <span class="sd-info-label"><i class="fa-solid fa-building-columns me-2 text-primary"></i>Institution</span>
                            <span class="sd-info-value">{{ $registration->institution ?? 'Not Specified' }}</span>
                        </div>
                        <div class="sd-info-item">
                            <span class="sd-info-label"><i class="fa-solid fa-flask me-2 text-primary"></i>Research Area</span>
                            <span class="sd-info-value">{{ $registration->research_area ?? 'General Studies' }}</span>
                        </div>
                        <div class="sd-info-item mb-0">
                            <span class="sd-info-label"><i class="fa-solid fa-calendar-check me-2 text-primary"></i>Registration Date</span>
                            <span class="sd-info-value">{{ $registration->created_at ? $registration->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metric Summary Tile --}}
            <div class="sd-card p-4">
                <h6 class="fw-extrabold text-dark mb-3"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Academic Snapshot</h6>
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="sd-metric-box bg-indigo-subtle">
                            <span class="sd-metric-num text-indigo">{{ $courses->count() }}</span>
                            <span class="sd-metric-lbl">Total Modules</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="sd-metric-box bg-emerald-subtle">
                            <span class="sd-metric-num text-emerald">{{ $enrolledModules->count() }}</span>
                            <span class="sd-metric-lbl">Enrolled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Right Column: Enrolled Modules & Payment Slips ── --}}
        <div class="col-12 col-lg-8">
            
            {{-- Enrolled Modules Card --}}
            <div class="sd-card mb-4 overflow-hidden">
                <div class="sd-card-header">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="sd-card-title mb-1"><i class="fa-solid fa-book-open me-2 text-primary"></i>Enrolled & Selected Modules</h5>
                            <p class="sd-card-sub mb-0">Courses and workshops student is currently enrolled in</p>
                        </div>
                        @if(isset($user))
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#enrollCourseModal">
                                <i class="fa-solid fa-plus me-1"></i>Add Course / Workshop
                            </button>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table sd-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Course Title</th>
                                <th>Category</th>
                                <th>Course Fee</th>
                                <th class="text-center">Enrollment Status</th>
                                <th class="text-end pe-4">Update Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                                @php
                                    $isEnrolled = $enrolledModules->contains('id', $course->id);
                                    $enrollData = $isEnrolled ? $enrolledModules->where('id', $course->id)->first() : null;
                                    $currentStatus = $enrollData?->pivot?->status ?? 'active';
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="sd-course-icon">
                                                <i class="fa-solid {{ $course->category === 'Workshop' ? 'fa-pen-ruler' : 'fa-book' }}"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block">{{ $course->title }}</span>
                                                <span class="text-muted small">{{ $course->duration ?? 'Standard Duration' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="sd-pill-chip">{{ $course->category }}</span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        PKR {{ number_format($course->price, 0) }}
                                    </td>
                                    <td class="text-center">
                                        @if($isEnrolled)
                                            <span class="sd-chip {{ $currentStatus === 'active' ? 'sd-chip-success' : ($currentStatus === 'completed' ? 'sd-chip-info' : 'sd-chip-muted') }}">
                                                <span class="sd-dot"></span>{{ ucfirst($currentStatus) }}
                                            </span>
                                        @else
                                            <span class="sd-chip sd-chip-warning">
                                                <span class="sd-dot"></span>Selected (Not Activated)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if(isset($user))
                                            <form action="{{ route('admin.student.enrollCourse') }}" method="POST" class="d-inline-flex gap-1 m-0 align-items-center justify-content-end">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="hidden" name="module_id" value="{{ $course->id }}">
                                                <select name="status" class="form-select form-select-sm rounded-pill border py-1 px-2 fw-semibold" style="font-size:0.75rem; width: 110px;" onchange="this.form.submit()">
                                                    <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="completed" {{ $currentStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="text-muted small">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No courses found for this student.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($registration->total_amount > 0)
                        <tfoot>
                            <tr class="bg-light fw-bold">
                                <td class="ps-4">Total Registered Fee</td>
                                <td colspan="4" class="text-end text-primary pe-4 fs-6">
                                    PKR {{ number_format($registration->total_amount, 0) }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Payment Receipts Card --}}
            <div class="sd-card">
                <div class="sd-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="sd-card-title mb-1"><i class="fa-solid fa-file-invoice-dollar me-2 text-emerald"></i>Uploaded Payment Slips</h5>
                            <p class="sd-card-sub mb-0">Receipts submitted by student for registration approval</p>
                        </div>
                        <span class="badge bg-emerald-subtle text-emerald rounded-pill px-3 py-2 fw-bold">
                            {{ $registration->slips ? $registration->slips->count() : 0 }} Slip(s)
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    @if(!$registration->slips || $registration->slips->isEmpty())
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="fa-solid fa-receipt fs-1 text-muted opacity-25 mb-3 d-block"></i>
                            <h6 class="fw-bold text-dark">No Payment Slips Uploaded</h6>
                            <p class="text-muted small mb-0">This student has not uploaded any payment verification receipts.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($registration->slips as $index => $slip)
                                <div class="col-md-6">
                                    <div class="sd-slip-box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">Payment Slip #{{ $index + 1 }}</h6>
                                                <span class="text-muted small">{{ $slip->created_at ? $slip->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                            </div>
                                            <span class="sd-chip {{ $slip->status == 'approved' ? 'sd-chip-success' : 'sd-chip-warning' }}">
                                                {{ ucfirst($slip->status) }}
                                            </span>
                                        </div>

                                        @php $isPdf = strtolower(pathinfo($slip->file_path, PATHINFO_EXTENSION)) === 'pdf'; @endphp

                                        <div class="sd-slip-preview mb-3">
                                            @if($isPdf)
                                                <div class="py-4 text-center">
                                                    <i class="fa-solid fa-file-pdf text-danger fs-1 mb-2 d-block"></i>
                                                    <span class="small fw-semibold text-dark">PDF Verification Document</span>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $slip->file_path) }}" class="sd-slip-img" alt="Payment Slip">
                                            @endif
                                        </div>

                                        <a href="{{ asset('storage/' . $slip->file_path) }}" target="_blank" class="sd-btn-view-slip">
                                            <i class="fa-solid fa-eye me-2"></i>Open Full Document
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ── MODAL — Enroll Student in Additional Course / Workshop ── --}}
@if(isset($user))
<div class="modal fade" id="enrollCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <div>
                    <h5 class="modal-title fw-bold mb-0"><i class="fa-solid fa-user-plus me-2"></i>Enroll Student in Course / Workshop</h5>
                    <p class="small mb-0 opacity-75">Assign additional courses or workshops to {{ $user->name }}</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.student.enrollCourse') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Select Course or Workshop</label>
                        <select name="module_id" class="form-select rounded-3 py-2 fw-semibold" required>
                            <option value="">Choose Course / Workshop...</option>
                            @foreach($allAvailableCourses as $avCourse)
                                <option value="{{ $avCourse->id }}">
                                    [{{ $avCourse->category }}] {{ $avCourse->title }} - PKR {{ number_format($avCourse->price, 0) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Enrollment Status</label>
                        <select name="status" class="form-select rounded-3 py-2 fw-semibold" required>
                            <option value="active" selected>Active (Enrolled)</option>
                            <option value="completed">Completed</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-2"></i>Enroll Student Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.sd-page-wrap {
    padding: 2rem;
    background-color: #F8FAFC;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.sd-page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0F172A;
}
.sd-page-sub {
    font-size: 0.85rem;
    color: #64748B;
}

.sd-btn-back {
    background: #FFFFFF;
    color: #475569 !important;
    border: 1px solid #E2E8F0;
    border-radius: 50px;
    padding: 0.55rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}
.sd-btn-back:hover {
    background: #F1F5F9;
    color: #0F172A !important;
}

.sd-btn-primary {
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF !important;
    border: none;
    border-radius: 50px;
    padding: 0.6rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
    transition: all 0.2s ease;
}
.sd-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
}

.sd-btn-outline {
    background: #FFFFFF;
    color: #4F46E5 !important;
    border: 1.5px solid #C7D2FE;
    border-radius: 50px;
    padding: 0.6rem 1.25rem;
    font-size: 0.82rem;
    font-weight: 800;
    text-decoration: none;
    transition: all 0.2s ease;
}
.sd-btn-outline:hover {
    background: #EEF2FF;
}

/* ── Card Styling ── */
.sd-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    overflow: hidden;
    position: relative;
}
.sd-card-banner {
    height: 90px;
    background: linear-gradient(135deg, #1E1B4B, #4F46E5);
}
.sd-avatar-container {
    margin-top: -45px;
    margin-bottom: 0.5rem;
}
.sd-profile-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #FFFFFF;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}
.sd-profile-avatar-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #FFFFFF;
    font-size: 2.5rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #FFFFFF;
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.3);
}

.sd-badge {
    padding: 0.3rem 0.9rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.sd-badge-success { background: #ECFDF5; color: #059669; }
.sd-badge-warning { background: #FFFBEB; color: #D97706; }
.sd-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.sd-info-list {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 1rem 1.25rem;
}
.sd-info-item {
    margin-bottom: 0.85rem;
}
.sd-info-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    display: block;
    margin-bottom: 2px;
}
.sd-info-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0F172A;
    display: block;
}

.sd-metric-box {
    padding: 1rem;
    border-radius: 14px;
}
.bg-indigo-subtle { background: #EEF2FF; }
.text-indigo { color: #4F46E5; }
.bg-emerald-subtle { background: #ECFDF5; }
.text-emerald { color: #10B981; }

.sd-metric-num {
    font-size: 1.5rem;
    font-weight: 800;
    display: block;
    line-height: 1;
}
.sd-metric-lbl {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748B;
    margin-top: 4px;
    display: block;
}

/* ── Card Header & Table ── */
.sd-card-header {
    padding: 1.25rem 1.5rem;
    background: #FAFAFC;
    border-bottom: 1px solid #F1F5F9;
}
.sd-card-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0F172A;
}
.sd-card-sub {
    font-size: 0.8rem;
    color: #64748B;
}

.sd-table thead th {
    background: #F8FAFC;
    color: #64748B;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
}
.sd-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.sd-course-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.sd-pill-chip {
    background: #F1F5F9;
    color: #475569;
    padding: 0.2rem 0.7rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 700;
}
.sd-chip {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.sd-chip-success { background: #ECFDF5; color: #059669; }
.sd-chip-info { background: #EEF2FF; color: #4F46E5; }
.sd-chip-muted { background: #F1F5F9; color: #64748B; }
.sd-chip-warning { background: #FFFBEB; color: #D97706; }

/* ── Payment Slip Boxes ── */
.sd-slip-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 1.15rem;
}
.sd-slip-preview {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 0.5rem;
    text-align: center;
}
.sd-slip-img {
    max-height: 160px;
    width: 100%;
    object-fit: contain;
    border-radius: 8px;
}
.sd-btn-view-slip {
    display: block;
    text-align: center;
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF !important;
    border-radius: 12px;
    padding: 0.55rem;
    font-size: 0.8rem;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.22);
    transition: all 0.2s ease;
}
.sd-btn-view-slip:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.32);
}
</style>
@endsection
