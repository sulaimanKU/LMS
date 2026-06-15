@extends('applayouts.app')

@section('contents')
<div class="cr-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="cr-header">
        <div>
            <h5 class="cr-title">Workshop Management</h5>
            <p class="cr-subtitle">Create and manage your specific workshop editions</p>
        </div>
        <a href="{{ route('workshops.create') }}" class="cr-btn-add text-decoration-none">
            <i class="fa-solid fa-plus me-2"></i>Add New Workshop
        </a>
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
        {{-- Tabs --}}
        <div class="cr-tabs">
            <a class="cr-tab {{ $filter === 'all' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all', 'page' => 1]) }}">All Workshops ({{ $totalWorkshops }})</a>
            <a class="cr-tab {{ $filter === 'active' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'active', 'page' => 1]) }}">Active ({{ $activeWorkshops }})</a>
            <a class="cr-tab {{ $filter === 'inactive' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'inactive', 'page' => 1]) }}">Inactive ({{ $inactiveWorkshops }})</a>
        </div>

        {{-- Search Form --}}
        <form action="{{ route('workshops.index') }}" method="GET" class="cr-search-form">
            @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
            <div class="cr-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or #..." class="cr-search-input">
                @if($search)
                    <a href="{{ route('workshops.index', ['filter' => $filter]) }}" class="cr-search-clear">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
                <button type="submit" class="cr-search-btn">Search</button>
            </div>
        </form>
    </div>

    {{-- ── Workshop grid ── --}}
    <div class="cr-grid">
        @forelse($workshops as $workshop)
        <div class="cr-card {{ $workshop->status === 'inactive' ? 'cr-card-inactive' : '' }}">

            {{-- Image --}}
            <div class="cr-card-img">
                @if($workshop->image)
                    <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}">
                @else
                    <div class="cr-card-img-placeholder">
                        <i class="fa-solid fa-person-chalkboard"></i>
                    </div>
                @endif
                <span class="cr-status-badge cr-status-overlay {{ $workshop->status === 'active' ? 'cr-badge-active' : 'cr-badge-inactive' }}">
                    {{ ucfirst($workshop->status) }}
                </span>
                <div class="cr-card-actions cr-actions-overlay">
                    <a href="{{ route('workshops.edit', $workshop->id) }}" class="cr-action-edit text-decoration-none" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('workshops.destroy', $workshop->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($workshop->title) }}\'?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cr-action-delete" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card body --}}
            <div class="cr-card-body">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-soft-primary text-primary rounded-pill px-2 py-1" style="font-size: 0.65rem; background: #EEF2FF;">Workshop Module</span>
                    @if($workshop->workshop_number)
                        <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.65rem;">Edition #{{ $workshop->workshop_number }}</span>
                    @endif
                </div>

                <h6 class="cr-card-title">{{ $workshop->title }}</h6>
                @if($workshop->short_description)
                    <p class="cr-card-desc">{{ Str::limit(strip_tags($workshop->short_description), 80) }}</p>
                @endif

                <div class="cr-meta mt-2">
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-users"></i> {{ $workshop->enrollments_count }} Participants
                    </span>
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-money-bill-wave"></i> PKR {{ number_format($workshop->price, 0) }}
                    </span>
                </div>
            </div>

        </div>
        @empty
        <div class="cr-empty col-12 text-center py-5">
            <i class="fa-solid fa-person-chalkboard fs-1 text-muted mb-3 d-block"></i>
            <p class="text-muted">No workshops found in this section.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($workshops->hasPages())
    <div class="cr-pagination mt-4">{{ $workshops->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

<style>
/* Reusing styles from courses but tailored */
.cr-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }
.cr-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.cr-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }
.cr-btn-add {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff !important;
    border: none; border-radius: 10px; padding: .55rem 1.1rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cr-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
.cr-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 14px;
    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .85rem;
}
.cr-stat-num   { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-stat-label { font-size: .7rem; font-weight: 600; color: #94A3B8; text-transform: uppercase; }
.cr-tabs { display: flex; gap: 4px; background: #EEF2FF; padding: 4px; border-radius: 10px; }
.cr-tab { padding: .32rem .9rem; border-radius: 7px; font-size: .8rem; font-weight: 600; color: #64748B; text-decoration: none; }
.cr-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.cr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
.cr-card { background: #fff; border-radius: 14px; border: 1.5px solid #F1F5F9; overflow: hidden; }
.cr-card-img { position: relative; height: 160px; background: #F1F5F9; }
.cr-card-img img { width: 100%; height: 100%; object-fit: cover; }
.cr-status-overlay { position: absolute; top: .5rem; left: .5rem; }
.cr-actions-overlay { position: absolute; top: .5rem; right: .5rem; display: flex; gap: 5px; }
.cr-card-body { padding: 1rem; }
.cr-card-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin-bottom: .5rem; }
.cr-card-desc { font-size: .8rem; color: #64748B; }
.cr-meta { display: flex; gap: 1rem; font-size: .75rem; color: #94A3B8; }
/* Search styles */
.cr-search-box { position: relative; display: flex; align-items: center; background: #fff; border: 1.5px solid #E2E8F0; border-radius: 12px; padding-left: 12px; }
.cr-search-input { border: none; padding: 8px; font-size: .85rem; width: 250px; outline: none; }
.cr-search-btn { background: #4F46E5; color: #fff; border: none; padding: 8px 15px; border-radius: 0 11px 11px 0; font-size: .8rem; }
</style>

@endsection
