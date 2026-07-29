@extends('applayouts.app')

@section('contents')
<div class="ts-page">

    {{-- ── Header ── --}}
    <div class="ts-header mb-4">
        <div>
            <div class="ts-header-badge"><i class="fa-solid fa-user-graduate me-1.5"></i> Faculty Student Directory</div>
            <h4 class="ts-title"><i class="fa-solid fa-users text-indigo me-2"></i>My Enrolled Students</h4>
            <p class="ts-subtitle">View and communicate with active students across your assigned courses</p>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="ts-stat-card ts-stat-indigo">
                <div class="ts-stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <div>
                    <h3 class="ts-stat-num">{{ $students->total() }}</h3>
                    <span class="ts-stat-lbl">Active Enrolled Students</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="ts-stat-card ts-stat-emerald">
                <div class="ts-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <h3 class="ts-stat-num">{{ $assignedModules->count() }}</h3>
                    <span class="ts-stat-lbl">Assigned Modules</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="ts-stat-card ts-stat-amber">
                <div class="ts-stat-icon"><i class="fa-solid fa-filter"></i></div>
                <div>
                    <h3 class="ts-stat-num">{{ $search || $selectedModule ? $students->count() : $students->total() }}</h3>
                    <span class="ts-stat-lbl">{{ $search || $selectedModule ? 'Filtered Matches' : 'Total Students' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Search & Filter Toolbar ── --}}
    <div class="ts-toolbar-card mb-4">
        <form action="{{ route('teacher.students.view') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5 col-12">
                <div class="ts-input-wrap">
                    <i class="fa-solid fa-layer-group ts-input-icon"></i>
                    <select name="module_id" class="form-select ts-select">
                        <option value="">All Assigned Modules</option>
                        @foreach($assignedModules as $mod)
                            <option value="{{ $mod->id }}" {{ ($selectedModule == $mod->id) ? 'selected' : '' }}>
                                {{ $mod->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-5 col-12">
                <div class="ts-input-wrap">
                    <i class="fa-solid fa-magnifying-glass ts-input-icon"></i>
                    <input type="text" name="search" class="ts-input" 
                           placeholder="Search student name, email, phone, or address..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-2 col-12 d-flex gap-2">
                <button type="submit" class="btn ts-btn-filter flex-grow-1">
                    <i class="fa-solid fa-filter me-1.5"></i>Filter
                </button>
                @if($search || $selectedModule)
                    <a href="{{ route('teacher.students.view') }}" class="btn ts-btn-clear" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Student List Table ── --}}
    <div class="ts-table-card">
        <div class="ts-table-head d-flex align-items-center justify-content-between">
            <span class="ts-table-title"><i class="fa-solid fa-list-check me-2 text-indigo"></i>Student Directory</span>
            <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace" style="font-size:0.75rem;">
                Showing {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} of {{ $students->total() }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Student Info</th>
                        <th class="py-3">Enrolled Modules</th>
                        <th class="py-3">Contact Information</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="ts-avatar-wrap me-3">
                                        @if($student->profile_image)
                                            <img src="{{ asset('storage/'.$student->profile_image) }}" class="ts-avatar-img" alt="{{ $student->name }}">
                                        @else
                                            <div class="ts-avatar-initial">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block text-dark mb-0.5" style="font-size: 0.92rem;">{{ $student->name }}</span>
                                        <span class="text-muted small"><i class="fa-regular fa-envelope me-1"></i>{{ $student->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-1.5">
                                    @foreach($student->enrolledModules as $mod)
                                        <span class="badge bg-indigo-light text-indigo border border-indigo-subtle px-2 py-1" style="font-weight: 600; font-size: 0.72rem;">
                                            <i class="fa-solid fa-book-bookmark me-1 text-indigo"></i>{{ $mod->title }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="text-dark small"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ $student->phone ?? 'N/A' }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;"><i class="fa-solid fa-location-dot me-1.5 text-muted"></i>{{ Str::limit($student->address ?? 'No address provided', 28) }}</div>
                            </td>
                            <td class="text-center py-3">
                                <span class="badge bg-emerald-light text-emerald px-2.5 py-1.5 rounded-pill" style="font-size: 0.7rem; font-weight:700;">
                                    <i class="fa-solid fa-circle-check me-1"></i>Active Student
                                </span>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <a href="mailto:{{ $student->email }}" class="btn btn-sm btn-light text-indigo border shadow-sm rounded-pill px-3 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                    <i class="fa-solid fa-envelope me-1"></i>Contact
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fa-solid fa-user-slash fa-3x text-muted opacity-25"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">No Students Found</h6>
                                <p class="text-muted small mb-3">No enrolled students match your search criteria or selected module.</p>
                                @if($search || $selectedModule)
                                    <a href="{{ route('teacher.students.view') }}" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm">
                                        Reset Filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="ts-table-foot py-3 px-4 border-top d-flex justify-content-center">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>

<style>
/* ── Page Layout ── */
.ts-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.ts-header { margin-bottom: 1.25rem; }
.ts-header-badge {
    display: inline-block;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    margin-bottom: 0.35rem;
}
.ts-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.ts-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

/* ── Stat Cards ── */
.ts-stat-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.ts-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.ts-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.ts-stat-indigo .ts-stat-icon { background: #EEF2FF; color: #4F46E5; }
.ts-stat-emerald .ts-stat-icon{ background: #ECFDF5; color: #10B981; }
.ts-stat-amber .ts-stat-icon  { background: #FFFBEB; color: #F59E0B; }

.ts-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.ts-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* ── Toolbar Card ── */
.ts-toolbar-card {
    background: #fff;
    border-radius: 16px;
    padding: 1rem 1.2rem;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.ts-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.ts-input-icon {
    position: absolute;
    left: 14px;
    color: #94A3B8;
    font-size: 0.85rem;
    pointer-events: none;
}
.ts-input, .ts-select {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.4rem;
    font-size: 0.84rem;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.ts-input:focus, .ts-select:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

.ts-btn-filter {
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    transition: all 0.2s;
}
.ts-btn-filter:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,0.3); }

.ts-btn-clear {
    background: #FEF2F2;
    color: #DC2626;
    border: 1.5px solid #FECACA;
    border-radius: 10px;
    padding: 0.5rem 0.8rem;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.ts-btn-clear:hover { background: #DC2626; color: #fff; border-color: #DC2626; }

/* ── Table Card ── */
.ts-table-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}
.ts-table-head {
    padding: 1.1rem 1.25rem 0.85rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.ts-table-title { font-size: 0.9rem; font-weight: 800; color: #0F172A; }

.table thead th {
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748B;
    background-color: #F8FAFF;
    border-bottom: 1.5px solid #EDF2F7;
}

/* Avatar styling */
.ts-avatar-wrap { width: 44px; height: 44px; flex-shrink: 0; }
.ts-avatar-img { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; border: 2px solid #EEF2FF; }
.ts-avatar-initial {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff; font-size: 1.1rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #EEF2FF;
}

/* Utility colors */
.bg-indigo-light { background: #EEF2FF; }
.text-indigo     { color: #4F46E5; }
.border-indigo-subtle { border-color: #C7D2FE !important; }
.bg-emerald-light{ background: #ECFDF5; }
.text-emerald    { color: #047857; }

/* Pagination styling */
.pagination { margin: 0; }
.page-link { border-radius: 8px !important; margin: 0 2px; border: none; color: #475569; font-weight: 600; font-size: 0.8rem; }
.page-item.active .page-link { background: #4F46E5; color: #fff; }
</style>
@endsection
