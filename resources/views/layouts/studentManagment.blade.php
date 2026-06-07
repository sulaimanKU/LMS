@extends('applayouts.app')

@section('contents')
<div class="sm-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-info me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 mt-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="sm-header">
        <div>
            <h5 class="sm-title">Student Registrations</h5>
            <p class="sm-subtitle">
                {{ $registrations->total() }} total &nbsp;·&nbsp;
                Page {{ $registrations->currentPage() }} of {{ $registrations->lastPage() }}
            </p>
        </div>

        {{-- Status filter tabs (server-side) --}}
        <div class="sm-tab-group">
            <a class="sm-tab {{ $filter === 'all'      ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all',      'page' => 1]) }}">All</a>
            <a class="sm-tab {{ $filter === 'pending'  ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'pending',  'page' => 1]) }}">Pending</a>
            <a class="sm-tab {{ $filter === 'approved' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'approved', 'page' => 1]) }}">Approved</a>
        </div>
    </div>

    {{-- ── Cards grid ── --}}
    <div class="sm-grid">
        @forelse($registrations as $reg)
        @php
            $hasSlip       = $reg->slips->isNotEmpty();
            $courseIds     = array_map('intval', $reg->selected_courses ?? []);
            $courseCount   = count($courseIds);
            $enrolledMap   = $enrolledByEmail[$reg->email] ?? collect();
            $enrolledIds   = $enrolledMap->keys()->toArray();
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
                    <p class="sm-name">{{ $reg->name }}</p>
                    <p class="sm-email">{{ $reg->email }}</p>
                    <p class="sm-meta">{{ $reg->phone }} &nbsp;·&nbsp; {{ $reg->institution }}</p>
                </div>
                <div class="ms-auto">
                    @if($allEnrolled)
                        <span class="sm-badge sm-badge-approved"><i class="fa-solid fa-graduation-cap me-1"></i>Fully Enrolled</span>
                    @elseif($partial)
                        <span class="sm-badge sm-badge-partial"><i class="fa-solid fa-circle-half-stroke me-1"></i>{{ $enrolledCount }}/{{ $courseCount }} Enrolled</span>
                    @elseif($hasSlip)
                        <span class="sm-badge sm-badge-review"><i class="fa-solid fa-clock me-1"></i>Review</span>
                    @else
                        <span class="sm-badge sm-badge-pending"><i class="fa-solid fa-hourglass me-1"></i>No Slip</span>
                    @endif
                </div>
            </div>

            {{-- Modules row --}}
            <div class="sm-courses-row">
                <span class="sm-courses-label">
                    <i class="fa-solid fa-book-open me-1"></i>Modules ({{ $enrolledCount }}/{{ $courseCount }} enrolled)
                </span>
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
                                        {{ Str::limit($allCourses[$cid]->title, 20) }}
                                    </button>
                                    <ul class="dropdown-menu shadow-sm border-0" style="font-size: .8rem; min-width: 140px;">
                                        <li><h6 class="dropdown-header">Update Status</h6></li>
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
                                    {{ Str::limit($allCourses[$cid]->title, 20) }}
                                </span>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Amount + slip + action --}}
            <div class="sm-card-foot">
                <div class="sm-amount">
                    <span class="sm-amount-label">Total Registered</span>
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
                        <a href="{{ $latestSlipUrl }}" target="_blank" class="sm-slip-btn" title="View latest payment slip">
                            <i class="fa-solid fa-receipt me-1"></i>Slip
                        </a>
                    @else
                        <span class="sm-no-slip">No slip yet</span>
                    @endif

                    @if($allEnrolled)
                        <button class="sm-action-btn sm-enrolled" disabled>
                            <i class="fa-solid fa-graduation-cap me-1"></i>Fully Enrolled
                        </button>
                    @elseif($partial && $hasSlip)
                        {{-- Some courses enrolled, new slip uploaded — allow adding remaining --}}
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
                                data-has-account="true">
                            <i class="fa-solid fa-circle-plus me-1"></i>Add Remaining Courses
                        </button>
                    @elseif($partial)
                        <button class="sm-action-btn sm-waiting" disabled>
                            <i class="fa-solid fa-clock me-1"></i>Awaiting Slip for Rest
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
                                data-has-account="false">
                            <i class="fa-solid fa-user-check me-1"></i>Review & Approve
                        </button>
                    @else
                        {{-- MANUAL APPROVAL (No Slip) --}}
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
                                data-has-account="false">
                            <i class="fa-solid fa-user-plus me-1"></i>Manual Approve
                        </button>
                    @endif

                    {{-- DELETE BUTTON --}}
                    <form action="{{ route('admin.registration.delete', $reg->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to PERMANENTLY delete this registration and the associated user account? This cannot be undone.')" 
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="sm-action-btn" style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @empty
        <div class="sm-empty">
            <i class="fa-solid fa-users-slash"></i>
            <p>No registrations found.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($registrations->hasPages())
    <div class="sm-pagination">
        {{ $registrations->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

{{-- ═══════════════════════════════════════════
     MODAL — Review & Approve
═══════════════════════════════════════════ --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:580px;">
        <div class="modal-content sm-modal">

            <div class="sm-modal-header">
                <div>
                    <h5 class="sm-modal-title"><i class="fa-solid fa-user-check me-2"></i>Review & Approve</h5>
                    <p class="sm-modal-sub" id="reviewSubtitle">Reviewing student application</p>
                </div>
                <button type="button" class="sm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="reviewForm" action="" method="POST">
                @csrf
                <div class="sm-modal-body">

                    {{-- How-to banner --}}
                    <div class="sm-howto">
                        <span class="sm-howto-step">1</span>
                        <span>Check payment (Slip or WhatsApp)</span>
                        <span class="sm-howto-sep">→</span>
                        <span class="sm-howto-step">2</span>
                        <span><strong>Tick</strong> module(s) paid for</span>
                        <span class="sm-howto-sep">→</span>
                        <span class="sm-howto-step">3</span>
                        <span>Click Approve</span>
                    </div>

                    {{-- Student info --}}
                    <div class="sm-review-info" id="reviewStudentInfo"></div>

                    {{-- Payment slip link --}}
                    <div class="sm-review-slip" id="reviewSlipRow"></div>

                    {{-- Course selection --}}
                    <div class="sm-review-section">
                        <p class="sm-review-section-title">
                            <i class="fa-solid fa-check-square me-2 text-primary"></i>
                            Select modules to enroll
                        </p>
                        <div id="reviewCourseList" class="sm-course-check-grid"></div>
                    </div>

                    {{-- Live payment tracker --}}
                    <div class="sm-pay-tracker" id="payTracker">
                        <div class="sm-pay-tracker-row">
                            <div>
                                <p class="sm-pay-label">Selected modules</p>
                                <p class="sm-pay-selected" id="paySelected">0 of 0</p>
                            </div>
                            <div style="text-align:right;">
                                <p class="sm-pay-label">Selected amount</p>
                                <p class="sm-pay-amount" id="payAmount">PKR 0</p>
                            </div>
                        </div>
                        <div class="sm-pay-bar-wrap">
                            <div class="sm-pay-bar" id="payBar" style="width:0%"></div>
                        </div>
                        <p class="sm-pay-hint" id="payHint"></p>
                    </div>

                </div>

                <div class="sm-modal-footer">
                    <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sm-btn-approve" id="approveBtn">
                        <i class="fa-solid fa-user-check me-2" id="approveBtnIcon"></i>
                        <span id="approveBtnText">Approve & Send Credentials</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL — Reset Password
═══════════════════════════════════════════ --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content sm-modal">

            <div class="sm-modal-header" style="background: linear-gradient(135deg, #475569, #1E293B);">
                <div>
                    <h5 class="sm-modal-title"><i class="fa-solid fa-key me-2"></i>Reset Password</h5>
                    <p class="sm-modal-sub" id="passwordSubtitle">Updating student credentials</p>
                </div>
                <button type="button" class="sm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.user.updatePassword') }}" method="POST">
                @csrf
                <div class="sm-modal-body" style="gap: 0.75rem;">
                    <input type="hidden" name="email" id="passwordEmail">
                    
                    <div class="mb-2">
                        <label class="sm-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="tm-input" style="padding-left: 1rem;" required minlength="8" placeholder="Enter new password">
                    </div>
                    
                    <div class="mb-2">
                        <label class="sm-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="tm-input" style="padding-left: 1rem;" required minlength="8" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="sm-modal-footer">
                    <button type="button" class="sm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="sm-btn-approve" style="background: linear-gradient(135deg, #475569, #1E293B);">
                        <i class="fa-solid fa-save me-2"></i>Update Password
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* ── Page ── */
.sm-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.sm-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap;
    gap: 1rem; margin-bottom: 1.75rem;
}
.sm-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.sm-subtitle { font-size: .8rem; color: #94A3B8; margin: .15rem 0 0; }

/* ── Filter tabs ── */
.sm-tab-group {
    display: flex; gap: 4px;
    background: #EEF2FF; padding: 4px; border-radius: 10px;
}
.sm-tab {
    padding: .35rem .9rem; border-radius: 7px; border: none;
    background: transparent; font-size: .8rem; font-weight: 600;
    color: #64748B; cursor: pointer; transition: all .15s;
    text-decoration: none;
}
.sm-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.sm-tab:hover:not(.active) { background: rgba(255,255,255,.6); }

/* ── Cards grid ── */
.sm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(460px, 1fr));
    gap: 1rem;
}
.sm-card {
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    overflow: hidden;
    transition: box-shadow .2s, transform .2s;
}
.sm-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
.sm-card[data-status="approved"] { border-color: #D1FAE5; }

/* ── Card top ── */
.sm-card-top {
    display: flex; align-items: flex-start;
    gap: 12px; padding: 1.1rem 1.2rem .75rem;
}
.sm-avatar {
    width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; font-size: .9rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
}
.sm-info { min-width: 0; flex: 1; }
.sm-name  { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; line-height: 1.3; }
.sm-email { font-size: .77rem; color: #64748B; margin: .1rem 0 .1rem; }
.sm-meta  { font-size: .72rem; color: #94A3B8; margin: 0; }

/* ── Badges ── */
.sm-badge {
    display: inline-flex; align-items: center;
    padding: .25rem .75rem; border-radius: 50px;
    font-size: .72rem; font-weight: 700; white-space: nowrap;
}
.sm-badge-approved { background: #D1FAE5; color: #065F46; }
.sm-badge-partial  { background: #DBEAFE; color: #1D4ED8; }
.sm-badge-review   { background: #FEF3C7; color: #92400E; }
.sm-badge-pending  { background: #FEE2E2; color: #991B1B; }

/* ── Modules row ── */
.sm-courses-row {
    padding: .6rem 1.2rem;
    background: #F8FAFF;
    border-top: 1px solid #F1F5F9;
    border-bottom: 1px solid #F1F5F9;
}
.sm-courses-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #94A3B8; display: block; margin-bottom: .4rem; }
.sm-courses-list  { display: flex; flex-wrap: wrap; gap: 5px; }
.sm-course-pill {
    padding: .18rem .6rem; border-radius: 50px;
    font-size: .72rem; font-weight: 600;
    display: inline-flex; align-items: center;
}
.sm-pill-active    { background: #D1FAE5; color: #065F46; }
.sm-pill-completed { background: #DBEAFE; color: #1D4ED8; }
.sm-pill-dropped   { background: #FEE2E2; color: #991B1B; }
.sm-pill-pending   { background: #FEF3C7; color: #92400E; }
.sm-pill-enrolled  { background: #D1FAE5; color: #065F46; }

/* ── Card foot ── */
.sm-card-foot {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: .75rem; padding: .85rem 1.2rem;
}
.sm-amount-label { font-size: .68rem; text-transform: uppercase; letter-spacing: .6px; color: #94A3B8; display: block; }
.sm-amount-val   { font-size: 1rem; font-weight: 800; color: #1E293B; }

.sm-slip-btn {
    display: inline-flex; align-items: center;
    background: #EEF2FF; color: #4F46E5;
    border: 1px solid #C7D2FE; border-radius: 7px;
    padding: .38rem .75rem; font-size: .78rem; font-weight: 600;
    text-decoration: none; transition: all .15s;
}
.sm-slip-btn:hover { background: #4F46E5; color: #fff; }
.sm-no-slip { font-size: .75rem; color: #CBD5E1; font-style: italic; }

.sm-action-btn {
    display: inline-flex; align-items: center;
    padding: .42rem .9rem; border-radius: 8px; border: none;
    font-size: .8rem; font-weight: 600; cursor: pointer; transition: all .2s;
}
.sm-approve-btn {
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; box-shadow: 0 3px 10px rgba(79,70,229,.25);
}
.sm-approve-btn:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(79,70,229,.36); }
.sm-add-btn {
    background: linear-gradient(135deg,#0891B2,#0E7490);
    color: #fff; box-shadow: 0 3px 10px rgba(8,145,178,.25);
}
.sm-add-btn:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(8,145,178,.36); }
.sm-enrolled { background: #D1FAE5; color: #065F46; cursor: default; }
.sm-waiting  { background: #F1F5F9; color: #94A3B8; cursor: default; }

/* ── Empty ── */
.sm-empty {
    grid-column: 1/-1;
    text-align: center; padding: 4rem 1rem;
    color: #CBD5E1;
}
.sm-empty i { font-size: 3rem; display: block; margin-bottom: .75rem; }
.sm-empty p { font-size: .9rem; margin: 0; }

/* ── Pagination ── */
.sm-pagination { margin-top: 1.5rem; display: flex; justify-content: center; }
.sm-pagination .pagination { margin: 0; }
.sm-pagination .page-link { color: #4F46E5; border-color: #E2E8F0; font-size: .82rem; }
.sm-pagination .page-item.active .page-link { background: #4F46E5; border-color: #4F46E5; }

/* ── Modal ── */
.sm-modal { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.12); }
.sm-modal-header {
    display: flex; align-items: flex-start;
    justify-content: space-between;
    padding: 1.3rem 1.5rem 1rem;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
}
.sm-modal-title { font-size: .975rem; font-weight: 700; color: #fff; margin: 0; }
.sm-modal-sub   { font-size: .78rem; color: rgba(255,255,255,.75); margin: .2rem 0 0; }
.sm-modal-close {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .8rem; transition: background .15s;
}
.sm-modal-close:hover { background: rgba(255,255,255,.28); }
.sm-modal-body   { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .9rem; max-height: 70vh; overflow-y: auto; }
.sm-modal-footer { padding: 1rem 1.5rem 1.3rem; display: flex; justify-content: flex-end; gap: .6rem; border-top: 1px solid #F1F5F9; }

/* ── How-to banner ── */
.sm-howto {
    display: flex; align-items: center; flex-wrap: wrap; gap: 6px;
    background: #EEF2FF; border: 1px solid #C7D2FE;
    border-radius: 10px; padding: .65rem 1rem;
    font-size: .78rem; color: #3730A3;
}
.sm-howto-step {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: #4F46E5; color: #fff; font-size: .7rem; font-weight: 800; flex-shrink: 0;
}
.sm-howto-sep { color: #A5B4FC; font-weight: 700; }

/* ── Review modal internals ── */
.sm-review-info {
    background: #F8FAFF; border: 1.5px solid #E2E8F0;
    border-radius: 10px; padding: 1rem 1.1rem;
    font-size: .845rem; display: grid;
    grid-template-columns: 1fr 1fr; gap: .4rem .75rem;
}
.sm-ri-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #94A3B8; margin: 0; }
.sm-ri-val   { font-size: .845rem; font-weight: 600; color: #1E293B; margin-bottom: .4rem; }

.sm-review-slip {
    display: flex; align-items: center; gap: 10px;
    padding: .75rem 1rem;
    background: #FFFBEB; border: 1px solid #FCD34D; border-radius: 10px;
    font-size: .845rem;
}
.sm-review-slip a {
    color: #92400E; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;
}

.sm-review-section-title {
    font-size: .82rem; font-weight: 700; color: #1E293B;
    margin: 0 0 .6rem; display: flex; align-items: center; gap: 6px;
}

.sm-course-check-grid { display: flex; flex-direction: column; gap: 6px; }

/* Already-enrolled row in modal */
.sm-course-locked {
    display: flex; align-items: center; gap: 10px;
    padding: .6rem .9rem; border: 1.5px solid #D1FAE5;
    border-radius: 9px; background: #F0FDF4;
}
.sm-locked-icon { color: #16A34A; font-size: .9rem; flex-shrink: 0; }
.sm-enrolled-tag {
    margin-left: auto; font-size: .68rem; font-weight: 700;
    background: #D1FAE5; color: #065F46;
    padding: .15rem .55rem; border-radius: 50px; white-space: nowrap;
}

.sm-course-chk {
    display: flex; align-items: center; gap: 10px;
    padding: .6rem .9rem; border: 1.5px solid #E2E8F0; border-radius: 9px;
    cursor: pointer; transition: border-color .15s, background .15s; user-select: none;
    margin: 0;
}
.sm-course-chk:hover { border-color: #7C3AED; background: #FAF5FF; }
.sm-course-chk.checked { border-color: #4F46E5; background: #EEF2FF; }
.sm-course-chk input { position: absolute; opacity: 0; width: 0; height: 0; }
.sm-chk-box {
    width: 20px; height: 20px; border-radius: 6px; flex-shrink: 0;
    border: 2px solid #CBD5E1; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; color: #fff; transition: all .15s;
}
.sm-course-chk.checked .sm-chk-box {
    background: linear-gradient(135deg,#4F46E5,#7C3AED); border-color: #4F46E5;
}
.sm-chk-label { font-size: .845rem; color: #1E293B; flex: 1; }
.sm-chk-price { font-size: .78rem; font-weight: 700; color: #4F46E5; }

/* ── Payment tracker ── */
.sm-pay-tracker {
    background: #0F0A2E; border-radius: 12px; padding: 1rem 1.1rem;
    display: flex; flex-direction: column; gap: .5rem;
}
.sm-pay-tracker-row { display: flex; justify-content: space-between; align-items: flex-start; }
.sm-pay-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: #6366F1; margin: 0 0 2px; }
.sm-pay-selected { font-size: 1.1rem; font-weight: 800; color: #A5B4FC; margin: 0; }
.sm-pay-amount { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 0; }
.sm-pay-bar-wrap {
    background: rgba(255,255,255,.1); border-radius: 99px; height: 5px; margin-top: .25rem; overflow: hidden;
}
.sm-pay-bar {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg,#6366F1,#8B5CF6);
    transition: width .3s ease, background .3s;
}
.sm-pay-bar.exact { background: #10B981; }
.sm-pay-bar.over  { background: #EF4444; }
.sm-pay-hint { font-size: .75rem; margin: 0; font-weight: 500; color: #94A3B8; }
.sm-pay-hint.match   { color: #34D399; }
.sm-pay-hint.partial { color: #FCD34D; }
.sm-pay-hint.over    { color: #FCA5A5; }

/* ── Modal buttons ── */
.sm-btn-cancel {
    padding: .5rem 1.1rem; border: 1.5px solid #E2E8F0; border-radius: 9px;
    background: #fff; color: #64748B; font-size: .855rem; font-weight: 600; cursor: pointer;
    transition: all .15s;
}
.sm-btn-cancel:hover { background: #F8FAFF; }
.sm-btn-approve {
    padding: .5rem 1.25rem; border: none; border-radius: 9px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; font-size: .855rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
    display: inline-flex; align-items: center;
}
.sm-btn-approve:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }

@media (max-width: 767.98px) {
    .sm-grid { grid-template-columns: 1fr; }
    .sm-howto { font-size: .72rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const reviewModal = document.getElementById('reviewModal');
    if (!reviewModal) return;

    // ── Populate modal on open ──
    reviewModal.addEventListener('show.bs.modal', function (e) {
        const btn      = e.relatedTarget;
        const regId    = btn.dataset.id;
        const name     = btn.dataset.name;
        const email    = btn.dataset.email;
        const phone    = btn.dataset.phone;
        const inst     = btn.dataset.institution;
        const regTotal = parseFloat(btn.dataset.amount) || 0;
        const slips    = JSON.parse(btn.dataset.slips || '[]');
        // courses = [{id, title, price}, ...] — pre-built server-side, no lookup needed
        const courses     = JSON.parse(btn.dataset.courses    || '[]');
        const enrolledIds = JSON.parse(btn.dataset.enrolledIds || '[]');
        const hasAccount  = btn.dataset.hasAccount === 'true';

        // Courses NOT yet enrolled (the ones admin can approve now)
        const pendingCourses = courses.filter(c => !enrolledIds.includes(c.id));

        reviewModal.querySelector('#reviewSubtitle').textContent =
            hasAccount ? 'Adding courses for: ' + name : 'Reviewing: ' + name;
        reviewModal.querySelector('#reviewForm').action =
            '{{ route("admin.approve.student", ":id") }}'.replace(':id', regId);

        // Student info
        reviewModal.querySelector('#reviewStudentInfo').innerHTML =
            `<div><p class="sm-ri-label">Name</p><p class="sm-ri-val">${name}</p></div>
             <div><p class="sm-ri-label">Email</p><p class="sm-ri-val">${email}</p></div>
             <div><p class="sm-ri-label">Phone</p><p class="sm-ri-val">${phone || '—'}</p></div>
             <div><p class="sm-ri-label">Institution</p><p class="sm-ri-val">${inst || '—'}</p></div>
             <div><p class="sm-ri-label">Registered Total</p><p class="sm-ri-val" style="color:#4F46E5">PKR ${regTotal.toLocaleString()}</p></div>
             <div><p class="sm-ri-label">Account Status</p><p class="sm-ri-val" style="color:${hasAccount?'#059669':'#D97706'}">${hasAccount ? '✓ Account Exists' : 'Will be Created'}</p></div>`;

        // Slip row — show ALL slips
        const slipRow = reviewModal.querySelector('#reviewSlipRow');
        if (slips.length === 0) {
            slipRow.innerHTML = `
                <div style="width:100%; padding:.75rem; border-radius:10px; background:#EEF2FF; border:1px solid #C7D2FE; display:flex; align-items:center; gap:.75rem;">
                    <div style="font-size:1.5rem; color:#4F46E5;"><i class="fa-brands fa-whatsapp"></i></div>
                    <div>
                        <p style="margin:0; font-size:.82rem; font-weight:700; color:#1E293B;">Manual WhatsApp Verification</p>
                        <p style="margin:0; font-size:.75rem; color:#64748B;">No slip in system. Please verify via WhatsApp proof before approving.</p>
                    </div>
                </div>`;
        } else {
            const statusColor = s => s === 'approved' ? '#065F46' : '#92400E';
            const statusBg    = s => s === 'approved' ? '#D1FAE5' : '#FEF3C7';
            slipRow.innerHTML = `
                <div style="width:100%;">
                  <p style="font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.6px;color:#92400E;margin:0 0 .5rem;">
                    <i class="fa-solid fa-receipt me-1"></i>${slips.length} Payment Slip${slips.length > 1 ? 's' : ''} — open each to verify
                  </p>
                  ${slips.map((s, i) => `
                    <div style="display:flex;align-items:center;gap:.6rem;padding:.4rem .65rem;border-radius:8px;background:#FFFBEB;border:1px solid #FCD34D;margin-bottom:.35rem;">
                      <span style="font-size:.75rem;font-weight:700;color:#92400E;min-width:52px;">Slip ${i+1}</span>
                      <span style="font-size:.73rem;color:#92400E;flex:1;">${s.date}</span>
                      <span style="font-size:.65rem;font-weight:800;padding:.12rem .45rem;border-radius:50px;background:${statusBg(s.status)};color:${statusColor(s.status)};">${s.status.charAt(0).toUpperCase()+s.status.slice(1)}</span>
                      <a href="${s.url}" target="_blank" style="font-size:.73rem;font-weight:600;color:#4F46E5;text-decoration:none;">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Open
                      </a>
                    </div>
                  `).join('')}
                </div>`;
        }

        // Build course list:
        // Already-enrolled → locked row (no checkbox)
        // Pending → checkboxes for admin to tick
        const list = reviewModal.querySelector('#reviewCourseList');
        let html = '';

        // Show enrolled courses as locked
        courses.filter(c => enrolledIds.includes(c.id)).forEach(c => {
            html += `<div class="sm-course-locked">
                       <span class="sm-locked-icon"><i class="fa-solid fa-circle-check"></i></span>
                       <span class="sm-chk-label">${c.title}</span>
                       <span class="sm-enrolled-tag">Already Enrolled</span>
                     </div>`;
        });

        // Show pending courses as checkboxes
        pendingCourses.forEach(c => {
            html += `<label class="sm-course-chk" data-price="${c.price}">
                       <input type="checkbox" name="approved_courses[]" value="${c.id}">
                       <span class="sm-chk-box"><i class="fa-solid fa-check"></i></span>
                       <span class="sm-chk-label">${c.title}</span>
                       <span class="sm-chk-price">PKR ${Number(c.price).toLocaleString()}</span>
                     </label>`;
        });

        list.innerHTML = html || '<p style="color:#94A3B8;font-size:.82rem;margin:0;">No pending modules.</p>';

        // Approve button label
        const btnText = document.getElementById('approveBtnText');
        const btnIcon = document.getElementById('approveBtnIcon');
        if (hasAccount) {
            btnText.textContent = 'Enroll in Additional Courses';
            btnIcon.className = 'fa-solid fa-circle-plus me-2';
        } else {
            btnText.textContent = 'Approve & Send Credentials';
            btnIcon.className = 'fa-solid fa-user-check me-2';
        }

        // Tracker (only counts pending/checkable courses)
        function updateTracker() {
            let selCount = 0, selAmount = 0;
            list.querySelectorAll('label.sm-course-chk').forEach(lbl => {
                if (lbl.classList.contains('checked')) {
                    selCount++;
                    selAmount += parseFloat(lbl.dataset.price) || 0;
                }
            });
            const total = pendingCourses.length;
            reviewModal.querySelector('#paySelected').textContent = selCount + ' of ' + total + ' pending module(s)';
            reviewModal.querySelector('#payAmount').textContent   = 'PKR ' + selAmount.toLocaleString();

            const bar  = reviewModal.querySelector('#payBar');
            const hint = reviewModal.querySelector('#payHint');
            bar.style.width = (total > 0 ? (selCount / total) * 100 : 0) + '%';
            bar.classList.remove('exact', 'over');
            hint.className = 'sm-pay-hint';

            if (selCount === 0) {
                hint.textContent = 'Open the slip above, then tick the module(s) that were paid for.';
                hint.classList.add('partial');
            } else if (selCount === total) {
                bar.classList.add('exact');
                hint.classList.add('match');
                hint.textContent = 'All ' + total + ' pending module(s) selected.';
            } else {
                hint.classList.add('partial');
                hint.textContent = 'Partial: ' + selCount + ' of ' + total + ' pending module(s) will be enrolled.';
            }
            document.getElementById('approveBtn').disabled = selCount === 0;
        }

        list.querySelectorAll('label.sm-course-chk').forEach(lbl => {
            lbl.addEventListener('click', function (ev) {
                ev.preventDefault();
                this.classList.toggle('checked');
                this.querySelector('input[type=checkbox]').checked = this.classList.contains('checked');
                updateTracker();
            });
        });

        updateTracker();
    });

    // ── Handle Password Modal ──
    const passwordModal = document.getElementById('passwordModal');
    if (passwordModal) {
        passwordModal.addEventListener('show.bs.modal', function (e) {
            const btn   = e.relatedTarget;
            const name  = btn.dataset.name;
            const email = btn.dataset.email;

            passwordModal.querySelector('#passwordSubtitle').textContent = 'Resetting password for: ' + name;
            passwordModal.querySelector('#passwordEmail').value = email;
            
            // Clear previous inputs
            passwordModal.querySelectorAll('input[type=password]').forEach(i => i.value = '');
        });
    }

});
</script>

@endsection
