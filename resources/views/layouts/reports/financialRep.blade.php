@extends('applayouts.app')
@section('contents')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
.fr-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

/* Header */
.fr-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:.75rem; margin-bottom:1.5rem; }
.fr-title  { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.fr-sub    { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats grid */
.fr-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.fr-stat {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:14px;
    padding:1rem 1.15rem; display:flex; align-items:center; gap:.85rem;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.fr-stat-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.fr-stat-num  { font-size:1.25rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.fr-stat-lbl  { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.2rem 0 0; }

/* Cards */
.fr-card { background:#fff; border:1.5px solid #F1F5F9; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; }
.fr-card-head { padding:.9rem 1.2rem; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; }
.fr-card-title { font-size:.88rem; font-weight:800; color:#1E293B; margin:0; }
.fr-card-sub   { font-size:.72rem; color:#94A3B8; }
.fr-card-body  { padding:1.1rem 1.2rem; }

/* Charts row */
.fr-charts { display:grid; grid-template-columns:2fr 1fr; gap:.75rem; margin-bottom:.75rem; }

/* Table */
.fr-table { width:100%; border-collapse:collapse; }
.fr-table th { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.6px; color:#94A3B8; padding:.65rem 1.2rem; border-bottom:1.5px solid #F1F5F9; text-align:left; }
.fr-table th:last-child { text-align:right; }
.fr-table td { padding:.75rem 1.2rem; border-bottom:1px solid #F8FAFF; vertical-align:middle; }
.fr-table tr:last-child td { border-bottom:none; }
.fr-table tr:hover td { background:#FAFBFF; }
.fr-td-name  { font-size:.84rem; font-weight:700; color:#1E293B; margin:0; }
.fr-td-email { font-size:.72rem; color:#94A3B8; margin:.1rem 0 0; }
.fr-td-date  { font-size:.78rem; color:#64748B; }
.fr-td-amt   { font-size:.92rem; font-weight:800; color:#059669; text-align:right; }
.fr-badge    { display:inline-flex; align-items:center; padding:.15rem .55rem; border-radius:50px; font-size:.67rem; font-weight:700; }
.fr-badge-ok  { background:#D1FAE5; color:#065F46; }
.fr-badge-pnd { background:#FEF3C7; color:#92400E; }

/* Module revenue rows */
.fr-mod-row { display:flex; align-items:center; gap:.75rem; margin-bottom:.6rem; }
.fr-mod-row:last-child { margin-bottom:0; }
.fr-mod-name { font-size:.8rem; font-weight:600; color:#1E293B; min-width:0; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.fr-mod-bar-wrap { flex:2; background:#F1F5F9; border-radius:99px; height:7px; overflow:hidden; }
.fr-mod-bar  { height:100%; border-radius:99px; background:linear-gradient(90deg,#4F46E5,#7C3AED); }
.fr-mod-amt  { font-size:.78rem; font-weight:700; color:#4F46E5; min-width:80px; text-align:right; }

/* Empty state */
.fr-empty { text-align:center; padding:3rem 1rem; color:#CBD5E1; }
.fr-empty i { font-size:2rem; display:block; margin-bottom:.5rem; }
.fr-empty p { margin:0; font-size:.82rem; }

@media(max-width:1100px) { .fr-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:900px)  { .fr-charts { grid-template-columns:1fr; } }
@media(max-width:600px)  { .fr-stats { grid-template-columns:repeat(2,1fr); } }
</style>

@php
    $maxModRev = $revenueByModule->max('module_revenue') ?: 1;
@endphp

<div class="fr-page">

    {{-- Header --}}
    <div class="fr-header">
        <div>
            <h5 class="fr-title">Financial Reports</h5>
            <p class="fr-sub">Live revenue & enrollment data &nbsp;·&nbsp; as of {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="fr-stats">
        <div class="fr-stat">
            <div class="fr-stat-icon" style="background:#D1FAE5;color:#059669;"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div>
                <p class="fr-stat-num">PKR {{ number_format($totalRevenue, 0) }}</p>
                <p class="fr-stat-lbl">Total Revenue</p>
            </div>
        </div>
        <div class="fr-stat">
            <div class="fr-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div>
                <p class="fr-stat-num">PKR {{ number_format($pendingRevenue, 0) }}</p>
                <p class="fr-stat-lbl">Pending Review</p>
            </div>
        </div>
        <div class="fr-stat">
            <div class="fr-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-graduation-cap"></i></div>
            <div>
                <p class="fr-stat-num">{{ $approvedCount }}</p>
                <p class="fr-stat-lbl">Approved Students</p>
            </div>
        </div>
        <div class="fr-stat">
            <div class="fr-stat-icon" style="background:#F0FDF4;color:#16A34A;"><i class="fa-solid fa-users"></i></div>
            <div>
                <p class="fr-stat-num">{{ $totalStudents }}</p>
                <p class="fr-stat-lbl">Total Enrolled</p>
            </div>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="fr-charts">

        {{-- Revenue trend --}}
        <div class="fr-card">
            <div class="fr-card-head">
                <p class="fr-card-title"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Revenue Trend</p>
                <span class="fr-card-sub">Last 8 months</span>
            </div>
            <div class="fr-card-body">
                @if($revenueByMonth->isEmpty())
                <div class="fr-empty">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>No approved payments yet — chart will appear once students are approved.</p>
                </div>
                @else
                <div style="position:relative;height:240px;">
                    <canvas id="revenueChart"></canvas>
                </div>
                @endif
            </div>
        </div>

        {{-- Revenue by module --}}
        <div class="fr-card">
            <div class="fr-card-head">
                <p class="fr-card-title"><i class="fa-solid fa-book me-2 text-primary"></i>By Module</p>
                <span class="fr-card-sub">Enrollment revenue</span>
            </div>
            <div class="fr-card-body">
                @if($revenueByModule->isEmpty())
                <div class="fr-empty">
                    <i class="fa-solid fa-book-open"></i>
                    <p>No enrollments yet.</p>
                </div>
                @else
                @foreach($revenueByModule as $mod)
                @php $pct = $maxModRev > 0 ? ($mod->module_revenue / $maxModRev * 100) : 0; @endphp
                <div class="fr-mod-row">
                    <span class="fr-mod-name" title="{{ $mod->title }}">{{ Str::limit($mod->title, 22) }}</span>
                    <div class="fr-mod-bar-wrap"><div class="fr-mod-bar" style="width:{{ $pct }}%"></div></div>
                    <span class="fr-mod-amt">PKR {{ number_format($mod->module_revenue, 0) }}</span>
                </div>
                @endforeach
                @endif
            </div>
        </div>

    </div>

    {{-- Recent ledger --}}
    <div class="fr-card">
        <div class="fr-card-head">
            <p class="fr-card-title"><i class="fa-solid fa-receipt me-2 text-primary"></i>Recent Payments</p>
            <span class="fr-card-sub">Last {{ $recentPayments->count() }} approved registrations</span>
        </div>

        @if($recentPayments->isEmpty())
        <div class="fr-empty" style="padding:2.5rem 1rem;">
            <i class="fa-solid fa-receipt"></i>
            <p>No approved registrations yet.</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table class="fr-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Slips</th>
                        <th>Approved On</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPayments as $i => $reg)
                    <tr>
                        <td style="color:#94A3B8;font-size:.78rem;">{{ $i + 1 }}</td>
                        <td>
                            <p class="fr-td-name">{{ $reg->name }}</p>
                            <p class="fr-td-email">{{ $reg->email }}</p>
                        </td>
                        <td>
                            <span style="font-size:.78rem;color:#64748B;">
                                <i class="fa-solid fa-receipt me-1 text-primary"></i>{{ $reg->slips->count() }} slip{{ $reg->slips->count() != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td class="fr-td-date">
                            {{ $reg->approved_at ? \Carbon\Carbon::parse($reg->approved_at)->format('d M Y') : '—' }}
                        </td>
                        <td><span class="fr-badge fr-badge-ok"><i class="fa-solid fa-check me-1"></i>Approved</span></td>
                        <td class="fr-td-amt">PKR {{ number_format($reg->total_amount, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@if($revenueByMonth->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($revenueByMonth->pluck('month'));
    const data   = @json($revenueByMonth->pluck('total')->map(fn($v) => (float)$v));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Revenue (PKR)',
                data,
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79,70,229,.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4F46E5',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => 'PKR ' + Number(v).toLocaleString()
                    }
                }
            }
        }
    });
});
</script>
@endif

@endsection
