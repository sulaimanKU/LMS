@extends('applayouts.app')

@section('contents')
<div class="cm-page">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="cm-header mb-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-6">
                <h5 class="cm-title"><i class="fa-solid fa-award me-2 text-primary"></i>Certificate Management</h5>
                <p class="cm-subtitle">Assign and manage student certificates module-wise</p>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('admin.certificates.management') }}" method="GET" id="moduleFilterForm">
                    <div class="input-group shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <span class="input-group-text bg-white border-0 ps-3"><i class="fa-solid fa-layer-group text-primary"></i></span>
                        <select name="module_id" class="form-select border-0 py-2 fw-600" onchange="this.form.submit()" style="font-size: 0.9rem;">
                            <option value="">Select Module to View Students...</option>
                            @foreach($allModules as $mod)
                                <option value="{{ $mod->id }}" {{ $selectedModuleId == $mod->id ? 'selected' : '' }}>
                                    {{ $mod->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!$selectedModuleId)
        <div class="cm-empty-state card border-0 shadow-sm text-center py-5" style="border-radius: 20px;">
            <div class="py-4">
                <i class="fa-solid fa-layer-group fa-4x text-muted opacity-25 mb-3"></i>
                <h5 class="fw-bold text-dark">No Module Selected</h5>
                <p class="text-muted">Please select a module from the dropdown above to manage its student certificates.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Students Enrolled in <span class="text-primary">{{ $allModules->find($selectedModuleId)->title }}</span></h6>
                    <span class="badge bg-light text-primary border rounded-pill px-3 py-2">{{ $students->count() }} Students</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4 py-3">Student Name</th>
                            <th class="py-3">Email Address</th>
                            <th class="text-center py-3">Course Status</th>
                            <th class="text-center py-3">Certificate Status</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $cert = $student->certificates->first();
                                $enrollment = $student->enrolledModules->first();
                                $status = $enrollment ? $enrollment->pivot->status : 'unknown';
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 700;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-muted small">{{ $student->email }}</td>
                                <td class="text-center py-3">
                                    @if($status == 'completed')
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                            <i class="fa-solid fa-graduation-cap me-1"></i>Completed
                                        </span>
                                    @elseif($status == 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <i class="fa-solid fa-play-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                            {{ ucfirst($status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3">
                                    @if($cert)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                            <i class="fa-solid fa-check-circle me-1"></i>Assigned
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                            <i class="fa-solid fa-clock me-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if($cert)
                                            <a href="{{ asset('storage/' . $cert->certificate_path) }}" target="_blank" class="btn btn-sm btn-light border" title="View Certificate">
                                                <i class="fa-solid fa-eye text-primary"></i>
                                            </a>
                                        @endif
                                        <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 fw-bold" 
                                                data-bs-toggle="modal" data-bs-target="#assignCertificateModal"
                                                data-userid="{{ $student->id }}"
                                                data-username="{{ $student->name }}"
                                                data-moduleid="{{ $selectedModuleId }}"
                                                data-moduletitle="{{ $allModules->find($selectedModuleId)->title }}">
                                            <i class="fa-solid fa-upload me-1"></i> {{ $cert ? 'Update' : 'Assign' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No students enrolled in this module yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

{{-- MODAL — Assign Certificate --}}
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
                        <div class="form-text">PDF, JPG or PNG. Max 5MB.</div>
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
