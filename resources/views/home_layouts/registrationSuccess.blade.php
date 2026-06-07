@extends('welcome')

@section('content')
<style>
    /* Standard Print Styling */
    @media print {
        .btn, .breadcrumb, .alert, nav, footer { display: none !important; }
        .card { border: 1px solid #dee2e6 !important; shadow: none !important; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; }
    }
</style>
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('register') }}">Registration</a></li>
            <li class="breadcrumb-item active" aria-current="page">Confirmation</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-0">Registration Invoice</h4>
                            <small class="text-muted">ID: #{{ $registeration->id }} | Date: {{ $registeration->created_at->format('d M, Y') }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning text-dark px-3 py-2 text-uppercase">
                                <i class="bi bi-clock-history me-1"></i> {{ $registeration->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Student Information</h6>
                            <p class="mb-1 fw-bold">{{ $registeration->name }}</p>
                            <p class="mb-1 text-muted">{{ $registeration->email }}</p>
                            <p class="mb-0 text-muted">{{ $registeration->phone }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Institution</h6>
                            <p class="mb-1">{{ $registeration->institution }}</p>
                            <p class="text-muted small">{{ $registeration->research_area }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3">Module Description</th>
                                    <th class="text-end py-3">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coursesDetial as $course)
                                <tr>
                                    <td class="py-3">{{ $course->title }}</td>
                                    <td class="text-end fw-semibold">PKR {{ number_format($course->price, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-0">
                                <tr>
                                    <td class="text-end fw-bold pt-4 fs-5">Total Payable</td>
                                    <td class="text-end fw-bold pt-4 fs-5 text-primary">PKR {{ number_format($registeration->total_amount, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-bank2 me-2 text-primary"></i>Bank & Account Details for Payment</h6>
                            <div class="row g-3">
                                @if(isset($systemSettings['payment_easypaisa']))
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded border-start border-primary border-4 shadow-sm">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.65rem;">EasyPaisa</small>
                                        <span class="fw-bold d-block">{{ $systemSettings['payment_easypaisa'] }}</span>
                                    </div>
                                </div>
                                @endif

                                @if(isset($systemSettings['payment_jazzcash']))
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded border-start border-danger border-4 shadow-sm">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.65rem;">JazzCash</small>
                                        <span class="fw-bold d-block">{{ $systemSettings['payment_jazzcash'] }}</span>
                                    </div>
                                </div>
                                @endif

                                @if(isset($systemSettings['payment_bank_name']))
                                <div class="col-12">
                                    <div class="p-3 bg-white rounded border-start border-success border-4 shadow-sm">
                                        <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.65rem;">Bank Account</small>
                                        <div class="d-flex flex-wrap gap-3">
                                            <div><span class="text-muted small">Bank:</span> <span class="fw-bold">{{ $systemSettings['payment_bank_name'] }}</span></div>
                                            @if(isset($systemSettings['payment_bank_title']))
                                            <div><span class="text-muted small">Title:</span> <span class="fw-bold">{{ $systemSettings['payment_bank_title'] }}</span></div>
                                            @endif
                                            @if(isset($systemSettings['payment_bank_number']))
                                            <div><span class="text-muted small">A/C:</span> <span class="fw-bold text-primary">{{ $systemSettings['payment_bank_number'] }}</span></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="background: #fff8f0; border-left: 5px solid #f39c12 !important;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Important Next Steps</h5>
                            <p class="mb-3 fs-6">To complete your enrollment, please follow these instructions carefully:</p>
                            
                            <div class="d-flex mb-3">
                                <div class="me-3 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0; font-weight: bold;">1</div>
                                <div>Click on the <strong>"Go to Payment Portal"</strong> button below.</div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="me-3 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0; font-weight: bold;">2</div>
                                <div>Enter your registered email address (<strong>{{ $registeration->email }}</strong>).</div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="me-3 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0; font-weight: bold;">3</div>
                                <div>Upload your payment slip or transaction screenshot.</div>
                            </div>

                            <div class="d-flex mb-0">
                                <div class="me-3 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; flex-shrink: 0; font-weight: bold;">4</div>
                                <div>After our team verifies your payment, your <strong>Username and Password</strong> will be sent to your <strong>registered email address</strong>.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-5">
                        <a href="{{ route('payment.upload') }}" class="btn btn-primary btn-lg px-5 shadow-sm fw-bold rounded-pill">
                            <i class="bi bi-wallet2 me-2"></i> Go to Payment Portal
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('register') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Back to Registration
                </a>
            </div>
        </div>
    </div>
</div>


@endsection
