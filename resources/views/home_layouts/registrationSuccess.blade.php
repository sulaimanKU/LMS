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
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Payment Instructions</h6>
                            <div class="d-flex mb-2">
                                <div class="me-3 text-primary fw-bold">01.</div>
                                <div>Transfer amount to <strong>JazzCash/EasyPaisa: 0346 9061650</strong></div>
                            </div>
                            <div class="d-flex mb-0">
                                <div class="me-3 text-primary fw-bold">02.</div>
                                <div>Click the button below to upload receipt via WhatsApp.</div>
                            </div>
                        </div>
                    </div>

                    @php
                        $waMessage = "Hi, I've registered for modules. ID: #{$registeration->id}. Name: {$registeration->name}. Amount: PKR " . number_format($registeration->total_amount, 0);
                        $waLink = "https://wa.me/923469061650?text=" . urlencode($waMessage);
                    @endphp

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-5">
                        <a href="{{ $waLink }}" target="_blank" class="btn btn-success btn-lg px-4 shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i> WhatsApp Proof
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-secondary btn-lg px-4">
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
