@extends('applayouts.app')

@section('contents')
<div class="main-content-area container-fluid pt-4">
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <h2 class="fw-bold text-dark mb-0">My Certificates</h2>
            <p class="text-muted small mb-0">View and download your completed course certificates</p>
        </div>
    </div>

    @if($certificates->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fa-solid fa-award fa-3x text-muted opacity-25"></i>
            </div>
            <h5 class="text-muted fw-bold">No Certificates Yet</h5>
            <p class="text-muted small">Complete your modules to earn certificates. They will appear here once assigned by your instructor or admin.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($certificates as $cert)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header border-0 pb-0 pt-3 bg-white">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-check-circle me-1"></i>Completed Module
                            </span>
                        </div>
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10B981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 2rem; box-shadow: 0 4px 10px rgba(16,185,129,0.3);">
                                    <i class="fa-solid fa-certificate"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $cert->module->title ?? 'Unknown Module' }}</h5>
                            <p class="text-muted small mb-3">Issued: {{ $cert->created_at->format('F d, Y') }}</p>
                            
                            <a href="{{ asset('storage/' . $cert->certificate_path) }}" target="_blank" class="btn btn-outline-primary w-100 rounded-pill fw-bold" style="border-width: 2px;">
                                <i class="fa-solid fa-download me-2"></i>Download Certificate
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .main-content-area { padding-bottom: 3rem; background-color: #f8f9fa; min-height: 100vh; }
    .card { transition: all 0.2s; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
</style>
@endsection
