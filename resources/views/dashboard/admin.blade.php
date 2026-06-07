@extends('applayouts.app')

@section('contents')

@php
    $months = $monthlyData->keys()->toArray();
    $counts = $monthlyData->values()->toArray();
@endphp

<div class="dash-page">

    {{-- ── Top bar ── --}}
    <div class="dash-topbar">
        <div>
            <h5 class="dash-title">Dashboard</h5>
            <p class="dash-subtitle">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('admin.student.managment') }}" class="dash-action-btn">
            <i class="fa-solid fa-user-plus me-1"></i> Review Registrations
            @if($pendingCount > 0)
                <span class="dash-badge">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="dash-stats-grid">

        <div class="dstat-card" style="--accent-h: #4F46E5; --accent-s: #EEF2FF;">
            <div class="dstat-icon" style="background:#EEF2FF; color:#4F46E5;">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div class="dstat-body">
                <p class="dstat-label">Total Students</p>
                <h3 class="dstat-num">{{ $totalStudents }}</h3>
            </div>
            <a href="{{ route('admin.student.managment') }}" class="dstat-link">View Accounts <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="dstat-card" style="--accent-h:#10B981; --accent-s:#ECFDF5;">
            <div class="dstat-icon" style="background:#ECFDF5; color:#10B981;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="dstat-body">
                <p class="dstat-label">Total Revenue</p>
                <h3 class="dstat-num" style="font-size: 1.6rem; padding-top: 0.2rem;">PKR {{ number_format($totalRevenue, 0) }}</h3>
            </div>
            <span class="text-muted" style="font-size: 0.65rem; font-weight: 700;">APPROVED PAYMENTS</span>
        </div>

        <div class="dstat-card" style="--accent-h:#7C3AED; --accent-s:#F5F3FF;">
            <div class="dstat-icon" style="background:#F5F3FF; color:#7C3AED;">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div class="dstat-body">
                <p class="dstat-label">Course Enrollments</p>
                <h3 class="dstat-num">{{ $totalEnrollments }}</h3>
            </div>
            <p class="text-muted mb-0" style="font-size: 0.65rem; font-weight: 700;">TOTAL SEATS FILLED</p>
        </div>

        <div class="dstat-card {{ $pendingCount > 0 ? 'dstat-alert' : '' }}" style="--accent-h:#D97706; --accent-s:#FFFBEB;">
            <div class="dstat-icon" style="background:#FFFBEB; color:#D97706;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="dstat-body">
                <p class="dstat-label">Pending Approvals</p>
                <h3 class="dstat-num">{{ $pendingCount }}</h3>
            </div>
            <a href="{{ route('admin.student.managment') }}?filter=pending" class="dstat-link">Review Slips <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

    </div>

    {{-- ── Charts + recent ── --}}
    <div class="dash-main-grid">

        {{-- Chart ── --}}
        <div class="dash-card dash-chart-card">
            <div class="dash-card-header">
                <span class="dash-card-title"><i class="fa-solid fa-chart-line me-2 text-primary"></i>New Registrations — Last 6 Months</span>
            </div>
            <div class="dash-card-body" style="height:260px; padding:.75rem 1rem 1rem;">
                <canvas id="regChart"></canvas>
            </div>
        </div>

        {{-- Module distribution ── --}}
        <div class="dash-card dash-chart-card">
            <div class="dash-card-header">
                <span class="dash-card-title"><i class="fa-solid fa-trophy me-2 text-warning"></i>Top Performing Modules</span>
            </div>
            <div class="dash-card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topModules as $course)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0" style="background:transparent; border-bottom: 1px solid #F1F5F9 !important;">
                        <div style="flex: 1; min-width: 0;">
                            <span class="fw-bold d-block text-truncate" style="font-size: .82rem;">{{ $course->title }}</span>
                            <small class="text-muted" style="font-size: .65rem;">{{ $course->teacher->name ?? 'No teacher' }}</small>
                        </div>
                        <div class="text-end ms-3">
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary" style="font-size: .75rem; font-weight: 800;">
                                {{ $course->enrolled_users_count }} Students
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-5 border-0">No enrollments yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    {{-- ── Recent registrations table ── --}}
    <div class="dash-card mt-4">
        <div class="dash-card-header">
            <span class="dash-card-title"><i class="fa-solid fa-list-check me-2 text-primary"></i>Recent Registrations</span>
            <a href="{{ route('admin.student.managment') }}" class="dash-view-all">View all</a>
        </div>
        <div class="dash-card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover dash-table mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Modules</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="dash-avatar">{{ strtoupper(substr($reg->name, 0, 1)) }}</div>
                                    <div>
                                        <span class="fw-semibold d-block" style="font-size:.85rem; line-height: 1.2;">{{ $reg->name }}</span>
                                        <span class="text-muted" style="font-size:.7rem;">{{ $reg->phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="dash-pill">{{ count($reg->selected_courses) }} module{{ count($reg->selected_courses) !== 1 ? 's' : '' }}</span>
                            </td>
                            <td style="font-size:.85rem; font-weight:700; color: #1E293B;">PKR {{ number_format($reg->total_amount, 0) }}</td>
                            <td>
                                @if($reg->status === 'approved')
                                    <span class="dash-status dash-status-approved"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>
                                @else
                                    <span class="dash-status dash-status-pending"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem; color:#64748B;">{{ $reg->created_at->format('d M, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4" style="font-size:.875rem;">
                                No registrations yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
/* ── Page wrapper ── */
.dash-page {
    padding: 1.5rem;
    min-height: 100%;
    background: #F8FAFF;
}

/* ── Top bar ── */
.dash-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.75rem;
}
.dash-title    { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; line-height: 1.2; }
.dash-subtitle { font-size: .82rem; color: #64748B; margin: .15rem 0 0; }

.dash-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    padding: .5rem 1.1rem;
    border-radius: 8px;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(79,70,229,.3);
    transition: all .2s;
    position: relative;
}
.dash-action-btn:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 20px rgba(79,70,229,.4); }
.dash-badge {
    background: #EF4444;
    color: #fff;
    border-radius: 50px;
    padding: 1px 7px;
    font-size: .7rem;
    font-weight: 700;
    margin-left: 2px;
}

/* ── Stat grid ── */
.dash-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.dstat-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.25rem 1.25rem 1rem;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    display: flex;
    flex-direction: column;
    gap: .5rem;
    border: 1.5px solid #F1F5F9;
    transition: box-shadow .2s, transform .2s;
}
.dstat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.1); transform: translateY(-2px); }
.dstat-card.dstat-alert { border-color: #FCD34D; background: #FFFDF0; }

.dstat-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.dstat-body { flex: 1; }
.dstat-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .7px; color: #94A3B8; margin: 0 0 .15rem; }
.dstat-num   { font-size: 2rem; font-weight: 800; color: #1E293B; margin: 0; line-height: 1.1; }
.dstat-link  { font-size: .75rem; font-weight: 600; color: #4F46E5; text-decoration: none; display: flex; align-items: center; }
.dstat-link:hover { color: #3730A3; }

/* ── Main grid (charts) ── */
.dash-main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1rem;
    margin-bottom: 0;
}

/* ── Card ── */
.dash-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    border: 1.5px solid #F1F5F9;
    overflow: hidden;
}
.dash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
}
.dash-card-title { font-size: .88rem; font-weight: 700; color: #1E293B; }
.dash-view-all   { font-size: .78rem; font-weight: 600; color: #4F46E5; text-decoration: none; }
.dash-view-all:hover { color: #3730A3; }
.dash-card-body  { padding: 1.25rem; }

/* ── Table ── */
.dash-table thead th {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #94A3B8;
    border-bottom: 1px solid #F1F5F9;
    background: #F8FAFF;
    padding: .7rem 1rem;
}
.dash-table tbody td { padding: .7rem 1rem; vertical-align: middle; border-bottom: 1px solid #F8FAFF; }
.dash-table tbody tr:last-child td { border-bottom: none; }

.dash-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700;
    flex-shrink: 0;
}

.dash-pill {
    background: #EEF2FF;
    color: #4F46E5;
    padding: .18rem .65rem;
    border-radius: 50px;
    font-size: .75rem;
    font-weight: 600;
}

.dash-status {
    display: inline-block;
    padding: .2rem .65rem;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .3px;
}
.dash-status-approved { background: #D1FAE5; color: #065F46; }
.dash-status-pending  { background: #FEF3C7; color: #92400E; }

.text-purple { color: #7C3AED; }

/* ── Responsive ── */
@media (max-width: 1199.98px) {
    .dash-stats-grid  { grid-template-columns: repeat(2, 1fr); }
    .dash-main-grid   { grid-template-columns: 1fr; }
}
@media (max-width: 575.98px) {
    .dash-page        { padding: 1rem .75rem; }
    .dash-stats-grid  { grid-template-columns: 1fr 1fr; gap: .6rem; }
    .dstat-num        { font-size: 1.6rem; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const months = @json($months);
    const counts = @json($counts);

    // ── Line chart: registrations per month ──
    new Chart(document.getElementById('regChart'), {
        type: 'line',
        data: {
            labels: months.length ? months : ['No data'],
            datasets: [{
                label: 'Registrations',
                data: counts.length ? counts : [0],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#4F46E5',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#F1F5F9' } }
            }
        }
    });
})();
</script>

@endsection
