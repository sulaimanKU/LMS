@extends('applayouts.app')

@section('contents')
<style>
    #reporting-view {
        padding: 15px;
        margin-top: 10px;
        width: 100%;
    }

    /* THE HEADER FIX */
    .header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center; /* Keeps title and buttons on the same horizontal line */
        flex-wrap: nowrap; /* Prevents them from dropping below each other */
        margin-bottom: 20px;
        gap: 10px;
    }

    .title-area h4 {
        font-size: 1.1rem; /* Slightly smaller to save space on mobile */
        margin-bottom: 0;
        white-space: nowrap;
    }

    .title-area p {
        display: block; /* Hidden on very small screens to save space */
    }

    /* Responsive Button Group */
    .btn-group-sm-custom {
        display: flex;
        background: #fff;
        border-radius: 6px;
        border: 1px solid #d1d3e2;
        overflow: hidden;
    }

    .btn-group-sm-custom .btn {
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 600;
        border: none;
        border-right: 1px solid #d1d3e2;
        background: transparent;
        color: #4e73df;
    }

    .btn-group-sm-custom .btn:last-child {
        border-right: none;
    }

    .btn-group-sm-custom .btn.active {
        background: #4e73df;
        color: #fff;
    }

    /* Hide sub-text on mobile to keep everything on one line */
    @media (max-width: 576px) {
        .title-area p {
            display: none;
        }
        .title-area h4 {
            font-size: 1rem;
        }
    }
</style>

<div id="reporting-view">

    {{-- 1. HEADER: Title Left, Buttons Right --}}
    <div class="header-flex">
        <div class="title-area">
            <h4 class="fw-bold text-dark">Attendance</h4>
            <p class="text-muted small mb-0">Daily Snapshot</p>
        </div>

        <div class="action-area">
            <div class="btn-group-sm-custom shadow-sm">
                <button class="btn active">Daily</button>
                <button class="btn">Monthly</button>
                <button class="btn">History</button>
            </div>
        </div>
    </div>

    {{-- 2. FILTER BOX (Stays Responsive) --}}
    <div class="card border-0 shadow-sm mb-4 bg-white rounded-3">
        <div class="card-body p-2">
            <div class="row g-2">
                <div class="col-8 col-md-4">
                    <select class="form-select form-select-sm border-0 bg-light">
                        <option>Daily Report</option>
                    </select>
                </div>
                <div class="col-4 col-md-3">
                    <select class="form-select form-select-sm border-0 bg-light">
                        <option>10-A</option>
                    </select>
                </div>
                <div class="col-12 col-md-5 d-flex gap-2">
                    <input type="date" class="form-control form-control-sm border-0 bg-light">
                    <button class="btn btn-primary btn-sm px-3 fw-bold">Go</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. TABLE AREA --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 13px;">
                <thead class="bg-light">
                    <tr class="text-muted">
                        <th class="ps-3">Class</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th class="text-end pe-3">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceStats as $stat)
                        @php
                            $enrolledCount = \App\Models\Enrollment::where('module_id', $stat->module_id)->count();
                            $attendanceRate = $enrolledCount > 0 ? round(($stat->attendances_count / $enrolledCount) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="ps-3 fw-bold">
                                {{ $stat->module->title ?? 'N/A' }}
                                <div class="text-muted" style="font-size: 10px;">{{ \Carbon\Carbon::parse($stat->class_date)->format('d M, Y') }}</div>
                            </td>
                            <td class="text-success">{{ $stat->attendances_count }}</td>
                            <td class="text-danger">{{ max(0, $enrolledCount - $stat->attendances_count) }}</td>
                            <td class="text-end pe-3">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <span class="fw-bold">{{ $attendanceRate }}%</span>
                                    <div class="progress" style="width: 40px; height: 4px;">
                                        <div class="progress-bar bg-{{ $attendanceRate > 70 ? 'success' : ($attendanceRate > 40 ? 'warning' : 'danger') }}" 
                                             role="progressbar" style="width: {{ $attendanceRate }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No attendance data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
