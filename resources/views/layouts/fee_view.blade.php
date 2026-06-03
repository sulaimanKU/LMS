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
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">Robert Fox</div>
                                    <small class="text-muted text-nowrap">Grade 10-A</small>
                                </td>
                                <td>$450.00</td>
                                <td><span class="badge bg-success-subtle text-success px-2 py-1">Paid</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-light btn-sm"><i class="bi bi-three-dots"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold">Cody Fisher</div>
                                    <small class="text-muted text-nowrap">Grade 12-B</small>
                                </td>
                                <td>$1,200.00</td>
                                <td><span class="badge bg-danger-subtle text-danger px-2 py-1">Overdue</span></td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-light btn-sm text-primary">Notify</button>
                                </td>
                            </tr>
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
                        <span class="fw-bold text-success">$84,000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Outstanding</span>
                        <span class="fw-bold text-danger">$12,500</span>
                    </div>
                    <hr>
                    <div class="small mb-1 text-muted">Collection Target</div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                    </div>
                </div>
            </div>

            {{-- Recent Log --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">System Log</h6>
                    <div class="border-start ps-3 py-1 mb-3">
                        <div class="small fw-bold">Payment Confirmed</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Robert Fox paid $450.00 via Bank.</div>
                    </div>
                    <div class="border-start ps-3 py-1">
                        <div class="small fw-bold">Invoice Generated</div>
                        <div class="text-muted" style="font-size: 0.75rem;">12 new invoices for Grade 12.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
