@extends('applayouts.app')

@section('contents')
<div class="main-content-area container-fluid pt-4">
    {{-- Header & Filter Section --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-lg-4">
            <h2 class="fw-bold text-dark mb-0">My Students</h2>
            <p class="text-muted small mb-0">Manage and communicate with your participants</p>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fff;">
                <div class="card-body p-2">
                    <form action="{{ route('teacher.students.view') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group input-group-sm border rounded-3 bg-light overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3"><i class="fas fa-layer-group text-primary"></i></span>
                                <select name="module_id" class="form-select border-0 bg-light shadow-none py-2">
                                    <option value="">All Modules Assigned</option>
                                    @foreach($assignedModules as $mod)
                                        <option value="{{ $mod->id }}" {{ ($selectedModule == $mod->id) ? 'selected' : '' }}>
                                            {{ $mod->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group input-group-sm border rounded-3 bg-light overflow-hidden">
                                <span class="input-group-text bg-light border-0 ps-3"><i class="fas fa-search text-primary"></i></span>
                                <input type="text" name="search" class="form-control border-0 bg-light shadow-none py-2" 
                                       placeholder="Search name, email, or phone..." value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100 py-2 rounded-3 shadow-sm fw-bold">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row (Optional but nice) --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 opacity-75 small fw-bold text-uppercase">Total Active</p>
                        <h3 class="mb-0 fw-bold">{{ $students->total() }}</h3>
                    </div>
                    <i class="fas fa-user-graduate fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Student List</h5>
            @if($search || $selectedModule)
                <a href="{{ route('teacher.students.view') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-times me-1"></i> Clear Filters
                </a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="ps-4 py-3">Student Details</th>
                        <th class="py-3">Enrolled Modules</th>
                        <th class="py-3">Contact info</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px; font-weight: 800; font-size: 1.1rem; border: 2px solid #fff;">
                                        @if($student->profile_image)
                                            <img src="{{ asset('storage/'.$student->profile_image) }}" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                        @else
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block text-dark mb-0" style="font-size: 0.95rem;">{{ $student->name }}</span>
                                        <span class="text-muted small"><i class="far fa-envelope me-1"></i>{{ $student->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($student->enrolledModules as $mod)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-weight: 600; font-size: 0.7rem;">
                                            <i class="fas fa-book-reader me-1"></i>{{ $mod->title }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="text-dark small"><i class="fas fa-phone-alt me-2 text-muted"></i>{{ $student->phone ?? 'N/A' }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;"><i class="fas fa-map-marker-alt me-2"></i>{{ Str::limit($student->address ?? 'No address', 25) }}</div>
                            </td>
                            <td class="text-center py-3">
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2" style="font-size: 0.7rem;">
                                    <i class="fas fa-check-circle me-1"></i>Approved
                                </span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px; font-size: 0.85rem;">
                                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user me-2 text-primary"></i> View Profile</a></li>
                                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-comment-dots me-2 text-success"></i> Message</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="#"><i class="fas fa-exclamation-circle me-2"></i> Report Issue</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-search fa-3x text-muted opacity-25"></i>
                                </div>
                                <h5 class="text-muted fw-bold">No Students Found</h5>
                                <p class="text-muted small">Try adjusting your filters or search keywords.</p>
                                <a href="{{ route('teacher.students.view') }}" class="btn btn-sm btn-primary rounded-pill px-4 mt-2 shadow-sm">
                                    Reset All Filters
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
            <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .main-content-area { padding-bottom: 3rem; background-color: #f8f9fa; min-height: 100vh; }
    .form-select, .form-control { border-radius: 8px; font-size: 0.85rem; }
    .table thead th { 
        text-transform: uppercase; 
        letter-spacing: 0.8px; 
        font-weight: 700; 
        color: #64748b;
        background-color: #fcfcfd;
        border-bottom: 1px solid #edf2f7;
    }
    .table tbody tr { transition: all 0.2s; }
    .table tbody tr:hover { background-color: #f8fafc; transform: scale(1.001); }
    .table td { border-bottom: 1px solid #f1f5f9; }
    .btn-primary { background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #4338ca, #6d28d9); transform: translateY(-1px); }
    .bg-success-subtle { background-color: #d1fae5; }
    .text-success { color: #065f46; }
    .pagination { margin-bottom: 0; }
    .page-link { border-radius: 8px !important; margin: 0 3px; border: none; color: #4b5563; font-weight: 600; }
    .page-item.active .page-link { background: #4f46e5; color: #fff; }
</style>
@endsection
