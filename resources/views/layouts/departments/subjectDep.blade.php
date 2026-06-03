@extends('applayouts.app')

@section('contents')
<style>
    #subjects-view {
        padding: 24px;
        background-color: #f8f9fc;
    }

    .subject-row {
        background: #fff;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        transition: all 0.2s;
        margin-bottom: 12px;
    }

    .subject-row:hover {
        border-color: #4e73df;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .code-badge {
        background: #f1f3f9;
        color: #4e73df;
        font-family: 'Monaco', monospace;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .credit-tag {
        font-size: 11px;
        color: #858796;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
</style>

<div id="subjects-view">

    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="fw-bold text-dark mb-1">Subject Curriculum</h4>
            <p class="text-muted small">Manage academic units and credit weightage.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                <i class="bi bi-journal-plus me-1"></i> Add Subject
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-3 mb-4 rounded-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm border-light bg-light" placeholder="Search by name or code...">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm border-light bg-light">
                    <option selected>All Departments</option>
                    <option>Computer Science</option>
                    <option>Mathematics</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-0">
        {{-- Dummy Subject 1 --}}
        <div class="col-12">
            <div class="subject-row p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="code-badge">CS201</div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Object Oriented Programming</h6>
                        <span class="credit-tag">3 Credit Hours • Core Subject</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="text-end d-none d-md-block">
                        <small class="text-muted d-block" style="font-size: 10px;">DEPARTMENT</small>
                        <span class="small fw-medium">Comp. Science</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><a class="dropdown-item small" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                            <li><a class="dropdown-item small text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dummy Subject 2 --}}
        <div class="col-12">
            <div class="subject-row p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="code-badge" style="background: #fff4e5; color: #ff9800;">MTH102</div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Applied Calculus</h6>
                        <span class="credit-tag">4 Credit Hours • Mandatory</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4">
                    <div class="text-end d-none d-md-block">
                        <small class="text-muted d-block" style="font-size: 10px;">DEPARTMENT</small>
                        <span class="small fw-medium">Mathematics</span>
                    </div>
                    <button class="btn btn-sm btn-light rounded-circle"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 p-4 pb-0">
                <h6 class="fw-bold mb-0">Define New Subject</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="transform: scale(0.7)"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="small fw-bold text-muted mb-1">Code</label>
                            <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="CS-101" required>
                        </div>
                        <div class="col-8">
                            <label class="small fw-bold text-muted mb-1">Subject Name</label>
                            <input type="text" class="form-control form-control-sm bg-light border-0" placeholder="e.g. Data Science" required>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold text-muted mb-1">Department</label>
                            <select class="form-select form-select-sm bg-light border-0">
                                <option selected disabled>Select Department</option>
                                <option>Computer Science</option>
                                <option>Business School</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1">Credit Hours</label>
                            <input type="number" class="form-control form-control-sm bg-light border-0" placeholder="3">
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1">Type</label>
                            <select class="form-select form-select-sm bg-light border-0">
                                <option>Theory</option>
                                <option>Practical</option>
                                <option>Both</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 py-2 fw-bold">Create Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
