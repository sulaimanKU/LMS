@extends('applayouts.app')

@section('contents')
<div id="fee-admin-view" class="container-fluid py-4" style="margin-top: 20px;">

    {{-- Professional Header: Minimalist --}}
    <div class="row align-items-center mb-4 border-bottom pb-3 g-3">
        <div class="col-12 col-md-6">
            <h3 class="fw-bold text-dark mb-1">Fee Management</h3>
            <p class="text-muted small mb-0">Track collections and outstanding student balances.</p>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end">
                <button class="btn btn-outline-secondary btn-sm px-3 shadow-sm bg-white">
                    <i class="bi bi-download me-1"></i> Export
                </button>
                <button class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Collect Fee
                </button>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">

        {{-- LEFT SIDE: Main Table --}}
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h6 class="fw-bold mb-0">Recent Transactions</h6>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control form-control-sm" placeholder="Search student...">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th class="ps-3">Student</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">{{ $tx->registration->name ?? 'Unknown' }}</div>
                                    <small class="text-muted text-nowrap">{{ $tx->registration->email ?? 'N/A' }}</small>
                                </td>
                                <td>PKR {{ number_format($tx->registration->total_amount ?? 0, 0) }}</td>
                                <td>
                                    @php
                                        $statusClass = $tx->status === 'approved' ? 'success' : ($tx->status === 'pending' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} px-2 py-1">{{ ucfirst($tx->status) }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.student.managment') }}" class="btn btn-light btn-sm"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: Summaries --}}
        <div class="col-12 col-xl-4">

            {{-- Collection Summary --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Quick Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Collected</span>
                        <span class="fw-bold text-success">PKR {{ number_format($totalCollected, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Outstanding</span>
                        <span class="fw-bold text-danger">PKR {{ number_format($outstanding, 0) }}</span>
                    </div>
                    <hr>
                    <div class="small mb-1 text-muted">Collection Rate</div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ $collectionRate }}%"></div>
                    </div>
                    <div class="text-end mt-1 small fw-bold text-primary">{{ $collectionRate }}%</div>
                </div>
            </div>

            {{-- Recent Log --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">System Log</h6>
                    @forelse($systemLogs as $log)
                    <div class="border-start border-3 border-{{ $log->status === 'approved' ? 'success' : 'warning' }} ps-3 py-1 mb-3">
                        <div class="small fw-bold">Payment {{ ucfirst($log->status) }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            {{ $log->student_name }} submitted a slip. 
                            <div class="mt-1 small opacity-75">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small">No logs available.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
