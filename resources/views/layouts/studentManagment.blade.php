@extends('applayouts.app')

@section('contents')
<div class="sm-page">

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
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FFFBEB; border-left: 4px solid #F59E0B !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-5 me-2 text-warning"></i>
                <span class="fw-semibold text-dark">{{ session('warning') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #EEF2FF; border-left: 4px solid #6366F1 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info fs-5 me-2 text-primary"></i>
                <span class="fw-semibold text-dark">{{ session('info') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
                <div>
                    @foreach($errors->all() as $e)<div class="fw-semibold text-dark">{{ $e }}</div>@endforeach
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Hero Banner ── --}}
    <div class="sm-hero-card mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="sm-hero-icon">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h4 class="sm-hero-title mb-1">Student Management & Approvals</h4>
                        <p class="sm-hero-sub mb-0">Review registrations, verify payment slips, approve course enrollments & manage student accounts</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <button class="sm-btn-secondary" data-bs-toggle="modal" data-bs-target="#notifyStudentsModal">
                        <i class="fa-solid fa-paper-plane me-2"></i>Send Class Notice
                    </button>
                    <button class="sm-btn-primary" data-bs-toggle="modal" data-bs-target="#manualEntryModal">
                        <i class="fa-solid fa-user-plus me-2"></i>Add New Student
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Executive KPI Stat Cards ── --}}
    <div class="sm-stats-grid mb-4">
        <a href="{{ route('admin.student.management', ['filter' => 'all']) }}" class="text-decoration-none">
            <div class="sm-stat-box {{ $filter === 'all' ? 'active-stat' : '' }}">
                <div class="sm-stat-icon bg-indigo-subtle text-indigo">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span class="sm-stat-val">{{ $totalRegs ?? 0 }}</span>
                    <span class="sm-stat-lbl">Total Registrations</span>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.student.management', ['filter' => 'approved']) }}" class="text-decoration-none">
            <div class="sm-stat-box {{ $filter === 'approved' ? 'active-stat' : '' }}">
                <div class="sm-stat-icon bg-emerald-subtle text-emerald">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span class="sm-stat-val">{{ $approvedCount ?? 0 }}</span>
                    <span class="sm-stat-lbl">Approved Students</span>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.student.management', ['filter' => 'pending']) }}" class="text-decoration-none">
            <div class="sm-stat-box {{ $filter === 'pending' ? 'active-stat' : '' }}">
                <div class="sm-stat-icon bg-amber-subtle text-amber">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <span class="sm-stat-val">{{ $pendingCount ?? 0 }}</span>
                    <span class="sm-stat-lbl">Pending Review</span>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.student.management', ['filter' => 'rejected']) }}" class="text-decoration-none">
            <div class="sm-stat-box {{ $filter === 'rejected' ? 'active-stat' : '' }}">
                <div class="sm-stat-icon bg-rose-subtle text-rose">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <span class="sm-stat-val">{{ $rejectedCount ?? 0 }}</span>
                    <span class="sm-stat-lbl">Rejected Requests</span>
                </div>
            </div>
        </a>
    </div>

    {{-- ── Enterprise Toolbar ── --}}
    <div class="sm-toolbar mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 w-100">
            <div class="sm-nav-pills">
                <a class="sm-pill {{ $filter === 'all'      ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all',      'page' => 1]) }}">
                    All Applications <span class="sm-pill-badge">{{ $totalRegs }}</span>
                </a>
                <a class="sm-pill {{ $filter === 'pending'  ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'pending',  'page' => 1]) }}">
                    Pending Review <span class="sm-pill-badge">{{ $pendingCount }}</span>
                </a>
                <a class="sm-pill {{ $filter === 'approved' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'approved', 'page' => 1]) }}">
                    Approved <span class="sm-pill-badge">{{ $approvedCount }}</span>
                </a>
                <a class="sm-pill {{ $filter === 'rejected' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'rejected', 'page' => 1]) }}">
                    Rejected <span class="sm-pill-badge">{{ $rejectedCount }}</span>
                </a>
            </div>

            <form action="{{ route('admin.student.management') }}" method="GET" class="m-0">
                @if($filter !== 'all')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <div class="input-group shadow-sm" style="min-width: 280px; border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                    <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-0 py-2 fw-medium text-dark shadow-none" placeholder="Search student, email, phone..." value="{{ $search ?? '' }}" style="font-size: 0.85rem;">
                    @if($search)
                        <a href="{{ route('admin.student.management', ['filter' => $filter]) }}" class="input-group-text bg-white border-0 text-muted px-3" title="Clear Search">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                    <button class="btn btn-primary px-3 fw-bold" type="submit" style="background: #4F46E5; border-color: #4F46E5;">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Cards Grid ── --}}
    <div class="sm-grid mb-4">
        @forelse($registrations as $reg)
        @php
            $hasSlip       = $reg->slips->isNotEmpty();
            $courseIds     = array_map('intval', $reg->selected_courses ?? []);
            $courseCount   = count($courseIds);
            $enrolledMap   = $enrolledByEmail[$reg->email] ?? collect();
            $enrolledIds   = $enrolledMap->keys()->toArray();
            
            $alreadyEnrolledTitles = [];
            foreach($enrolledMap as $eid => $eMod) {
                if(!in_array($eid, $courseIds)) {
                    $alreadyEnrolledTitles[] = $eMod->title;
                }
            }
            
            $enrolledCount = count(array_intersect($enrolledIds, $courseIds));
            $hasAccount    = array_key_exists($reg->email, $enrolledByEmail->toArray());
            $allEnrolled   = $courseCount > 0 && $enrolledCount >= $courseCount;
            $partial       = $enrolledCount > 0 && $enrolledCount < $courseCount;
        @endphp

        <div class="sm-card" data-status="{{ $reg->status }}">

            {{-- Card top bar --}}
            <div class="sm-card-top">
                <div class="sm-avatar">{{ strtoupper(substr($reg->name,0,1)) }}</div>
                <div class="sm-info">
                    <h6 class="sm-name mb-0">{{ $reg->name }}</h6>
                    <p class="sm-email mb-1">{{ $reg->email }}</p>
                    <p class="sm-meta mb-1"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $reg->phone }} &bull; <i class="fa-solid fa-building-columns me-1 text-muted"></i>{{ Str::limit($reg->institution, 22) }}</p>
                    <p class="sm-meta mb-0 text-primary fw-bold" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-clock me-1"></i>Applied {{ $reg->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="ms-auto text-end">
                    @if($allEnrolled)
                        <span class="sm-badge sm-badge-approved"><i class="fa-solid fa-graduation-cap me-1"></i>Fully Enrolled</span>
                    @elseif($partial)
                        <span class="sm-badge sm-badge-partial"><i class="fa-solid fa-circle-half-stroke me-1"></i>{{ $enrolledCount }}/{{ $courseCount }} Enrolled</span>
                    @elseif($hasSlip)
                        <span class="sm-badge sm-badge-review"><i class="fa-solid fa-clock me-1"></i>Review Slip</span>
                    @else
                        <span class="sm-badge sm-badge-pending"><i class="fa-solid fa-hourglass me-1"></i>No Slip</span>
                    @endif
                </div>
            </div>

            {{-- Modules row --}}
            <div class="sm-courses-row">
                <span class="sm-courses-label mb-2">
                    <i class="fa-solid fa-book-open me-1 text-indigo"></i>Selected Modules ({{ $enrolledCount }}/{{ $courseCount }} enrolled)
                </span>
                
                @if($hasAccount && $enrolledMap->isNotEmpty())
                    <div class="sm-already-enrolled mb-2">
                        <small class="text-muted d-block mb-1 fw-bold" style="font-size: 0.65rem; text-transform: uppercase;">Existing Enrollments:</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($enrolledMap as $eid => $eMod)
                                @if(!in_array($eid, $courseIds))
                                    <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">
                                        <i class="fa-solid fa-check text-success me-1"></i>{{ Str::limit($eMod->title, 18) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="sm-courses-list">
                    @foreach($courseIds as $cid)
                        @if(isset($allCourses[$cid]))
                            @php 
                                $enrollment = $enrolledByEmail[$reg->email][$cid] ?? null;
                                $isEnrolled = !is_null($enrollment);
                                $status = $isEnrolled ? $enrollment->pivot->status : 'pending';
                            @endphp
                            
                            @if($isEnrolled)
                                <div class="dropdown d-inline-block">
                                    <button class="sm-course-pill sm-pill-{{ $status }} dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-{{ $status == 'active' ? 'circle-check' : ($status == 'completed' ? 'graduation-cap' : 'circle-xmark') }} me-1"></i>
                                        {{ Str::limit($allCourses[$cid]->title, 22) }}
                                    </button>
                                    <ul class="dropdown-menu shadow-sm border-0" style="font-size: .8rem; min-width: 140px;">
                                        <li><h6 class="dropdown-header">Update Module Status</h6></li>
                                        <li>
                                            <form action="{{ route('admin.enrollment.updateStatus') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ $reg->email }}">
                                                <input type="hidden" name="module_id" value="{{ $cid }}">
                                                <button type="submit" name="status" value="active" class="dropdown-item {{ $status == 'active' ? 'active' : '' }}">
                                                    <i class="fa-solid fa-circle-check me-2 text-success"></i>Active
                                                </button>
                                                <button type="submit" name="status" value="completed" class="dropdown-item {{ $status == 'completed' ? 'active' : '' }}">
                                                    <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>Completed
                                                </button>
                                                <button type="submit" name="status" value="dropped" class="dropdown-item {{ $status == 'dropped' ? 'active' : '' }}">
                                                    <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>Dropped
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <span class="sm-course-pill sm-pill-pending">
                                    <i class="fa-solid fa-clock me-1"></i>
                                    {{ Str::limit($allCourses[$cid]->title, 22) }}
                                </span>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Amount + slip + action --}}
            <div class="sm-card-foot">
                <div class="sm-amount">
                    <span class="sm-amount-label">Registered Amount</span>
                    <span class="sm-amount-val">PKR {{ number_format($reg->total_amount, 0) }}</span>
                </div>

                @php
                    $courseObjects = collect($courseIds)->map(function($cid) use ($allCourses) {
                        $c = $allCourses[$cid] ?? null;
                        return $c ? ['id' => $c->id, 'title' => $c->title, 'price' => (float)$c->price] : null;
                    })->filter()->values()->toArray();
                    $latestSlipUrl = $hasSlip ? asset('storage/' . $reg->slips->last()->file_path) : null;
                    $allSlipsJson  = $reg->slips->sortBy('created_at')->map(fn($s) => [
                        'url'    => asset('storage/' . $s->file_path),
                        'status' => $s->status,
                        'date'   => $s->created_at->format('d M Y'),
                    ])->values()->toJson();
                @endphp
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('admin.student.details', $reg->id) }}" class="sm-slip-btn text-decoration-none" title="View all student details">
                        <i class="fa-solid fa-circle-info me-1"></i>Details
                    </a>
                    @if($hasAccount)
                        <button class="sm-slip-btn" style="background: #F1F5F9; color: #475569; border-color: #E2E8F0;"
                                data-bs-toggle="modal" data-bs-target="#passwordModal"
                                data-name="{{ $reg->name }}"
                                data-email="{{ $reg->email }}"
                                title="Reset Student Password">
                            <i class="fa-solid fa-key me-1"></i>PW
                        </button>
                    @endif

                    @if($hasSlip)
                        <a href="{{ $latestSlipUrl }}" target="_blank" class="sm-slip-btn text-decoration-none" title="View latest payment slip">
                            <i class="fa-solid fa-receipt me-1"></i>Slip
                        </a>
                    @else
                        <span class="sm-no-slip">No slip</span>
                    @endif

                    @if($allEnrolled)
                        <button class="sm-action-btn sm-enrolled" disabled>
                            <i class="fa-solid fa-graduation-cap me-1"></i>Enrolled
                        </button>
                    @elseif($partial && $hasSlip)
                        <button class="sm-action-btn sm-add-btn"
                                data-bs-toggle="modal" data-bs-target="#reviewModal"
                                data-id="{{ $reg->id }}"
                                data-name="{{ $reg->name }}"
                                data-email="{{ $reg->email }}"
                                data-phone="{{ $reg->phone }}"
                                data-institution="{{ $reg->institution }}"
                                data-amount="{{ $reg->total_amount }}"
                                data-slips="{{ $allSlipsJson }}"
                                data-courses="{{ json_encode($courseObjects) }}"
                                data-enrolled-ids="{{ json_encode(array_values($enrolledIds)) }}"
                                data-already-enrolled="{{ json_encode($alreadyEnrolledTitles) }}"
                                data-has-account="true">
                            <i class="fa-solid fa-circle-plus me-1"></i>Add Remaining
                        </button>
                    @elseif($partial)
                        <button class="sm-action-btn sm-add-btn"
                                style="background: linear-gradient(135deg, #64748b, #475569);"
                                data-bs-toggle="modal" data-bs-target="#reviewModal"
                                data-id="{{ $reg->id }}"
                                data-name="{{ $reg->name }}"
                                data-email="{{ $reg->email }}"
                                data-phone="{{ $reg->phone }}"
                                data-institution="{{ $reg->institution }}"
                                data-amount="{{ $reg->total_amount }}"
                                data-slips="{{ $allSlipsJson }}"
                                data-courses="{{ json_encode($courseObjects) }}"
                                data-enrolled-ids="{{ json_encode(array_values($enrolledIds)) }}"
                                data-already-enrolled="{{ json_encode($alreadyEnrolledTitles) }}"
                                data-has-account="true">
                            <i class="fa-solid fa-user-plus me-1"></i>Manual Add
                        </button>
                    @elseif($hasSlip)
                        <button class="sm-action-btn sm-approve-btn"
                                data-bs-toggle="modal" data-bs-target="#reviewModal"
                                data-id="{{ $reg->id }}"
                                data-name="{{ $reg->name }}"
                                data-email="{{ $reg->email }}"
                                data-phone="{{ $reg->phone }}"
                                data-institution="{{ $reg->institution }}"
                                data-amount="{{ $reg->total_amount }}"
                                data-slips="{{ $allSlipsJson }}"
                                data-courses="{{ json_encode($courseObjects) }}"
                                data-enrolled-ids="[]"
                                data-already-enrolled="{{ json_encode($alreadyEnrolledTitles) }}"
                                data-has-account="false">
                            <i class="fa-solid fa-user-check me-1"></i>Review & Approve
                        </button>
                    @else
                        <button class="sm-action-btn sm-approve-btn"
                                style="background: linear-gradient(135deg, #64748b, #475569);"
                                data-bs-toggle="modal" data-bs-target="#reviewModal"
                                data-id="{{ $reg->id }}"
                                data-name="{{ $reg->name }}"
                                data-email="{{ $reg->email }}"
                                data-phone="{{ $reg->phone }}"
                                data-institution="{{ $reg->institution }}"
                                data-amount="{{ $reg->total_amount }}"
                                data-slips="[]"
                                data-courses="{{ json_encode($courseObjects) }}"
                                data-enrolled-ids="[]"
                                data-already-enrolled="{{ json_encode($alreadyEnrolledTitles) }}"
                                data-has-account="false">
                            <i class="fa-solid fa-user-check me-1"></i>Manual Approve
                        </button>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="sm-empty text-center py-5 w-100">
            <i class="fa-solid fa-users-slash fa-3x text-muted opacity-25 mb-3 d-block"></i>
            <h5 class="fw-bold text-dark">No Student Registrations Found</h5>
            <p class="text-muted small">No student records matched your current filter or search term.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($registrations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $registrations->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

{{-- ── REVIEW & APPROVE MODAL ── --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #1E1B4B, #4F46E5);">
                <div>
                    <h5 class="modal-title fw-extrabold mb-1" id="reviewModalTitle"><i class="fa-solid fa-user-check me-2"></i>Review & Approve Student</h5>
                    <p class="small mb-0 opacity-80" id="modalSubhead">Verify payment slips and select courses for enrollment</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.student.approve') }}" method="POST" id="approvalForm">
                @csrf
                <input type="hidden" name="registration_id" id="modalRegId">

                <div class="modal-body p-4">
                    <div class="row g-4">
                        {{-- Left: Student Details --}}
                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-4 border">
                                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-gear me-2 text-primary"></i>Student Details</h6>
                                <div class="mb-2"><span class="text-muted small d-block">Full Name</span><strong id="modalName" class="text-dark">-</strong></div>
                                <div class="mb-2"><span class="text-muted small d-block">Email Address</span><strong id="modalEmail" class="text-dark">-</strong></div>
                                <div class="mb-2"><span class="text-muted small d-block">Phone Number</span><strong id="modalPhone" class="text-dark">-</strong></div>
                                <div class="mb-2"><span class="text-muted small d-block">Institution</span><strong id="modalInst" class="text-dark">-</strong></div>
                                <div class="mb-0"><span class="text-muted small d-block">Registered Total Amount</span><strong id="modalAmount" class="text-primary">-</strong></div>
                            </div>
                        </div>

                        {{-- Right: Payment Slips & Course Selection --}}
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Uploaded Payment Slips</label>
                                <div id="slipsContainer" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Select Courses to Approve / Enroll</label>
                                <div id="coursesContainer" class="d-flex flex-column gap-2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #4F46E5, #6366F1); border: none;">
                        <i class="fa-solid fa-check me-2"></i>Confirm Approval & Enroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── PASSWORD RESET MODAL ── --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #1E1B4B, #312E81);">
                <div>
                    <h5 class="modal-title fw-bold mb-1"><i class="fa-solid fa-key me-2"></i>Reset Student Password</h5>
                    <p class="small mb-0 opacity-80" id="pwdModalSub">Set new password for student</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.user.updatePassword') }}" method="POST">
                @csrf
                <input type="hidden" name="email" id="pwdModalEmail">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NEW PASSWORD</label>
                        <input type="password" name="password" class="form-control py-2 rounded-3 border" required placeholder="Minimum 8 characters">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">CONFIRM PASSWORD</label>
                        <input type="password" name="password_confirmation" class="form-control py-2 rounded-3 border" required placeholder="Repeat password">
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: #4F46E5; border: none;">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MANUAL ENTRY MODAL ── --}}
<div class="modal fade" id="manualEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #1E1B4B, #4F46E5);">
                <div>
                    <h5 class="modal-title fw-bold mb-1"><i class="fa-solid fa-user-plus me-2"></i>Add New Student Manually</h5>
                    <p class="small mb-0 opacity-80">Create student account and assign courses directly</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.student.manualStore') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">FULL NAME</label>
                            <input type="text" name="name" class="form-control py-2 rounded-3 border" required placeholder="John Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">EMAIL ADDRESS</label>
                            <input type="email" name="email" class="form-control py-2 rounded-3 border" required placeholder="student@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">PHONE NUMBER</label>
                            <input type="text" name="phone" class="form-control py-2 rounded-3 border" placeholder="+92 300 1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">INSTITUTION</label>
                            <input type="text" name="institution" class="form-control py-2 rounded-3 border" placeholder="University Name">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">ASSIGN COURSES</label>
                            <div class="border rounded-3 p-3 bg-light" style="max-height: 180px; overflow-y: auto;">
                                @foreach($allCourses as $c)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="selected_courses[]" value="{{ $c->id }}" id="manualC{{ $c->id }}">
                                        <label class="form-check-label small fw-semibold text-dark" for="manualC{{ $c->id }}">
                                            {{ $c->title }} — <span class="text-primary fw-bold">PKR {{ number_format($c->price) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" style="background: #4F46E5; border: none;">
                        Create Student Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── NOTIFY STUDENTS MODAL ── --}}
<div class="modal fade" id="notifyStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #1E1B4B, #312E81);">
                <div>
                    <h5 class="modal-title fw-bold mb-1"><i class="fa-solid fa-paper-plane me-2"></i>Send Class Notification</h5>
                    <p class="small mb-0 opacity-80">Broadcast emails to students enrolled in specific modules</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('class.notification.send') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE</label>
                            <select class="form-select py-2 rounded-3 border" name="module_id" id="notify_module_id" required>
                                <option value="" selected disabled>Select Course...</option>
                                @foreach($allCourses as $id => $course)
                                    <option value="{{ $id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS DATE (OPTIONAL)</label>
                            <input type="date" name="class_date" class="form-control py-2 rounded-3 border">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">MEETING LINK (OPTIONAL)</label>
                            <input type="url" name="meeting_link" class="form-control py-2 rounded-3 border" placeholder="https://zoom.us/j/...">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">EMAIL SUBJECT / TOPIC</label>
                            <input type="text" name="subject" class="form-control py-2 rounded-3 border" required placeholder="e.g. Tomorrow's Class Link & Prep">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">MESSAGE BODY</label>
                            <textarea name="message" class="form-control py-2 rounded-3 border" rows="5" required placeholder="Write your announcement here..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-muted mb-0">SELECT RECIPIENTS</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllStudents">
                                    <label class="form-check-label small fw-bold" for="selectAllStudents">Select All</label>
                                </div>
                            </div>
                            <div id="students_list_container" class="border rounded-3 p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <p class="text-muted small text-center mb-0 py-3">Please select a module first to see students.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" id="sendNotificationBtn" disabled style="background: #4F46E5; border: none;">
                        <i class="fa-solid fa-paper-plane me-2"></i>Send Email to Selected
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.sm-page {
    padding: 2rem;
    background-color: #F8FAFC;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Hero Banner */
.sm-hero-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}
.sm-hero-icon {
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
.sm-hero-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.3px;
}
.sm-hero-sub {
    font-size: 0.85rem;
    color: #64748B;
}
.sm-btn-primary {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF !important;
    border: none;
    border-radius: 50px;
    padding: 0.65rem 1.25rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.28);
    transition: all 0.2s ease;
}
.sm-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.38);
}
.sm-btn-secondary {
    display: inline-flex;
    align-items: center;
    background: #FFFFFF;
    color: #4F46E5 !important;
    border: 1.5px solid #C7D2FE;
    border-radius: 50px;
    padding: 0.65rem 1.25rem;
    font-size: 0.84rem;
    font-weight: 700;
    transition: all 0.2s ease;
}
.sm-btn-secondary:hover {
    background: #EEF2FF;
    border-color: #4F46E5;
}

/* KPI Stats Grid */
.sm-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
.sm-stat-box {
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
.sm-stat-box:hover, .sm-stat-box.active-stat {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    border-color: #4F46E5;
}
.sm-stat-icon {
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
.bg-amber-subtle { background: #FFFBEB; }
.text-amber { color: #D97706; }
.bg-rose-subtle { background: #FFE4E6; }
.text-rose { color: #E11D48; }

.sm-stat-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    display: block;
}
.sm-stat-lbl {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    margin-top: 0.25rem;
    display: block;
}

/* Toolbar & Nav Pills */
.sm-toolbar {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.sm-nav-pills {
    display: inline-flex;
    background: #F1F5F9;
    padding: 4px;
    border-radius: 12px;
    gap: 4px;
}
.sm-pill {
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
.sm-pill.active {
    background: #FFFFFF;
    color: #4F46E5;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.sm-pill-badge {
    font-size: 0.7rem;
    padding: 2px 7px;
    border-radius: 50px;
    background: #F1F5F9;
    color: #475569;
}
.sm-pill.active .sm-pill-badge {
    background: #EEF2FF;
    color: #4F46E5;
}

/* Cards Grid */
.sm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 1.25rem;
}
.sm-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    transition: all 0.25s ease;
}
.sm-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.07);
    border-color: #CBD5E1;
}

.sm-card-top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}
.sm-avatar {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #FFFFFF;
    font-weight: 800;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
}
.sm-name {
    font-size: 0.98rem;
    font-weight: 800;
    color: #0F172A;
}
.sm-email {
    font-size: 0.8rem;
    color: #64748B;
}
.sm-meta {
    font-size: 0.75rem;
    color: #64748B;
}

.sm-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
}
.sm-badge-approved { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.sm-badge-partial { background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; }
.sm-badge-review { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
.sm-badge-pending { background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; }

.sm-courses-row {
    background: #F8FAFC;
    border: 1px solid #F1F5F9;
    border-radius: 14px;
    padding: 0.85rem;
    margin-bottom: 1rem;
}
.sm-courses-label {
    font-size: 0.75rem;
    font-weight: 800;
    color: #475569;
    display: block;
}
.sm-courses-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.sm-course-pill {
    padding: 0.25rem 0.65rem;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
}
.sm-pill-active { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.sm-pill-completed { background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; }
.sm-pill-dropped { background: #FEF2F2; color: #DC2626; border: 1px solid #FCA5A5; }
.sm-pill-pending { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }

.sm-card-foot {
    margin-top: auto;
    padding-top: 0.85rem;
    border-top: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}
.sm-amount-label {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748B;
    display: block;
}
.sm-amount-val {
    font-size: 0.92rem;
    font-weight: 800;
    color: #4F46E5;
}

.sm-slip-btn {
    background: #FFFFFF;
    color: #475569 !important;
    border: 1px solid #CBD5E1;
    border-radius: 50px;
    padding: 0.35rem 0.8rem;
    font-size: 0.75rem;
    font-weight: 700;
    transition: all 0.2s ease;
}
.sm-slip-btn:hover { background: #F1F5F9; color: #0F172A !important; }
.sm-no-slip { font-size: 0.72rem; color: #94A3B8; font-style: italic; }

.sm-action-btn {
    border: none;
    border-radius: 50px;
    padding: 0.45rem 1rem;
    font-size: 0.76rem;
    font-weight: 800;
    color: #FFFFFF;
    transition: all 0.2s ease;
}
.sm-approve-btn { background: linear-gradient(135deg, #4F46E5, #6366F1); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
.sm-add-btn { background: linear-gradient(135deg, #059669, #10B981); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); }
.sm-enrolled { background: #EEF2FF; color: #4F46E5; border: 1px solid #C7D2FE; }

@media(max-width: 991.98px) {
    .sm-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 575.98px) {
    .sm-stats-grid { grid-template-columns: 1fr; }
    .sm-page { padding: 1rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewModal = document.getElementById('reviewModal');
    if (reviewModal) {
        reviewModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('modalRegId').value = btn.dataset.id;
            document.getElementById('modalName').textContent = btn.dataset.name;
            document.getElementById('modalEmail').textContent = btn.dataset.email;
            document.getElementById('modalPhone').textContent = btn.dataset.phone;
            document.getElementById('modalInst').textContent = btn.dataset.institution;
            document.getElementById('modalAmount').textContent = 'PKR ' + Number(btn.dataset.amount).toLocaleString();

            // Populate Slips
            const slipsContainer = document.getElementById('slipsContainer');
            const slips = JSON.parse(btn.dataset.slips || '[]');
            if(slips.length === 0) {
                slipsContainer.innerHTML = '<span class="text-muted small italic">No payment slip uploaded yet</span>';
            } else {
                slipsContainer.innerHTML = slips.map((s, idx) => `
                    <a href="${s.url}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="fa-solid fa-receipt me-1"></i>Slip #${idx+1} (${s.date})
                    </a>
                `).join('');
            }

            // Populate Courses
            const coursesContainer = document.getElementById('coursesContainer');
            const courses = JSON.parse(btn.dataset.courses || '[]');
            const enrolledIds = JSON.parse(btn.dataset.enrolledIds || '[]').map(Number);

            if(courses.length === 0) {
                coursesContainer.innerHTML = '<span class="text-muted small italic">No modules selected</span>';
            } else {
                coursesContainer.innerHTML = courses.map(c => {
                    const isEnrolled = enrolledIds.includes(Number(c.id));
                    return `
                        <div class="form-check p-2 rounded-3 border ${isEnrolled ? 'bg-light opacity-75' : 'bg-white'}">
                            <input class="form-check-input" type="checkbox" name="approved_courses[]" value="${c.id}" id="mCourse${c.id}" ${isEnrolled ? 'checked disabled' : 'checked'}>
                            <label class="form-check-label small fw-bold text-dark d-flex justify-content-between" for="mCourse${c.id}">
                                <span>${c.title} ${isEnrolled ? '<span class="badge bg-success ms-1">Enrolled</span>' : ''}</span>
                                <span class="text-primary">PKR ${Number(c.price).toLocaleString()}</span>
                            </label>
                        </div>
                    `;
                }).join('');
            }
        });
    }

    const passwordModal = document.getElementById('passwordModal');
    if(passwordModal) {
        passwordModal.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('pwdModalEmail').value = btn.dataset.email;
            document.getElementById('pwdModalSub').textContent = 'For student: ' + btn.dataset.name;
        });
    }

    const notifyModuleSelect = document.getElementById('notify_module_id');
    const studentContainer   = document.getElementById('students_list_container');
    const selectAllCheckbox  = document.getElementById('selectAllStudents');
    const sendBtn            = document.getElementById('sendNotificationBtn');

    if (notifyModuleSelect) {
        notifyModuleSelect.addEventListener('change', function() {
            const moduleId = this.value;
            studentContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
            
            fetch(`/teacher/module/${moduleId}/students`)
                .then(res => {
                    if(!res.ok) throw new Error('Server returned an error');
                    return res.json();
                })
                .then(students => {
                    if(students.length === 0) {
                        studentContainer.innerHTML = '<p class="text-muted small text-center mb-0 py-3">No students enrolled in this module yet.</p>';
                        sendBtn.disabled = true;
                    } else {
                        let html = '<div class="row g-2">';
                        students.forEach(s => {
                            html += `
                                <div class="col-md-6">
                                    <div class="form-check p-2 rounded bg-white border">
                                        <input class="form-check-input student-checkbox ms-1" type="checkbox" name="student_ids[]" value="${s.id}" id="std${s.id}">
                                        <label class="form-check-label small d-block ms-4" for="std${s.id}">
                                            <span class="fw-bold d-block">${s.name}</span>
                                            <span class="text-muted" style="font-size: 0.7rem;">${s.email}</span>
                                        </label>
                                    </div>
                                </div>`;
                        });
                        html += '</div>';
                        studentContainer.innerHTML = html;
                        sendBtn.disabled = false;
                        selectAllCheckbox.checked = false;
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    studentContainer.innerHTML = '<p class="text-danger small text-center mb-0 py-3">Error loading students.</p>';
                    sendBtn.disabled = true;
                });
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endsection
