@extends('applayouts.app')

@section('contents')
<div class="container-fluid p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.student.management') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to List
        </a>
        <h4 class="fw-bold mb-0">Student Profile & Registration Details</h4>
    </div>

    <div class="row g-4">
        {{-- Left Column: Student Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 text-center">
                    <div class="mx-auto bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2.5rem; font-weight: 800;">
                        {{ strtoupper(substr($registration->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $registration->name }}</h5>
                    <p class="mb-0 opacity-75 small">{{ $registration->email }}</p>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="small text-muted fw-bold text-uppercase mb-1 d-block">Contact Information</label>
                        <p class="mb-2"><i class="fa-solid fa-phone me-2 text-primary"></i>{{ $registration->phone }}</p>
                        <p class="mb-0"><i class="fa-solid fa-envelope me-2 text-primary"></i>{{ $registration->email }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted fw-bold text-uppercase mb-1 d-block">Academic Background</label>
                        <p class="mb-2"><i class="fa-solid fa-building-columns me-2 text-primary"></i>{{ $registration->institution ?? 'Not provided' }}</p>
                        <p class="mb-0"><i class="fa-solid fa-flask me-2 text-primary"></i>{{ $registration->research_area ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="small text-muted fw-bold text-uppercase mb-1 d-block">Registration Info</label>
                        <p class="mb-2">
                            <span class="badge {{ $registration->status == 'approved' ? 'bg-success' : 'bg-warning' }} px-3 rounded-pill text-capitalize">
                                Status: {{ $registration->status }}
                            </span>
                        </p>
                        <p class="mb-0 small text-muted">Applied on: {{ $registration->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Courses & Slips --}}
        <div class="col-lg-8">
            {{-- Modules Selection --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-book-open me-2 text-primary"></i>Enrolled & Selected Modules</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Module Title</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    @php
                                        $isEnrolled = $enrolledModules->contains('id', $course->id);
                                        $enrollData = $isEnrolled ? $enrolledModules->where('id', $course->id)->first() : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $course->title }}</td>
                                        <td>PKR {{ number_format($course->price, 0) }}</td>
                                        <td>
                                            @if($isEnrolled)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">
                                                    Enrolled ({{ $enrollData->pivot->status }})
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 rounded-pill">
                                                    Pending Approval
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td>Total Registered Amount</td>
                                    <td colspan="2" class="text-primary">PKR {{ number_format($registration->total_amount, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-receipt me-2 text-warning"></i>Uploaded Payment Slips ({{ $registration->slips->count() }})</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @if($registration->slips->isEmpty())
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="fa-solid fa-receipt fs-1 text-muted opacity-25 mb-3 d-block"></i>
                            <p class="text-muted">No payment slips have been uploaded yet.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($registration->slips as $index => $slip)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-4 bg-light h-100">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="fw-bold mb-1">Slip #{{ $index + 1 }}</h6>
                                                <p class="small text-muted mb-0">{{ $slip->created_at->format('d M Y') }}</p>
                                            </div>
                                            <span class="badge {{ $slip->status == 'approved' ? 'bg-success' : 'bg-warning' }} text-capitalize px-3 rounded-pill">
                                                {{ $slip->status }}
                                            </span>
                                        </div>
                                        
                                        @php $isPdf = strtolower(pathinfo($slip->file_path, PATHINFO_EXTENSION)) === 'pdf'; @endphp
                                        
                                        <div class="mb-3 text-center bg-white p-2 rounded-3 border">
                                            @if($isPdf)
                                                <div class="py-4">
                                                    <i class="fa-solid fa-file-pdf text-danger fs-1 mb-2"></i>
                                                    <p class="small text-muted mb-0">PDF Document</p>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $slip->file_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: contain;">
                                            @endif
                                        </div>

                                        <div class="d-grid">
                                            <a href="{{ asset('storage/' . $slip->file_path) }}" target="_blank" class="btn btn-primary rounded-pill btn-sm">
                                                <i class="fa-solid fa-eye me-2"></i>View Full Slip
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
