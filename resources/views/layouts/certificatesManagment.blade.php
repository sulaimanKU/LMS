@extends('applayouts.app')

@section('contents')
<div class="cm-page">

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
    <div class="cm-hero-card mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="cm-hero-icon">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <h4 class="cm-hero-title mb-1">Certificate Management Hub</h4>
                        <p class="cm-hero-sub mb-0">Issue, audit, and manage official course completion certificates for all enrolled students</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 text-lg-end">
                <div class="cm-header-chip d-inline-flex align-items-center">
                    <i class="fa-solid fa-shield-halved me-2 text-warning"></i>Verified Issuance System
                </div>
            </div>
        </div>
    </div>

    {{-- ── Executive KPI Stat Cards ── --}}
    <div class="cm-stats-grid mb-4">
        <div class="cm-stat-box">
            <div class="cm-stat-icon-wrap bg-indigo-subtle text-indigo">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="cm-stat-content">
                <span class="cm-stat-val">{{ $totalRecords ?? 0 }}</span>
                <span class="cm-stat-lbl">Enrolled Records</span>
            </div>
        </div>

        <div class="cm-stat-box">
            <div class="cm-stat-icon-wrap bg-emerald-subtle text-emerald">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="cm-stat-content">
                <span class="cm-stat-val">{{ $issuedCount ?? 0 }}</span>
                <span class="cm-stat-lbl">Certificates Issued</span>
            </div>
        </div>

        <div class="cm-stat-box">
            <div class="cm-stat-icon-wrap bg-amber-subtle text-amber">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="cm-stat-content">
                <span class="cm-stat-val">{{ $pendingCount ?? 0 }}</span>
                <span class="cm-stat-lbl">Pending Issuance</span>
            </div>
        </div>

        <div class="cm-stat-box">
            <div class="cm-stat-icon-wrap bg-purple-subtle text-purple">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="cm-stat-content">
                <span class="cm-stat-val">{{ $completedCount ?? 0 }}</span>
                <span class="cm-stat-lbl">Completed Courses</span>
            </div>
        </div>
    </div>

    {{-- ── Filter & Search Toolbar ── --}}
    <div class="cm-toolbar mb-4">
        <form action="{{ route('admin.certificates.management') }}" method="GET" id="cmFilterForm" class="m-0">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="input-group shadow-sm" style="border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                        <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-0 py-2 fw-medium text-dark shadow-none" placeholder="Search student name or email address..." value="{{ $search ?? '' }}" style="font-size: 0.85rem;">
                        @if($search)
                            <a href="{{ route('admin.certificates.management', array_filter(['module_id' => $selectedModuleId])) }}" class="input-group-text bg-white border-0 text-muted px-3" title="Clear Search">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-5">
                    <div class="input-group shadow-sm" style="border-radius: 12px; border: 1.5px solid #cbd5e1; background: #fff; overflow: hidden;">
                        <span class="input-group-text bg-white border-0 ps-3 text-primary"><i class="fa-solid fa-layer-group"></i></span>
                        <select name="module_id" class="form-select border-0 py-2 fw-semibold text-dark shadow-none" onchange="this.form.submit()" style="font-size: 0.85rem; cursor: pointer;">
                            <option value="">All Courses & Workshops</option>
                            @foreach($allModules as $mod)
                                <option value="{{ $mod->id }}" {{ $selectedModuleId == $mod->id ? 'selected' : '' }}>
                                    {{ $mod->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Main Certificate Roster Card ── --}}
    <div class="cm-card">
        <div class="cm-card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-extrabold text-dark mb-0">
                        @if($selectedModuleId && $allModules->find($selectedModuleId))
                            Students Enrolled in <span class="text-primary">{{ $allModules->find($selectedModuleId)->title }}</span>
                        @else
                            All Student Certificate Records
                        @endif
                    </h6>
                    <span class="text-muted small">Select any student below to upload or manage their certificate</span>
                </div>
                <span class="cm-badge-count">
                    Total {{ $users->total() }} Record(s) — Showing {{ $users->count() }} on Page {{ $users->currentPage() }}
                </span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table cm-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Student Profile</th>
                        <th>Email Address</th>
                        <th>Enrolled Module</th>
                        <th class="text-center">Course Status</th>
                        <th class="text-center">Certificate Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    @if(isset($item->student->profile_image) && $item->student->profile_image)
                                        <img src="{{ asset('storage/' . $item->student->profile_image) }}" class="cm-avatar-img" alt="{{ $item->student->name }}">
                                    @else
                                        <div class="cm-avatar-placeholder">
                                            {{ strtoupper(substr($item->student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="cm-student-name">{{ $item->student->name }}</span>
                                        <span class="text-muted extra-small">ID #{{ $item->student->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="text-dark font-medium">
                                <i class="fa-solid fa-envelope me-2 text-muted"></i>{{ $item->student->email }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="cm-cat-chip">{{ $item->module->category }}</span>
                                    <span class="fw-bold text-dark">{{ $item->module->title }}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                @if($item->status == 'completed')
                                    <span class="cm-chip cm-chip-completed">
                                        <i class="fa-solid fa-graduation-cap me-1"></i>Completed
                                    </span>
                                @elseif($item->status == 'active')
                                    <span class="cm-chip cm-chip-active">
                                        <i class="fa-solid fa-play me-1"></i>Active
                                    </span>
                                @else
                                    <span class="cm-chip cm-chip-muted">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($item->certificate)
                                    <span class="cm-chip cm-chip-issued">
                                        <span class="cm-dot"></span>Issued (Active)
                                    </span>
                                @else
                                    <span class="cm-chip cm-chip-pending">
                                        <span class="cm-dot"></span>Not Issued
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @if($item->certificate)
                                        {{-- View Certificate --}}
                                        <a href="{{ asset('storage/' . $item->certificate->certificate_path) }}" target="_blank" class="cm-action-icon view-icon" title="View Certificate Document">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        {{-- Delete Certificate --}}
                                        <form action="{{ route('admin.student.certificate.delete', $item->certificate->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete this certificate? It will be permanently removed from student dashboard, database and storage.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cm-action-icon delete-icon" title="Delete Certificate">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Assign / Re-upload Certificate --}}
                                    <button class="cm-btn-upload" 
                                            data-bs-toggle="modal" data-bs-target="#assignCertificateModal"
                                            data-userid="{{ $item->student->id }}"
                                            data-username="{{ $item->student->name }}"
                                            data-moduleid="{{ $item->module->id }}"
                                            data-moduletitle="{{ $item->module->title }}">
                                        <i class="fa-solid fa-file-arrow-up me-1"></i> {{ $item->certificate ? 'Re-upload' : 'Upload Cert' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4 text-center">
                                    <i class="fa-solid fa-award fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                    <h6 class="fw-bold text-dark mb-1">No Student Certificate Records Found</h6>
                                    <p class="text-muted small mb-0">Try clearing your search query or selecting a different course filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

{{-- ── MODAL — Upload / Assign Certificate ── --}}
<div class="modal fade" id="assignCertificateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: #fff;">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #1E1B4B, #4F46E5);">
                <div>
                    <h5 class="modal-title fw-extrabold mb-1" id="modalHeaderTitle"><i class="fa-solid fa-award me-2"></i>Upload Student Certificate</h5>
                    <p class="small mb-0 opacity-80">Target: <strong id="certStudentName">-</strong></p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.student.certificate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" id="certUserId">
                <input type="hidden" name="module_id" id="certModuleId">

                <div class="modal-body p-4">
                    <div class="cm-modal-info-box mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="cm-modal-badge-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <span class="cm-modal-lbl">Enrolled Module</span>
                                <h6 class="fw-bold text-dark mb-0" id="certModuleName">-</h6>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Select Certificate File (PDF / Image)</label>
                        <input type="file" name="certificate" class="form-control py-2 rounded-3 border" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted mt-1 d-block" style="font-size: 11px;">Supported formats: PDF, JPG, PNG (Max 10MB)</small>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cm-btn-modal-submit">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Publish Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.cm-page {
    padding: 2rem;
    background-color: #F8FAFC;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* ── Hero Card ── */
.cm-hero-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.5rem 1.75rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}
.cm-hero-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.3);
    flex-shrink: 0;
}
.cm-hero-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.3px;
}
.cm-hero-sub {
    font-size: 0.85rem;
    color: #64748B;
}
.cm-header-chip {
    background: #FFFBEB;
    color: #D97706;
    border: 1px solid #FDE68A;
    padding: 0.55rem 1.1rem;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 800;
}

/* ── KPI Stats Grid ── */
.cm-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
.cm-stat-box {
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
.cm-stat-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    border-color: #CBD5E1;
}
.cm-stat-icon-wrap {
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
.bg-purple-subtle { background: #F3E8FF; }
.text-purple { color: #9333EA; }

.cm-stat-val {
    font-size: 1.45rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1;
    display: block;
}
.cm-stat-lbl {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    margin-top: 0.25rem;
    display: block;
}

/* ── Toolbar ── */
.cm-toolbar {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 1rem 1.25rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}

/* ── Main Card & Table ── */
.cm-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}
.cm-card-header {
    padding: 1.25rem 1.5rem;
    background: #FAFAFC;
    border-bottom: 1px solid #F1F5F9;
}
.cm-badge-count {
    background: #EEF2FF;
    color: #4F46E5;
    border: 1px solid #C7D2FE;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 800;
}

.cm-table thead th {
    background: #F8FAFC;
    color: #64748B;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
}
.cm-table tbody td {
    padding: 1.15rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.cm-table tbody tr:hover {
    background-color: #F8FAFC;
}

.cm-avatar-img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #E2E8F0;
}
.cm-avatar-placeholder {
    width: 42px;
    height: 42px;
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
.cm-student-name {
    font-size: 0.92rem;
    font-weight: 800;
    color: #0F172A;
    display: block;
}

.cm-cat-chip {
    background: #F1F5F9;
    color: #475569;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 700;
}

.cm-chip {
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.cm-chip-completed { background: #EEF2FF; color: #4F46E5; }
.cm-chip-active { background: #ECFDF5; color: #059669; }
.cm-chip-muted { background: #F1F5F9; color: #64748B; }

.cm-chip-issued { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.cm-chip-pending { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }

.cm-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.cm-action-icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    text-decoration: none;
    border: 1px solid #E2E8F0;
    background: #FFFFFF;
    transition: all 0.2s ease;
}
.view-icon { color: #4F46E5 !important; }
.view-icon:hover { background: #EEF2FF; border-color: #C7D2FE; transform: translateY(-1px); }

.delete-icon { color: #EF4444 !important; }
.delete-icon:hover { background: #FEF2F2; border-color: #FCA5A5; transform: translateY(-1px); }

.cm-btn-upload {
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF !important;
    border: none;
    border-radius: 50px;
    padding: 0.45rem 1.1rem;
    font-size: 0.78rem;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    transition: all 0.2s ease;
}
.cm-btn-upload:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
}

/* ── Modal Details ── */
.cm-modal-info-box {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 1rem;
}
.cm-modal-badge-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.cm-modal-lbl {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    display: block;
}
.cm-btn-modal-submit {
    background: linear-gradient(135deg, #4F46E5, #6366F1);
    color: #FFFFFF;
    border: none;
    border-radius: 50px;
    padding: 0.65rem 1.5rem;
    font-size: 0.85rem;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.28);
    transition: all 0.2s ease;
}
.cm-btn-modal-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.38);
}

@media(max-width: 991.98px) {
    .cm-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 575.98px) {
    .cm-stats-grid { grid-template-columns: 1fr; }
    .cm-page { padding: 1rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const certModal = document.getElementById('assignCertificateModal');
    if (certModal) {
        certModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            certModal.querySelector('#certUserId').value = btn.dataset.userid;
            certModal.querySelector('#certModuleId').value = btn.dataset.moduleid;
            certModal.querySelector('#certStudentName').textContent = btn.dataset.username;
            certModal.querySelector('#certModuleName').textContent = btn.dataset.moduletitle;
        });
    }
});
</script>
@endsection
