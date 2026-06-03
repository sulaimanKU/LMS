@extends('welcome')

@section('content')

{{-- ── Page Hero ── --}}
<section class="reg-hero">
    <div class="container">
        <div class="text-center">
            <span class="section-label" style="color:rgba(255,255,255,.8);">
                <i class="bi bi-person-plus-fill me-1"></i>Join Us
            </span>
            <h1 class="reg-hero-title">Student Registration</h1>
            <p class="reg-hero-sub">Secure your place in the next research cohort. Select your modules and complete your profile below.</p>
        </div>
    </div>
</section>

{{-- ── Form ── --}}
<section class="reg-body-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0 ps-3 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST" id="registrationForm">
                    @csrf
                    <input type="hidden" name="total_amount" id="total-amount-input" value="0">

                    <div class="reg-card-wrap">

                        {{-- ── Left: form fields ── --}}
                        <div class="reg-form-col">

                            {{-- Step 1 --}}
                            <div class="reg-step-header">
                                <div class="reg-step-badge">1</div>
                                <div>
                                    <div class="reg-step-title">Account Information</div>
                                    <div class="reg-step-sub">Tell us about yourself</div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="reg-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="reg-input-wrap">
                                        <i class="bi bi-person reg-input-icon"></i>
                                        <input type="text" name="name" class="reg-input @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="Dr. Sarah Ahmed" required>
                                    </div>
                                    @error('name')<div class="reg-field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="reg-input-wrap">
                                        <i class="bi bi-envelope reg-input-icon"></i>
                                        <input type="email" name="email" class="reg-input @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="sarah@university.edu" required>
                                    </div>
                                    @error('email')<div class="reg-field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">University / Institution <span class="text-danger">*</span></label>
                                    <div class="reg-input-wrap">
                                        <i class="bi bi-building reg-input-icon"></i>
                                        <input type="text" name="institution" class="reg-input @error('institution') is-invalid @enderror"
                                            value="{{ old('institution') }}" placeholder="e.g. Punjab University" required>
                                    </div>
                                    @error('institution')<div class="reg-field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">Major / Research Area</label>
                                    <div class="reg-input-wrap">
                                        <i class="bi bi-journal-text reg-input-icon"></i>
                                        <input type="text" name="research_area" class="reg-input @error('research_area') is-invalid @enderror"
                                            value="{{ old('research_area') }}" placeholder="e.g. Molecular Biology">
                                    </div>
                                    @error('research_area')<div class="reg-field-error">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="reg-label">WhatsApp Number <span class="text-danger">*</span></label>
                                    <div class="reg-input-wrap">
                                        <i class="bi bi-whatsapp reg-input-icon"></i>
                                        <input type="text" name="phone" class="reg-input @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="+92 3XX XXXXXXX" required>
                                    </div>
                                    @error('phone')<div class="reg-field-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Step 2 --}}
                            <div class="reg-step-header">
                                <div class="reg-step-badge">2</div>
                                <div>
                                    <div class="reg-step-title">Select Modules</div>
                                    <div class="reg-step-sub">Choose one or more programmes</div>
                                </div>
                            </div>

                            @error('selected_courses')
                                <div class="alert alert-warning border-0 py-2 px-3 mb-3" style="font-size:.875rem; border-radius:8px;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            <div class="course-selection-grid">
                                @foreach($courses as $course)
                                    @php
                                        $oldSelected = old('selected_courses', []);
                                        $isChecked = in_array($course->id, array_map('intval', $oldSelected));
                                    @endphp
                                    <label class="course-sel-item {{ $isChecked ? 'selected' : '' }}">
                                        <input type="checkbox" name="selected_courses[]"
                                            value="{{ $course->id }}"
                                            data-price="{{ $course->price }}"
                                            class="course-checkbox"
                                            {{ $isChecked ? 'checked' : '' }}>
                                        <div class="csi-check"><i class="bi bi-check-lg"></i></div>
                                        <div class="csi-body">
                                            <div class="csi-title">{{ $course->title }}</div>
                                            <div class="csi-cat">{{ $course->category }}</div>
                                        </div>
                                        <div class="csi-price">PKR {{ number_format($course->price, 0) }}</div>
                                    </label>
                                @endforeach
                            </div>

                        </div>

                        {{-- ── Right: summary panel ── --}}
                        <div class="reg-summary-col">
                            <div class="reg-summary-panel">

                                <div class="rsp-header">
                                    <i class="bi bi-receipt me-2"></i>Payment Summary
                                </div>

                                <div class="rsp-body">
                                    <div class="rsp-row">
                                        <span>Modules selected</span>
                                        <span id="selected-count" class="fw-bold">0</span>
                                    </div>
                                    <div class="rsp-total-wrap">
                                        <span class="rsp-total-label">Total Amount</span>
                                        <div class="rsp-total-amount">
                                            <span class="rsp-currency">PKR</span>
                                            <span id="total-price">0</span>
                                        </div>
                                    </div>

                                    <div class="rsp-divider"></div>

                                    <div class="rsp-payment-info">
                                        <div class="rsp-pi-title">
                                            <i class="bi bi-patch-check-fill text-success me-1"></i>How to Pay
                                        </div>
                                        <p class="rsp-pi-text">
                                            Transfer via <strong>JazzCash / EasyPaisa</strong>:<br>
                                            <span class="rsp-account">+92 3469061650</span>
                                        </p>
                                        <p class="rsp-pi-text mb-0">
                                            After payment, WhatsApp your receipt to the same number for verification.
                                        </p>
                                    </div>

                                    <button type="submit" class="rsp-submit-btn">
                                        <i class="bi bi-send-check me-2"></i>Complete Registration
                                    </button>

                                    <p class="rsp-secure-note">
                                        <i class="bi bi-shield-lock-fill me-1"></i>Secure &amp; Encrypted Registration
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<style>
/* ── Hero ── */
.reg-hero {
    background: var(--gradient-primary);
    padding: 3.5rem 0 3rem;
    position: relative;
    overflow: hidden;
}
.reg-hero::before {
    content:''; position:absolute; top:-40%; right:-5%;
    width:400px; height:400px; border-radius:50%;
    background:rgba(255,255,255,.06); pointer-events:none;
}
.reg-hero-title {
    font-size: clamp(1.7rem,4vw,2.6rem);
    font-weight: 800; color: #fff;
    margin-bottom: .75rem; position: relative; z-index:1;
}
.reg-hero-sub {
    color: rgba(255,255,255,.82); font-size:.975rem;
    max-width:520px; margin:0 auto; line-height:1.7; position:relative; z-index:1;
}

/* ── Body section ── */
.reg-body-section { padding: 3rem 0 5rem; background: var(--color-bg-soft); }

/* ── Card layout ── */
.reg-card-wrap {
    display: flex;
    gap: 0;
    background: #fff;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

/* ── Form column ── */
.reg-form-col {
    flex: 1;
    padding: 2.5rem 2rem 2.5rem 2.5rem;
    border-right: 1px solid var(--color-border);
}

/* ── Step headers ── */
.reg-step-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.4rem;
}
.reg-step-badge {
    width: 32px; height: 32px;
    background: var(--gradient-primary);
    color: #fff; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700;
    flex-shrink: 0;
}
.reg-step-title { font-weight: 700; font-size: 1rem; color: var(--color-text); line-height: 1.2; }
.reg-step-sub   { font-size: .78rem; color: var(--color-muted); }

/* ── Input styles ── */
.reg-label {
    display: block;
    font-size: .82rem;
    font-weight: 600;
    color: var(--color-text);
    margin-bottom: .35rem;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.reg-input-wrap { position: relative; }
.reg-input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-muted);
    font-size: .9rem;
    pointer-events: none;
}
.reg-input {
    width: 100%;
    padding: .6rem .85rem .6rem 2.35rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius-sm);
    font-size: .9rem;
    color: var(--color-text);
    background: var(--color-bg-soft);
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    font-family: var(--font-family-base);
}
.reg-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
    background: #fff;
}
.reg-input.is-invalid { border-color: var(--color-error); }
.reg-field-error { font-size: .78rem; color: var(--color-error); margin-top: .3rem; }

/* ── Course selection grid ── */
.course-selection-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.course-sel-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: .7rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: border-color .2s, background .2s, box-shadow .2s;
    user-select: none;
}
.course-sel-item:hover {
    border-color: var(--color-primary);
    background: var(--color-primary-light);
}
.course-sel-item.selected {
    border-color: var(--color-primary);
    background: var(--color-primary-light);
    box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}
.course-sel-item input[type="checkbox"] { display: none; }
.csi-check {
    width: 22px; height: 22px;
    border: 2px solid var(--color-border);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; color: #fff;
    transition: all .2s;
    flex-shrink: 0;
    background: #fff;
}
.course-sel-item.selected .csi-check {
    background: var(--gradient-primary);
    border-color: var(--color-primary);
}
.csi-body { flex: 1; min-width: 0; }
.csi-title { font-size: .875rem; font-weight: 600; color: var(--color-text); line-height: 1.3; }
.csi-cat   { font-size: .75rem; color: var(--color-muted); margin-top: 1px; }
.csi-price {
    font-size: .82rem; font-weight: 700;
    color: var(--color-primary); white-space: nowrap;
    flex-shrink: 0;
}

/* ── Summary panel ── */
.reg-summary-col { width: 320px; flex-shrink: 0; }
.reg-summary-panel { position: sticky; top: 80px; height: 100%; display: flex; flex-direction: column; }

.rsp-header {
    background: var(--gradient-primary);
    color: #fff;
    padding: 1.25rem 1.5rem;
    font-weight: 700;
    font-size: .97rem;
}
.rsp-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; gap: .25rem; }

.rsp-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .875rem;
    color: var(--color-muted);
    margin-bottom: .75rem;
}
.rsp-total-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.25rem;
    background: var(--color-bg-soft);
    border-radius: var(--radius-md);
    border: 1.5px solid var(--color-border);
    margin-bottom: 1rem;
    text-align: center;
}
.rsp-total-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: var(--color-muted); margin-bottom: .3rem; }
.rsp-total-amount { font-size: 1.6rem; font-weight: 800; color: var(--color-primary); line-height: 1.2; }
.rsp-currency { font-size: 1rem; font-weight: 600; margin-right: 3px; color: var(--color-muted); }

.rsp-divider { height: 1px; background: var(--color-border); margin: .5rem 0 1rem; }

.rsp-payment-info { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: var(--radius-sm); padding: .9rem 1rem; margin-bottom: 1.25rem; }
.rsp-pi-title { font-weight: 700; font-size: .85rem; margin-bottom: .5rem; color: var(--color-text); }
.rsp-pi-text  { font-size: .82rem; color: #374151; line-height: 1.6; margin-bottom: .5rem; }
.rsp-account  { font-weight: 800; font-size: .97rem; color: var(--color-primary); display: inline-block; margin-top: 2px; }

.rsp-submit-btn {
    width: 100%;
    background: var(--gradient-primary);
    color: #fff;
    border: none;
    padding: .8rem 1rem;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: .95rem;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-primary);
    margin-top: auto;
}
.rsp-submit-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(79,70,229,.38); }

.rsp-secure-note {
    text-align: center;
    font-size: .75rem;
    color: var(--color-muted);
    margin-top: .75rem;
    margin-bottom: 0;
}

/* ── Responsive ── */
@media (max-width: 991.98px) {
    .reg-card-wrap   { flex-direction: column; }
    .reg-form-col    { border-right: none; border-bottom: 1px solid var(--color-border); padding: 1.75rem 1.25rem; }
    .reg-summary-col { width: 100%; }
    .reg-summary-panel { position: static; }
}
</style>

<script>
(function () {
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
        const item = cb.closest('.course-sel-item');
        item.classList.toggle('selected', cb.checked);
        recalc();
    });

    // Restore visual state for old() values on validation failure
    form.querySelectorAll('.course-checkbox:checked').forEach(cb => {
        cb.closest('.course-sel-item').classList.add('selected');
    });
    recalc();
})();
</script>

@endsection
