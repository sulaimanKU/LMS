@extends('applayouts.app')

@section('contents')

@php
    $months = $monthlyData->keys()->toArray();
    $counts = $monthlyData->values()->toArray();
@endphp

<div class="dash-page">

    {{-- ── Hero Banner ── --}}
    <div class="dash-hero-banner mb-4">
        <div class="dash-hero-backdrop"></div>
        <div class="row align-items-center g-3 position-relative" style="z-index: 2;">
            <div class="col-12 col-lg-7">
                <div class="d-flex align-items-center gap-3">
                    <div class="dash-hero-avatar">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="dash-hero-chip"><i class="fa-solid fa-shield-halved me-1"></i>System Administrator</span>
                            <span class="text-white-50 small font-mono">&bull; {{ date('l, d M Y') }}</span>
                        </div>
                        <h3 class="dash-hero-title mb-1">Welcome back, {{ auth()->user()->name }}</h3>
                        <p class="dash-hero-sub mb-0">Central executive portal, real-time student analytics & revenue monitoring</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 text-lg-end">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('admin.student.management') }}" class="dash-hero-btn primary-btn text-decoration-none">
                        <i class="fa-solid fa-user-check me-2"></i>Review Registrations
                        @if($pendingCount > 0)
                            <span class="dash-hero-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('course.index') }}" class="dash-hero-btn secondary-btn text-decoration-none">
                        <i class="fa-solid fa-book-bookmark me-2"></i>Course Directory
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Executive KPI Stat Cards ── --}}
    <div class="dash-stats-grid mb-4">

        {{-- Total Students --}}
        <div class="dstat-card card-indigo">
            <div class="dstat-accent-bar"></div>
            <div class="dstat-top">
                <div class="dstat-icon bg-indigo-subtle text-indigo">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <span class="dstat-trend text-indigo"><i class="fa-solid fa-arrow-trend-up me-1"></i>Active Roster</span>
            </div>
            <div class="dstat-body mt-3">
                <p class="dstat-label">Total Unique Students</p>
                <h2 class="dstat-num">{{ number_format($totalStudents) }}</h2>
            </div>
            <a href="{{ route('admin.student.management') }}" class="dstat-link text-decoration-none">
                Manage Students <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        {{-- Total Revenue --}}
        <div class="dstat-card card-emerald">
            <div class="dstat-accent-bar"></div>
            <div class="dstat-top">
                <div class="dstat-icon bg-emerald-subtle text-emerald">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <span class="dstat-trend text-emerald"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
            </div>
            <div class="dstat-body mt-3">
                <p class="dstat-label">Total Revenue</p>
                <h2 class="dstat-num text-emerald" style="font-size: 1.65rem;">PKR {{ number_format($totalRevenue, 0) }}</h2>
            </div>
            <span class="dstat-sub-info"><i class="fa-solid fa-shield-check me-1"></i>Approved Slip Payments</span>
        </div>

        {{-- Course Enrollments --}}
        <div class="dstat-card card-purple">
            <div class="dstat-accent-bar"></div>
            <div class="dstat-top">
                <div class="dstat-icon bg-purple-subtle text-purple">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <span class="dstat-trend text-purple"><i class="fa-solid fa-graduation-cap me-1"></i>Seats Filled</span>
            </div>
            <div class="dstat-body mt-3">
                <p class="dstat-label">Total Course Enrollments</p>
                <h2 class="dstat-num">{{ number_format($totalEnrollments) }}</h2>
            </div>
            <span class="dstat-sub-info"><i class="fa-solid fa-book-open me-1"></i>Across {{ $totalCourses ?? 15 }} Active Courses</span>
        </div>

        {{-- Pending Approvals --}}
        <div class="dstat-card card-amber {{ $pendingCount > 0 ? 'dstat-alert' : '' }}">
            <div class="dstat-accent-bar"></div>
            <div class="dstat-top">
                <div class="dstat-icon bg-amber-subtle text-amber">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                @if($pendingCount > 0)
                    <span class="dstat-trend text-amber"><i class="fa-solid fa-circle-exclamation me-1"></i>Action Needed</span>
                @else
                    <span class="dstat-trend text-muted"><i class="fa-solid fa-check me-1"></i>Up to date</span>
                @endif
            </div>
            <div class="dstat-body mt-3">
                <p class="dstat-label">Pending Approvals</p>
                <h2 class="dstat-num">{{ number_format($pendingCount) }}</h2>
            </div>
            <a href="{{ route('admin.student.management', ['filter' => 'pending']) }}" class="dstat-link text-decoration-none text-amber">
                Review Slips <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

    </div>

    {{-- ── Main Grid: Charts & Top Performing Modules ── --}}
    <div class="row g-4 mb-4">

        {{-- Registration Trend Chart ── --}}
        <div class="col-12 col-xl-8">
            <div class="dash-card h-100">
                <div class="dash-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="dash-card-title"><i class="fa-solid fa-chart-line me-2 text-indigo"></i>Application Trend</span>
                        <span class="text-muted d-block small">Monthly student registration growth over the last 6 months</span>
                    </div>
                    <span class="badge bg-indigo-subtle text-indigo px-3 py-2 rounded-pill fw-bold" style="font-size: 0.72rem;">Live Chart</span>
                </div>
                <div class="dash-card-body p-3" style="height: 300px;">
                    <canvas id="regChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Performing Modules ── --}}
        <div class="col-12 col-xl-4">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <span class="dash-card-title"><i class="fa-solid fa-trophy me-2 text-warning"></i>Top Performing Courses</span>
                    <span class="text-muted d-block small">Ranked by total student enrollments</span>
                </div>
                <div class="dash-card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($topModules as $index => $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0" style="background:transparent; border-bottom: 1px solid #F1F5F9 !important;">
                            <div class="d-flex align-items-center gap-3" style="flex: 1; min-width: 0;">
                                <div class="dash-rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : 'rank-3') }}">
                                    #{{ $index + 1 }}
                                </div>
                                <div style="min-width: 0;">
                                    <span class="fw-bold d-block text-truncate text-dark" style="font-size: .85rem;">{{ $course->title }}</span>
                                    <small class="text-muted" style="font-size: .72rem;">
                                        <i class="fa-solid fa-chalkboard-user me-1 text-emerald"></i>{{ $course->teacher->first()->name ?? 'Unassigned' }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end ms-2">
                                <span class="badge rounded-pill bg-indigo-subtle text-indigo px-3 py-2" style="font-size: .75rem; font-weight: 800;">
                                    {{ $course->enrolled_users_count }} Enrolled
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-5 border-0">No course enrollments found yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Registrations Roster Table ── --}}
    <div class="dash-card mb-4">
        <div class="dash-card-header d-flex justify-content-between align-items-center">
            <div>
                <span class="dash-card-title mb-1"><i class="fa-solid fa-list-check me-2 text-indigo"></i>Recent Applications & Payment Verification</span>
                <span class="text-muted d-block small">Latest student applications submitted to the portal</span>
            </div>
            <a href="{{ route('admin.student.management') }}" class="cm-btn-view-all text-decoration-none">
                View All Registrations <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="dash-card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle dash-table mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Student Profile</th>
                            <th>Mobile Contact</th>
                            <th class="text-center">Modules Selected</th>
                            <th>Total Registered Fee</th>
                            <th class="text-center">Application Status</th>
                            <th class="text-end pe-4">Applied Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dash-avatar">{{ strtoupper(substr($reg->name, 0, 1)) }}</div>
                                    <div>
                                        <span class="fw-bold d-block text-dark" style="font-size:.88rem; line-height: 1.2;">{{ $reg->name }}</span>
                                        <span class="text-muted" style="font-size:.75rem;">{{ $reg->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-dark font-medium small">
                                <i class="fa-solid fa-phone me-1 text-muted"></i>{{ $reg->phone }}
                            </td>
                            <td class="text-center">
                                <span class="dash-pill-module">
                                    <i class="fa-solid fa-book-bookmark me-1 text-indigo"></i>{{ count($reg->selected_courses) }} Course(s)
                                </span>
                            </td>
                            <td style="font-size:.88rem; font-weight:800; color: #4F46E5;">
                                PKR {{ number_format($reg->total_amount, 0) }}
                            </td>
                            <td class="text-center">
                                @if($reg->status === 'approved')
                                    <span class="dash-status dash-status-approved">
                                        <i class="fa-solid fa-circle-check me-1"></i>Approved
                                    </span>
                                @else
                                    <span class="dash-status dash-status-pending">
                                        <i class="fa-solid fa-clock me-1"></i>Pending Review
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4 text-muted small fw-medium">
                                {{ $reg->created_at->format('d M, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5" style="font-size:.875rem;">
                                No recent applications registered yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Admin Shortcut Quick Access ── --}}
    <div class="row g-3 mb-2">
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.student.management') }}" class="dash-quick-link text-decoration-none">
                <div class="quick-icon bg-indigo-subtle text-indigo"><i class="fa-solid fa-users-gear"></i></div>
                <div>
                    <span class="quick-title">Student Management</span>
                    <span class="quick-sub">Review & Approve</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.certificates.management') }}" class="dash-quick-link text-decoration-none">
                <div class="quick-icon bg-emerald-subtle text-emerald"><i class="fa-solid fa-award"></i></div>
                <div>
                    <span class="quick-title">Certificate Hub</span>
                    <span class="quick-sub">Issue Certificates</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('settings.view') }}" class="dash-quick-link text-decoration-none">
                <div class="quick-icon bg-purple-subtle text-purple"><i class="fa-solid fa-gears"></i></div>
                <div>
                    <span class="quick-title">Email & SMTP Config</span>
                    <span class="quick-sub">Server Settings</span>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('course.index') }}" class="dash-quick-link text-decoration-none">
                <div class="quick-icon bg-amber-subtle text-amber"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <span class="quick-title">Course Directory</span>
                    <span class="quick-sub">Manage Modules</span>
                </div>
            </a>
        </div>
    </div>

</div>

<style>
/* Page Wrapper */
.dash-page {
    padding: 2rem;
    background-color: #F8FAFC;
    background-image: 
        radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent 400px),
        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.05), transparent 400px);
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Hero Banner */
.dash-hero-banner {
    background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #312E81 100%);
    border-radius: 24px;
    padding: 2rem 2.25rem;
    color: #FFFFFF;
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.35);
    position: relative;
    overflow: hidden;
}
.dash-hero-backdrop {
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 1;
}
.dash-hero-avatar {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}
.dash-hero-chip {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(6px);
    color: #A5B4FC;
    padding: 3px 12px;
    border-radius: 50px;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.dash-hero-title {
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.4px;
}
.dash-hero-sub {
    font-size: 0.92rem;
    color: #C7D2FE;
}
.dash-hero-btn {
    padding: 0.7rem 1.35rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    transition: all 0.25s ease;
}
.dash-hero-btn.primary-btn {
    background: linear-gradient(135deg, #6366F1, #4F46E5);
    color: #FFFFFF !important;
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}
.dash-hero-btn.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.55);
}
.dash-hero-btn.secondary-btn {
    background: rgba(255, 255, 255, 0.1);
    color: #FFFFFF !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
}
.dash-hero-btn.secondary-btn:hover { background: rgba(255, 255, 255, 0.2); }
.dash-hero-badge {
    background: #EF4444;
    color: #FFFFFF;
    border-radius: 50px;
    padding: 2px 8px;
    font-size: 0.72rem;
    font-weight: 800;
    margin-left: 6px;
}

/* Stat Grid */
.dash-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}
.dstat-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 22px;
    padding: 1.35rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}
.dstat-accent-bar {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    border-radius: 50px;
}
.card-indigo .dstat-accent-bar { background: linear-gradient(90deg, #6366F1, #4F46E5); }
.card-emerald .dstat-accent-bar { background: linear-gradient(90deg, #10B981, #059669); }
.card-purple .dstat-accent-bar  { background: linear-gradient(90deg, #9333EA, #7C3AED); }
.card-amber .dstat-accent-bar   { background: linear-gradient(90deg, #F59E0B, #D97706); }

.dstat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    border-color: #CBD5E1;
}
.dstat-card.dstat-alert {
    border-color: #FCD34D;
    background: #FFFDF0;
}
.dstat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dstat-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.bg-indigo-subtle { background: #EEF2FF; }
.text-indigo { color: #4F46E5; }
.bg-emerald-subtle { background: #ECFDF5; }
.text-emerald { color: #10B981; }
.bg-purple-subtle { background: #F3E8FF; }
.text-purple { color: #9333EA; }
.bg-amber-subtle { background: #FFFBEB; }
.text-amber { color: #D97706; }

.dstat-trend {
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.dstat-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #64748B;
    margin-bottom: 0.25rem;
}
.dstat-num {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0F172A;
    margin: 0;
    line-height: 1.1;
    letter-spacing: -0.5px;
}
.dstat-link {
    font-size: 0.8rem;
    font-weight: 800;
    color: #4F46E5;
    margin-top: 0.85rem;
    display: inline-block;
    transition: all 0.2s ease;
}
.dstat-link:hover { color: #312E81; }
.dstat-sub-info {
    font-size: 0.7rem;
    font-weight: 800;
    color: #94A3B8;
    margin-top: 0.85rem;
    display: block;
    letter-spacing: 0.5px;
}

/* Card Containers */
.dash-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 22px;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    overflow: hidden;
}
.dash-card-header {
    padding: 1.35rem 1.6rem;
    background: #FAFAFC;
    border-bottom: 1px solid #F1F5F9;
}
.dash-card-title {
    font-size: 0.98rem;
    font-weight: 800;
    color: #0F172A;
}
.cm-btn-view-all {
    font-size: 0.78rem;
    font-weight: 800;
    color: #4F46E5;
    background: #EEF2FF;
    border: 1px solid #C7D2FE;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    transition: all 0.2s ease;
}
.cm-btn-view-all:hover {
    background: #4F46E5;
    color: #FFFFFF !important;
}

/* Rank Badges */
.dash-rank-badge {
    width: 30px;
    height: 30px;
    border-radius: 10px;
    font-size: 0.78rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.rank-1 { background: #FEF3C7; color: #D97706; border: 1px solid #FCD34D; }
.rank-2 { background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; }
.rank-3 { background: #FFEDD5; color: #C2410C; border: 1px solid #FDBA74; }

/* Table Styling */
.dash-table thead th {
    background: #F8FAFC;
    color: #64748B;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 1.05rem 1.35rem;
    border-bottom: 1px solid #E2E8F0;
}
.dash-table tbody td {
    padding: 1.15rem 1.35rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}
.dash-avatar {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #FFFFFF;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
}
.dash-pill-module {
    background: #F1F5F9;
    color: #475569;
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 700;
}
.dash-status {
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
}
.dash-status-approved { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.dash-status-pending  { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }

/* Quick Link Shortcuts */
.dash-quick-link {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 18px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.95rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.02);
    transition: all 0.25s ease;
}
.dash-quick-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.07);
    border-color: #CBD5E1;
}
.quick-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.quick-title {
    font-size: 0.88rem;
    font-weight: 800;
    color: #0F172A;
    display: block;
}
.quick-sub {
    font-size: 0.74rem;
    color: #64748B;
    display: block;
}

@media(max-width: 991.98px) {
    .dash-stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 575.98px) {
    .dash-stats-grid { grid-template-columns: 1fr; }
    .dash-page { padding: 1rem; }
}
</style>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('regChart');
    if(canvas) {
        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.28)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartMonths ?? $months) !!},
                datasets: [{
                    label: 'Applications Registered',
                    data: {!! json_encode($chartCounts ?? $counts) !!},
                    borderColor: '#4F46E5',
                    borderWidth: 3.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    pointHoverBackgroundColor: '#4F46E5',
                    pointHoverBorderColor: '#FFFFFF',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { size: 13, weight: 'bold', family: 'Plus Jakarta Sans' },
                        bodyFont: { size: 12, family: 'Plus Jakarta Sans' },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' Applications: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 }, color: '#64748B' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { precision: 0, font: { family: 'Plus Jakarta Sans', weight: '600', size: 11 }, color: '#64748B' }
                    }
                }
            }
        });
    }
});
</script>

@endsection
