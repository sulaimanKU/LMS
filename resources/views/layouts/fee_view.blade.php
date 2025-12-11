@extends('index')

@section('contents')
    <!-- ===============================================
   STUDENT FEE DASHBOARD
   ROLE: Student
   PURPOSE: View dues, payments, receipts
=============================================== -->
{{-- <div class="container my-5">

    <!-- Section Header -->
    <div class="p-4 bg-primary text-white rounded shadow-sm mb-4">
        <h2 class="fw-bold mb-1">Student Fee Portal</h2>
        <p class="mb-0 opacity-75">Track your fees, payments, and download receipts.</p>
    </div>

    <!-- Current Fee Overview -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">📘 Current Fee Overview</h4>

        <div class="row g-3 text-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Fee</h6>
                        <h3 class="fw-bold text-primary">Rs. 15,000</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Paid</h6>
                        <h3 class="fw-bold text-success">Rs. 8,000</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Remaining</h6>
                        <h3 class="fw-bold text-danger">Rs. 7,000</h3>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary mt-4 px-4 py-2">
            💳 Make Payment
        </button>
    </div>

    <!-- Fee Breakdown -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">📑 Fee Breakdown</h4>

        <ul class="list-group shadow-sm">
            <li class="list-group-item d-flex justify-content-between">
                Tuition Fee <strong>Rs. 10,000</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                Lab Fee <strong>Rs. 3,000</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                Exam Fee <strong>Rs. 2,000</strong>
            </li>
        </ul>
    </div>

    <!-- Payment History -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">🧾 Payment History</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>12 Feb 2025</td>
                        <td>Rs. 5,000</td>
                        <td>Online</td>
                        <td><button class="btn btn-sm btn-outline-secondary">Download</button></td>
                    </tr>
                    <tr>
                        <td>02 Jan 2025</td>
                        <td>Rs. 3,000</td>
                        <td>Bank Deposit</td>
                        <td><button class="btn btn-sm btn-outline-secondary">Download</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Alerts -->
    <div class="alert alert-warning p-3 shadow-sm">
        ⚠ <strong>Reminder:</strong> Your remaining fee is due before <strong>28 Feb 2025</strong>.
    </div>
</div> --}}

<!-- ===============================================
   ADMIN FEE CONTROL PANEL
   ROLE: Admin / Teacher
   PURPOSE: Manage transactions, reports, fee structure
=============================================== -->
<div class="container my-5">

    <!-- Section Header -->
    <div class="p-4 bg-dark text-white rounded shadow-sm mb-4">
        <h2 class="fw-bold mb-1">Fee Management Panel</h2>
        <p class="mb-0 opacity-75">Admin tools for verification, reporting, and fee structure control.</p>
    </div>

    <!-- Search Student Status -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">🔍 Search Student Fee Status</h4>

        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter Student Name / ID">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option>Select Course</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </div>

    <!-- Payment Verification Table -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">📝 Payment Verification</h4>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Verify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ali Khan</td>
                        <td>Rs. 3,000</td>
                        <td>Bank Deposit</td>
                        <td>15 Feb 2025</td>
                        <td>
                            <button class="btn btn-success btn-sm">Accept</button>
                            <button class="btn btn-danger btn-sm">Reject</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Fee Structure Creator -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">🏗 Create / Edit Fee Structure</h4>

        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Fee Name (e.g., Tuition)">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" placeholder="Amount">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100">Save Fee</button>
            </div>
        </div>
    </div>

    <!-- Dues Report -->
    <div class="p-4 bg-white border rounded shadow-sm mb-4">
        <h4 class="fw-bold mb-3">📊 Dues Report</h4>

        <button class="btn btn-outline-secondary mb-3">Export to Excel</button>

        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Dues</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ayesha B.</td>
                    <td>Computer Science</td>
                    <td class="text-danger fw-bold">Rs. 7,000</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div class="p-4 bg-light border rounded shadow-sm">
        <h4 class="fw-bold mb-3">💰 Financial Summary</h4>

        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
                Total Expected <strong>Rs. 250,000</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                Total Collected <strong>Rs. 180,000</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                Outstanding <strong class="text-danger">Rs. 70,000</strong>
            </li>
        </ul>
    </div>

</div>

@endsection
