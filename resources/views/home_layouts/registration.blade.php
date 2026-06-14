@extends('welcome')

@section('content')
<!-- Premium Font -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --reg-primary: #6366F1;
        --reg-primary-dark: #4F46E5;
        --reg-slate-900: #0F172A;
        --reg-slate-600: #475569;
        --reg-slate-400: #94A3B8;
        --reg-bg: #F8FAFF;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--reg-bg); }

    /* ── Premium Hero ── */
    .reg-premium-hero {
        background: var(--reg-slate-900);
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.15) 0%, transparent 40%);
        padding: 6rem 0 5rem;
        position: relative;
        color: #fff;
    }
    .reg-hero-label {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
        padding: 8px 20px; border-radius: 50px; font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1.5px; color: #A5B4FC; margin-bottom: 1.5rem;
    }
    .reg-hero-title { 
        font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 1rem; line-height: 1.1; 
        background: linear-gradient(to bottom, #FFFFFF 50%, #E2E8F0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
    }
    .reg-hero-sub { font-size: 1.15rem; color: var(--reg-slate-400); max-width: 650px; margin: 0 auto; line-height: 1.6; font-weight: 500; }

    /* ── Modern Card Layout ── */
    .reg-main-section { padding: 4rem 0 6rem; margin-top: -3rem; position: relative; z-index: 10; }
    
    .reg-container-card {
        background: #fff; border-radius: 28px; border: 1px solid #E5E7EB;
        box-shadow: 0 40px 100px -12px rgba(0, 0, 0, 0.08);
        display: flex; overflow: hidden;
    }

    /* Left Side: Form */
    .reg-form-panel { flex: 1.6; padding: 4rem; border-right: 1px solid #F1F5F9; }
    
    .step-indicator { display: flex; align-items: center; gap: 12px; margin-bottom: 2.5rem; }
    .step-badge { 
        width: 36px; height: 36px; background: var(--reg-primary-dark); 
        color: #fff; border-radius: 12px; display: flex; align-items: center; 
        justify-content: center; font-weight: 800; font-size: 1rem;
    }
    .step-text h4 { font-size: 1.25rem; font-weight: 800; color: var(--reg-slate-900); margin: 0; }
    .step-text p { font-size: 0.85rem; color: var(--reg-slate-400); margin: 0; }

    .form-group-custom { margin-bottom: 1.75rem; }
    .label-custom { 
        display: block; font-size: 0.8rem; font-weight: 700; color: var(--reg-slate-600); 
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;
    }
    .input-wrapper-custom { position: relative; }
    .input-wrapper-custom i { position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--reg-slate-400); transition: 0.2s; }
    .form-control-custom {
        width: 100%; padding: 14px 16px 14px 3.2rem;
        background: #F9FAFB; border: 2px solid #F3F4F6; border-radius: 14px;
        font-size: 0.95rem; font-weight: 500; transition: all 0.2s ease; outline: none;
    }
    .form-control-custom:focus {
        background: #fff; border-color: var(--reg-primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .form-control-custom:focus + i { color: var(--reg-primary); }

    /* Course Selection Grid */
    .course-intl-grid { display: flex; flex-direction: column; gap: 12px; margin-top: 1rem; }
    .course-intl-item {
        display: flex; align-items: center; gap: 16px; padding: 1.25rem 1.5rem;
        background: #fff; border: 2px solid #F3F4F6; border-radius: 16px;
        cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
    }
    .course-intl-item:hover { border-color: var(--reg-primary); background: #F8FAFF; }
    .course-intl-item.active { border-color: var(--reg-primary); background: #EEF2FF; box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15); }
    
    .course-intl-item input { display: none; }
    .check-sq {
        width: 24px; height: 24px; border: 2.5px solid #E2E8F0; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; transition: 0.2s; background: #fff;
    }
    .course-intl-item.active .check-sq { background: var(--reg-primary); border-color: var(--reg-primary); color: #fff; }
    
    .ci-title { font-size: 1rem; font-weight: 700; color: var(--reg-slate-900); display: block; }
    .ci-cat { font-size: 0.75rem; font-weight: 600; color: var(--reg-slate-400); text-transform: uppercase; }
    .ci-price { margin-left: auto; font-size: 0.95rem; font-weight: 800; color: var(--reg-primary-dark); }

    /* Right Side: Summary */
    .reg-summary-panel { flex: 1; background: #F9FAFB; padding: 4rem 3rem; display: flex; flex-direction: column; }
    
    .summary-card {
        background: #fff; border-radius: 20px; border: 1px solid #E5E7EB;
        padding: 2rem; box-shadow: 0 15px 30px -5px rgba(0,0,0,0.05);
        position: sticky; top: 110px;
    }
    .summary-header { font-weight: 800; font-size: 1.1rem; color: var(--reg-slate-900); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
    .summary-header i { color: var(--reg-primary); }

    .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.9rem; color: var(--reg-slate-600); }
    .total-divider { height: 1px; background: #F1F5F9; margin: 1.5rem 0; }
    .total-row { display: flex; justify-content: space-between; align-items: baseline; }
    .total-label { font-weight: 800; color: var(--reg-slate-900); }
    .total-amount { font-size: 2rem; font-weight: 800; color: var(--reg-primary-dark); letter-spacing: -1px; }
    .currency { font-size: 1rem; color: var(--reg-slate-400); margin-right: 4px; }

    .payment-notice {
        margin-top: 2rem; padding: 1.25rem; background: #EEF2FF; border-radius: 14px;
        border: 1px solid rgba(99, 102, 241, 0.1);
    }
    .pn-title { font-weight: 700; font-size: 0.85rem; color: var(--reg-primary-dark); margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
    .pn-text { font-size: 0.82rem; color: var(--reg-slate-600); line-height: 1.5; margin: 0; }
    .pn-acc { display: block; font-weight: 800; font-size: 1rem; color: var(--reg-slate-900); margin-top: 8px; }

    .btn-reg-submit {
        width: 100%; padding: 16px; background: var(--reg-primary-dark); color: #fff;
        border: none; border-radius: 14px; font-weight: 800; font-size: 1.05rem;
        transition: all 0.3s; margin-top: 2rem; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
    }
    .btn-reg-submit:hover { transform: translateY(-2px); box-shadow: 0 15px 35px rgba(79, 70, 229, 0.4); color: #fff; }

    @media (max-width: 991px) {
        .reg-container-card { flex-direction: column; }
        .reg-form-panel { padding: 3rem 2rem; border-right: none; }
        .reg-summary-panel { padding: 3rem 2rem; }
        .summary-card { position: static; }
    }
</style>

{{-- ── Hero Section ── --}}
<section class="reg-premium-hero">
    <div class="container text-center">
        <div class="reg-hero-label">
            <i class="bi bi-star-fill"></i> Join the Academic Community
        </div>
        <h1 class="reg-hero-title">Student Registration</h1>
        <p class="reg-hero-sub">
            Complete your profile and select your specialized modules to begin your 
            professional certification journey today.
        </p>
    </div>
</section>

{{-- ── Main Form Section ── --}}
<section class="reg-main-section">
    <div class="container">
        
        <form action="{{ route('register.store') }}" method="POST" id="registrationForm">
            @csrf
            <input type="hidden" name="total_amount" id="total-amount-input" value="0">

            <div class="reg-container-card">
                
                {{-- Form Side --}}
                <div class="reg-form-panel">
                    
                    {{-- Section 1: Identity --}}
                    <div class="step-indicator">
                        <div class="step-badge">1</div>
                        <div class="step-text">
                            <h4>Personal Identity</h4>
                            <p>Information used for your certification</p>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-custom">Full Name</label>
                                <div class="input-wrapper-custom">
                                    <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" placeholder="e.g. Dr. Sarah Ahmed" required>
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-custom">Email Address</label>
                                <div class="input-wrapper-custom">
                                    <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" placeholder="sarah@university.edu" required>
                                    <i class="bi bi-envelope"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-custom">University / Institution</label>
                                <div class="input-wrapper-custom">
                                    <input type="text" name="institution" class="form-control-custom" value="{{ old('institution') }}" placeholder="Organization name" required>
                                    <i class="bi bi-bank"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="label-custom">WhatsApp Number</label>
                                <div class="input-wrapper-custom">
                                    <input type="text" name="phone" class="form-control-custom" value="{{ old('phone') }}" placeholder="+92 ..." required>
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-custom">
                                <label class="label-custom">Research Area / Major</label>
                                <div class="input-wrapper-custom">
                                    <input type="text" name="research_area" class="form-control-custom" value="{{ old('research_area') }}" placeholder="Specific field of study">
                                    <i class="bi bi-journal-bookmark"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Modules --}}
                    <div class="step-indicator">
                        <div class="step-badge">2</div>
                        <div class="step-text">
                            <h4>Specialized Modules</h4>
                            <p>Select the programs you wish to enroll in</p>
                        </div>
                    </div>

                    <div class="course-intl-grid">
                        @foreach($courses as $course)
                            @php
                                $oldSelected = old('selected_courses', []);
                                $isChecked = in_array($course->id, array_map('intval', $oldSelected));
                            @endphp
                            <label class="course-intl-item {{ $isChecked ? 'active' : '' }}">
                                <input type="checkbox" name="selected_courses[]" 
                                       value="{{ $course->id }}" 
                                       data-price="{{ $course->price }}"
                                       class="course-checkbox"
                                       {{ $isChecked ? 'checked' : '' }}>
                                
                                <div class="check-sq"><i class="bi bi-check-lg"></i></div>
                                
                                <div class="ci-info">
                                    <span class="ci-title">{{ $course->title }}</span>
                                    <span class="ci-cat">{{ $course->category }}</span>
                                </div>
                                
                                <div class="ci-price">PKR {{ number_format($course->price, 0) }}</div>
                            </label>
                        @endforeach
                    </div>

                </div>

                {{-- Summary Side --}}
                <div class="reg-summary-panel">
                    
                    <div class="summary-card">
                        <div class="summary-header">
                            <i class="bi bi-shield-lock"></i> Enrollment Summary
                        </div>

                        <div class="summary-row">
                            <span>Selected Modules</span>
                            <span id="selected-count" class="fw-bold text-dark">0</span>
                        </div>
                        
                        <div class="total-divider"></div>

                        <div class="total-row">
                            <span class="total-label">Total Payable</span>
                            <div class="total-amount">
                                <span class="currency">PKR</span>
                                <span id="total-price">0</span>
                            </div>
                        </div>

                        <div class="payment-notice">
                            <div class="pn-title"><i class="bi bi-info-circle"></i> How to Pay</div>
                            <p class="pn-text">
                                @if(isset($systemSettings['payment_easypaisa']))
                                    <strong>EasyPaisa:</strong> <span class="d-block mb-1 text-dark fw-bold">{{ $systemSettings['payment_easypaisa'] }}</span>
                                @endif
                                
                                @if(isset($systemSettings['payment_jazzcash']))
                                    <strong>JazzCash:</strong> <span class="d-block mb-1 text-dark fw-bold">{{ $systemSettings['payment_jazzcash'] }}</span>
                                @endif

                                @if(isset($systemSettings['payment_bank_name']))
                                    <div class="mt-2 border-top pt-2" style="border-color: rgba(99, 102, 241, 0.1) !important;">
                                        <strong>Bank:</strong> {{ $systemSettings['payment_bank_name'] }}<br>
                                        <strong>Title:</strong> {{ $systemSettings['payment_bank_title'] ?? '' }}<br>
                                        <strong>A/C:</strong> <span class="text-dark fw-bold">{{ $systemSettings['payment_bank_number'] ?? '' }}</span>
                                    </div>
                                @endif
                            </p>
                            <p class="pn-text mt-2 small opacity-75">Send receipt proof to the WhatsApp number for instant verification.</p>
                        </div>

                        <button type="submit" class="btn-reg-submit">
                            Confirm Registration <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0"><i class="bi bi-lock-fill me-1"></i> SSL Encrypted Connection</p>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registrationForm');

    function recalc() {
        let total = 0, count = 0;
        form.querySelectorAll('.course-checkbox:checked').forEach(cb => {
            total += parseFloat(cb.dataset.price) || 0;
            count++;
        });
        document.getElementById('total-price').textContent   = total.toLocaleString();
        document.getElementById('selected-count').textContent = count;
        document.getElementById('total-amount-input').value  = total;
    }

    form.addEventListener('change', function (e) {
        const cb = e.target.closest('.course-checkbox');
        if (!cb) return;
        const item = cb.closest('.course-intl-item');
        if (cb.checked) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
        recalc();
    });

    form.addEventListener('submit', function () {
        const btn = form.querySelector('.btn-reg-submit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Registering...';
    });

    // Initialize
    recalc();
});
</script>

@endsection
