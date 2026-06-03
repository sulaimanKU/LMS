@extends('applayouts.app')

@section('contents')
<style>
    /* Standard Layout Colors */
    :root {
        --dept-card-bg: #ffffff;
        --accent-blue: #4e73df;
        --text-muted: #858796;
    }

    #academic-setup-view {
        padding: 24px;
        background-color: #f8f9fc;
    }

    /* Standard Card Styling */
    .dept-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e3e6f0 !important;
    }

    .dept-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }

    /* Icon Containers */
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    /* Progress Bar for "Completeness" */
    .setup-progress {
        height: 6px;
        border-radius: 10px;
        background-color: #eaecf4;
    }

    /* Avatar Stacks for Faculty View */
    .avatar-stack {
        display: flex;
        align-items: center;
    }

    .avatar-stack img, .avatar-stack .avatar-placeholder {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid #fff;
        margin-right: -10px;
        background: #4e73df;
        color: white;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div id="academic-setup-view">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item small"><a href="#">Settings</a></li>
            <li class="breadcrumb-item small active">Academic Setup</li>
        </ol>
    </nav>

    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <h3 class="fw-bold text-dark letter-spacing-tight">Academic Infrastructure</h3>
            <p class="text-muted">Configure your university's core hierarchy and department faculty.</p>
        </div>
        <div class="col-md-5 text-md-end">
            <button class="btn btn-outline-secondary btn-sm rounded-3 me-2">
                <i class="bi bi-download me-1"></i> Export PDF
            </button>
            <button class="btn btn-primary btn-sm rounded-3 px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-plus-lg me-1"></i> Add Department
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-uppercase fw-bold text-muted" style="font-size: 10px;">Total Departments</small>
                <h4 class="fw-bold mb-0">12</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-uppercase fw-bold text-muted" style="font-size: 10px;">Total Faculty</small>
                <h4 class="fw-bold mb-0">142</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-uppercase fw-bold text-muted" style="font-size: 10px;">Active Courses</small>
                <h4 class="fw-bold mb-0">86</h4>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-xl-4 col-md-6">
            <div class="card dept-card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-code-slash fs-4"></i>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-subtle text-success border-0 px-2 mb-2" style="font-size: 10px;">Operational</span>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                <li><a class="dropdown-item small" href="#"><i class="bi bi-pencil me-2"></i>Edit Setup</a></li>
                                <li><a class="dropdown-item small" href="#"><i class="bi bi-people me-2"></i>Manage Faculty</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item small text-danger" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-1">School of Computing</h5>
                <p class="text-muted small mb-4">Department lead by <span class="text-primary fw-medium">Dr. Alan Turing</span>.</p>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small" style="font-size: 11px;">Curriculum Setup</span>
                        <span class="fw-bold small" style="font-size: 11px;">80%</span>
                    </div>
                    <div class="progress setup-progress">
                        <div class="progress-bar bg-primary" style="width: 80%"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <div class="avatar-stack">
                        <img src="https://i.pravatar.cc/150?u=1" title="Teacher 1">
                        <img src="https://i.pravatar.cc/150?u=2" title="Teacher 2">
                        <img src="https://i.pravatar.cc/150?u=3" title="Teacher 3">
                        <div class="avatar-placeholder">+9</div>
                    </div>
                    <a href="#" class="btn btn-light btn-sm rounded-pill px-3 fw-bold" style="font-size: 11px;">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card dept-card border-0 shadow-sm rounded-4 p-4 h-100 bg-white border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-palette fs-4"></i>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning-subtle text-warning border-0 px-2 mb-2" style="font-size: 10px;">Setup Required</span>
                        <button class="btn btn-link text-muted p-0"><i class="bi bi-three-dots-vertical"></i></button>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-1">Arts & Humanities</h5>
                <p class="text-muted small mb-4">No Head of Department assigned yet.</p>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small" style="font-size: 11px;">Curriculum Setup</span>
                        <span class="fw-bold small" style="font-size: 11px;">30%</span>
                    </div>
                    <div class="progress setup-progress">
                        <div class="progress-bar bg-warning" style="width: 30%"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <div class="avatar-stack">
                        <div class="avatar-placeholder bg-secondary">0</div>
                    </div>
                    <a href="#" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-white" style="font-size: 11px;">Finish Setup</a>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 p-4 pb-0">
                <div>
                    <h5 class="fw-bold mb-0">New Department</h5>
                    <p class="text-muted small mb-0">Initialize a new academic entity.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-dark mb-2">Department Label</label>
                        <input type="text" class="form-control border-1 p-2 shadow-none" placeholder="e.g. Mechanical Engineering" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-12">
                            <label class="small fw-bold text-dark mb-2">Assign HOD</label>
                            <select class="form-select border-1 p-2 shadow-none">
                                <option selected>Unassigned</option>
                                <option>Dr. Alan Turing</option>
                                <option>Prof. Ada Lovelace</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold text-dark mb-2">Academic Description</label>
                        <textarea class="form-control border-1 p-2 shadow-none" rows="3" placeholder="Focus and objectives of this department..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">Initialize Dept</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
