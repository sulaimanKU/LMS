@extends('applayouts.app')

@section('contents')
<div class="cm-page">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header & Filters --}}
    <div class="cm-header mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-lg-5">
                <h5 class="cm-title"><i class="fa-solid fa-award me-2 text-primary"></i>Certificate Management</h5>
                <p class="cm-subtitle">Assign and manage student certificates for enrolled courses</p>
            </div>
            <div class="col-12 col-lg-7">
                <form action="{{ route('admin.certificates.management') }}" method="GET" id="filterForm">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text bg-white border-0 ps-3"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-0 py-2" placeholder="Search student name or email..." value="{{ $search ?? '' }}" style="font-size: 0.9rem;">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                <span class="input-group-text bg-white border-0 ps-3"><i class="fa-solid fa-layer-group text-primary"></i></span>
                                <select name="module_id" class="form-select border-0 py-2 fw-600" onchange="this.form.submit()" style="font-size: 0.9rem;">
                                    <option value="">All Modules / Courses</option>
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
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">
                        @if($selectedModuleId && $allModules->find($selectedModuleId))
                            Students Enrolled in <span class="text-primary">{{ $allModules->find($selectedModuleId)->title }}</span>
                        @else
                            All Enrolled Students
                        @endif
                    </h6>
                </div>
                <span class="badge bg-light text-primary border rounded-pill px-3 py-2">
                    {{ $enrollmentList->count() }} Record(s) Shown
                </span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="ps-4 py-3">Student Name</th>
                        <th class="py-3">Email Address</th>
                        <th class="py-3">Module / Course</th>
                        <th class="text-center py-3">Course Status</th>
                        <th class="text-center py-3">Certificate</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollmentList as $item)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 700; flex-shrink:0;">
                                        {{ strtoupper(substr($item->student->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $item->student->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted small">{{ $item->student->email }}</td>
                            <td class="py-3">
                                <span class="fw-semibold text-dark">{{ $item->module->title }}</span>
                            </td>
                            <td class="text-center py-3">
                                @if($item->status == 'completed')
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-graduation-cap me-1"></i>Completed
                                    </span>
                                @elseif($item->status == 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-play-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3">
                                @if($item->certificate)
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-circle-check me-1"></i>Assigned
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-clock me-1"></i>Not Issued
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    @if($item->certificate)
                                        <a href="{{ asset('storage/' . $item->certificate->certificate_path) }}" target="_blank" class="btn btn-sm btn-light border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View Certificate">
                                            <i class="fa-solid fa-eye text-primary"></i>
                                        </a>

                                        <form action="{{ route('admin.student.certificate.delete', $item->certificate->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete this certificate? It will be permanently removed from student dashboard, system database and storage.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete Certificate">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 fw-bold" 
                                            data-bs-toggle="modal" data-bs-target="#assignCertificateModal"
                                            data-userid="{{ $item->student->id }}"
                                            data-username="{{ $item->student->name }}"
                                            data-moduleid="{{ $item->module->id }}"
                                            data-moduletitle="{{ $item->module->title }}">
                                        <i class="fa-solid fa-upload me-1"></i> {{ $item->certificate ? 'Re-upload' : 'Assign' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="fa-solid fa-award fa-3x text-muted opacity-25 mb-3"></i>
                                    <h6>No student enrollments found matching your criteria.</h6>
                                    <p class="small mb-0">Try clearing your search query or selecting a different module filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL — Assign / Update Certificate --}}
<div class="modal fade" id="assignCertificateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:450px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">

            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <div>
                    <h5 class="modal-title fw-bold mb-0"><i class="fa-solid fa-award me-2"></i>Upload Certificate</h5>
                    <p class="small mb-0 opacity-75" id="certSubtitle">Module completion award</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.student.certificate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="user_id" id="certUserId">
                    <input type="hidden" name="module_id" id="certModuleId">
                    
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-user-graduate text-primary me-2"></i>
                            <span class="fw-bold text-dark" id="certStudentName">Student Name</span>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fa-solid fa-book-open text-primary me-2" style="font-size: 0.8rem;"></i>
                            <span id="certModuleName">Module Name</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Choose Certificate File</label>
                        <input type="file" name="certificate" class="form-control" required accept=".pdf, .jpg, .jpeg, .png">
                        <div class="form-text">PDF, JPG or PNG. Maximum 10MB.</div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save Certificate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .cm-page { padding: 2rem; background: #f8f9fa; min-height: 100vh; }
    .cm-title { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0; }
    .cm-subtitle { font-size: 0.9rem; color: #64748b; margin: 0.2rem 0 0; }
    .table thead th { border-bottom: none; letter-spacing: 0.5px; }
    .table td { border-bottom: 1px solid #f1f5f9; padding-top: 1rem; padding-bottom: 1rem; }
    .fw-600 { font-weight: 600; }
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
