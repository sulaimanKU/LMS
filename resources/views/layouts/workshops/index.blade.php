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

    {{-- ── Header ── --}}
    <div class="cr-header">
        <div>
            <h5 class="cr-title"><i class="fa-solid fa-person-chalkboard me-2 text-primary"></i>Workshop Management</h5>
            <p class="cr-subtitle">Create, monitor, and batch manage status for workshop editions</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- Bulk Status Manager Button (Opens Modal) --}}
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkStatusModal">
                <i class="fa-solid fa-sliders me-1 text-primary"></i> Bulk Status Manager
            </button>

            <a href="{{ route('workshops.create') }}" class="cr-btn-add text-decoration-none">
                <i class="fa-solid fa-plus me-2"></i>Add New Workshop
            </a>
        </div>
    </div>

    {{-- ── Bulk Status Modal (Scalable for 1, 10, 50+ Workshops) ── --}}
    <div class="modal fade" id="bulkStatusModal" tabindex="-1" aria-labelledby="bulkStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <form action="{{ route('workshops.bulk-status') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 40px; height: 40px; border-radius: 12px; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark mb-0" id="bulkStatusModalLabel" style="font-size: 1.1rem;">Bulk Status Manager</h5>
                                <p class="text-muted small mb-0">Batch activate or inactivate workshops by edition</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body p-4">
                        {{-- 1. Select Target --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase text-muted small" style="letter-spacing: 0.5px;">1. Select Workshop Edition</label>
                            <select name="workshop_number" class="form-select py-2 shadow-none" style="border-radius: 10px; border-color: #CBD5E1; font-size: 0.9rem;">
                                <option value="all">🌐 All Workshops in System</option>
                                <optgroup label="Available Workshop Editions">
                                    @if(isset($availableWorkshopNumbers) && $availableWorkshopNumbers->isNotEmpty())
                                        @foreach($availableWorkshopNumbers as $wNum)
                                            <option value="{{ $wNum }}" {{ $loop->first ? 'selected' : '' }}>Workshop Edition #{{ $wNum }}</option>
                                        @endforeach
                                    @else
                                        <option value="1">Workshop Edition #1</option>
                                    @endif
                                </optgroup>
                            </select>
                        </div>

                        {{-- 2. Select Status Action --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase text-muted small" style="letter-spacing: 0.5px;">2. Action to Perform</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="wsStatusInactiveRadio" value="inactive" checked autocomplete="off">
                                    <label class="btn btn-outline-danger w-100 py-2 d-flex flex-column align-items-center justify-content-center shadow-none" for="wsStatusInactiveRadio" style="border-radius: 12px;">
                                        <i class="fa-solid fa-circle-pause fs-4 mb-1"></i>
                                        <span class="fw-bold small">Inactivate</span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Hide / Close</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="status" id="wsStatusActiveRadio" value="active" autocomplete="off">
                                    <label class="btn btn-outline-success w-100 py-2 d-flex flex-column align-items-center justify-content-center shadow-none" for="wsStatusActiveRadio" style="border-radius: 12px;">
                                        <i class="fa-solid fa-circle-check fs-4 mb-1"></i>
                                        <span class="fw-bold small">Activate</span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Publish / Open</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-circle-info text-primary mt-1"></i>
                                <span class="small text-muted">
                                    Updates every workshop matching the selected edition in one click. No manual edits needed.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" onclick="return confirm('Apply this batch status update?')">
                            <i class="fa-solid fa-check me-1"></i>Apply Status Change
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="cr-stats">
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#EEF2FF;color:#4F46E5;">
                <i class="fa-solid fa-person-chalkboard"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalWorkshops }}</p>
                <p class="cr-stat-label">Total Workshops</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#D1FAE5;color:#065F46;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $activeWorkshops }}</p>
                <p class="cr-stat-label">Active</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#F1F5F9;color:#64748B;">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $inactiveWorkshops }}</p>
                <p class="cr-stat-label">Inactive</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#FEF3C7;color:#92400E;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalEnrolled }}</p>
                <p class="cr-stat-label">Total Participants</p>
            </div>
        </div>
    </div>

    {{-- ── Filter & Search Row ── --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        {{-- Status Tabs --}}
        <div class="cr-tabs">
            <a class="cr-tab {{ $filter === 'all' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'page' => 1]) }}">All ({{ $totalWorkshops }})</a>
            <a class="cr-tab {{ $filter === 'active' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'active', 'page' => 1]) }}">Active ({{ $activeWorkshops }})</a>
            <a class="cr-tab {{ $filter === 'inactive' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'inactive', 'page' => 1]) }}">Inactive ({{ $inactiveWorkshops }})</a>
        </div>

        {{-- Filter & Search Form --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if(isset($availableWorkshopNumbers) && $availableWorkshopNumbers->isNotEmpty())
            <form action="{{ route('workshops.index') }}" method="GET" class="m-0">
                @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
                <select name="workshop_number" class="form-select form-select-sm rounded-pill shadow-none" onchange="this.form.submit()" style="font-size: 0.82rem; border-color: #CBD5E1; min-width: 140px;">
                    <option value="">All Editions</option>
                    @foreach($availableWorkshopNumbers as $wNum)
                        <option value="{{ $wNum }}" {{ request('workshop_number') == $wNum ? 'selected' : '' }}>Edition #{{ $wNum }}</option>
                    @endforeach
                </select>
            </form>
            @endif

            <form action="{{ route('workshops.index') }}" method="GET" class="cr-search-form m-0">
                @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                @if(request('workshop_number')) <input type="hidden" name="workshop_number" value="{{ request('workshop_number') }}"> @endif
                <div class="cr-search-box">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search workshops..." class="cr-search-input">
                    @if($search)
                        <a href="{{ route('workshops.index', ['filter' => $filter]) }}" class="cr-search-clear text-muted text-decoration-none me-2">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                    <button type="submit" class="cr-search-btn">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Workshop grid ── --}}
    <div class="cr-grid">
        @forelse($workshops as $workshop)
        <div class="cr-card {{ $workshop->status === 'inactive' ? 'cr-card-inactive' : '' }}">

            {{-- Image & Badges --}}
            <div class="cr-card-img">
                @if($workshop->image)
                    <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}">
                @else
                    <div class="cr-card-img-placeholder">
                        <i class="fa-solid fa-person-chalkboard"></i>
                    </div>
                @endif

                {{-- 1-Click Status Toggle Form --}}
                <form action="{{ route('workshops.toggle-status', $workshop->id) }}" method="POST" class="cr-status-overlay m-0">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent p-0" title="Click to toggle Active / Inactive" onclick="return confirm('Change status of {{ addslashes($workshop->title) }} to {{ $workshop->status === 'active' ? 'Inactive' : 'Active' }}?')">
                        <span class="cr-status-badge {{ $workshop->status === 'active' ? 'cr-badge-active' : 'cr-badge-inactive' }}" style="cursor: pointer;">
                            <i class="fa-solid {{ $workshop->status === 'active' ? 'fa-circle-check' : 'fa-circle-pause' }} me-1"></i>
                            {{ ucfirst($workshop->status) }}
                        </span>
                    </button>
                </form>

                <div class="cr-card-actions cr-actions-overlay">
                    <a href="{{ route('workshops.edit', $workshop->id) }}" class="cr-action-edit text-decoration-none" title="Edit Workshop">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('workshops.destroy', $workshop->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($workshop->title) }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cr-action-delete" title="Delete Workshop">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card body --}}
            <div class="cr-card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-soft-primary text-primary rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #EEF2FF;">{{ $workshop->category ?? 'Workshop' }}</span>
                    @if($workshop->workshop_number)
                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Edition #{{ $workshop->workshop_number }}</span>
                    @endif
                </div>

                <h6 class="cr-card-title">{{ $workshop->title }}</h6>
                @if($workshop->short_description)
                    <p class="cr-card-desc mb-2">{{ Str::limit(strip_tags($workshop->short_description), 80) }}</p>
                @endif

                <div class="cr-meta mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-users text-muted me-1"></i> {{ $workshop->enrollments_count }} Enrolled
                    </span>
                    <span class="cr-meta-item fw-bold text-dark">
                        PKR {{ number_format($workshop->price, 0) }}
                    </span>
                </div>
            </div>

        </div>
        @empty
        <div class="cr-empty col-12 text-center py-5">
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #EEF2FF; display: inline-flex; align-items: center; justify-content: center; color: #4F46E5; margin-bottom: 1rem;">
                <i class="fa-solid fa-person-chalkboard fs-3"></i>
            </div>
            <h6 class="fw-bold text-dark">No Workshops Found</h6>
            <p class="text-muted small">No workshops currently match this query or filter.</p>
            <a href="{{ route('workshops.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i>Create First Workshop
            </a>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($workshops->hasPages())
    <div class="cr-pagination mt-4">{{ $workshops->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

<style>
.cr-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }
.cr-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.cr-title    { font-size: 1.25rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-subtitle { font-size: .8rem; color: #64748B; margin: .1rem 0 0; }
.cr-btn-add {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff !important;
    border: none; border-radius: 50px; padding: .55rem 1.2rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cr-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,.35); }
.cr-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .75rem; margin-bottom: 1.25rem; }
.cr-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 14px;
    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .85rem;
}
.cr-stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.cr-stat-num   { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-stat-label { font-size: .7rem; font-weight: 600; color: #94A3B8; text-transform: uppercase; margin: 0; }
.cr-tabs { display: flex; gap: 4px; background: #EEF2FF; padding: 4px; border-radius: 10px; }
.cr-tab { padding: .35rem .9rem; border-radius: 7px; font-size: .8rem; font-weight: 600; color: #64748B; text-decoration: none; }
.cr-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.cr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
.cr-card { background: #fff; border-radius: 14px; border: 1.5px solid #E2E8F0; overflow: hidden; transition: all .2s; }
.cr-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,.05); }
.cr-card-inactive { opacity: 0.8; border-style: dashed; }
.cr-card-img { position: relative; height: 160px; background: #F1F5F9; }
.cr-card-img img { width: 100%; height: 100%; object-fit: cover; }
.cr-card-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #CBD5E1; }
.cr-status-overlay { position: absolute; top: .6rem; left: .6rem; z-index: 2; }
.cr-status-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 50px; font-size: .72rem; font-weight: 700; }
.cr-badge-active { background: #10B981; color: #fff; box-shadow: 0 2px 8px rgba(16,185,129,.3); }
.cr-badge-inactive { background: #64748B; color: #fff; box-shadow: 0 2px 8px rgba(100,116,139,.3); }
.cr-actions-overlay { position: absolute; top: .6rem; right: .6rem; display: flex; gap: 6px; z-index: 2; }
.cr-action-edit, .cr-action-delete {
    width: 32px; height: 32px; border-radius: 50%; background: #fff;
    display: flex; align-items: center; justify-content: center;
    border: none; color: #475569; font-size: .8rem; box-shadow: 0 2px 6px rgba(0,0,0,.15);
    transition: all .2s;
}
.cr-action-edit:hover { background: #4F46E5; color: #fff; }
.cr-action-delete:hover { background: #EF4444; color: #fff; }
.cr-card-body { padding: 1rem; }
.cr-card-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin-bottom: .3rem; }
.cr-card-desc { font-size: .78rem; color: #64748B; line-height: 1.4; }
.cr-meta-item { font-size: .75rem; color: #64748B; }
/* Search styles */
.cr-search-box { position: relative; display: flex; align-items: center; background: #fff; border: 1.5px solid #CBD5E1; border-radius: 50px; padding-left: 12px; overflow: hidden; }
.cr-search-input { border: none; padding: 6px 10px; font-size: .82rem; width: 180px; outline: none; }
.cr-search-btn { background: #4F46E5; color: #fff; border: none; padding: 6px 14px; font-size: .8rem; font-weight: 600; }
</style>

@endsection
