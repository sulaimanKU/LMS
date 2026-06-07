@extends('welcome')
@section('content')

<style>
.pu-wrap { max-width: 560px; margin: 0 auto; padding: 2.5rem 1rem; }

.pu-card {
    background: #fff; border-radius: 20px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    overflow: hidden;
}
.pu-payment-info {
    background: #F8FAFF;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #E2E8F0;
}
.pu-payment-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: #4F46E5;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.pu-method-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}
@media (min-width: 480px) {
    .pu-method-grid { grid-template-columns: 1fr 1fr; }
}
.pu-method-item {
    background: #fff;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid #EDF2F7;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.pu-method-label {
    font-size: 0.65rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    display: block;
    margin-bottom: 0.25rem;
}
.pu-method-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1E293B;
}
.pu-bank-full {
    grid-column: 1 / -1;
}
.pu-card-head {
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    padding: 2rem 2rem 1.5rem; text-align: center;
}
.pu-card-head-icon {
    width: 64px; height: 64px; border-radius: 16px;
    background: rgba(255,255,255,.2); margin: 0 auto .9rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; color: #fff;
}
.pu-card-head h3 { font-size: 1.1rem; font-weight: 800; color: #fff; margin: 0 0 .3rem; }
.pu-card-head p  { font-size: .82rem; color: rgba(255,255,255,.75); margin: 0; }
.pu-card-body { padding: 1.75rem 2rem; }

/* Fields */
.pu-label {
    font-size: .72rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .6px; color: #64748B; display: block; margin-bottom: .4rem;
}
.pu-input {
    width: 100%; border: 1.5px solid #E2E8F0; border-radius: 10px;
    padding: .6rem .9rem; font-size: .9rem; color: #1E293B;
    background: #fff; outline: none; transition: border-color .15s;
}
.pu-input:focus { border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,.08); }

/* Upload zone */
.pu-drop {
    border: 2px dashed #C7D2FE; border-radius: 12px;
    background: #F8FAFF; padding: 1.5rem 1rem;
    text-align: center; position: relative; cursor: pointer;
    transition: all .2s;
}
.pu-drop:hover, .pu-drop.drag-over { border-color: #4F46E5; background: #EEF2FF; }
.pu-drop input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
}
.pu-drop-icon { font-size: 2rem; color: #A5B4FC; margin-bottom: .5rem; }
.pu-drop-text { font-size: .82rem; color: #64748B; margin: 0 0 .4rem; }
.pu-drop-types { font-size: .7rem; color: #94A3B8; }

/* Preview */
.pu-preview { display: none; text-align: center; }
.pu-preview img { max-height: 180px; border-radius: 10px; object-fit: contain; margin-bottom: .5rem; }
.pu-preview-name {
    font-size: .8rem; font-weight: 600; color: #1E293B;
    display: flex; align-items: center; justify-content: center; gap: .4rem; margin-bottom: .3rem;
}
.pu-remove {
    font-size: .75rem; color: #EF4444; cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: .3rem;
}

/* Submit */
.pu-submit {
    width: 100%; padding: .75rem; border: none; border-radius: 10px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    font-size: .9rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 4px 14px rgba(79,70,229,.3); transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.pu-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
.pu-submit:disabled { opacity: .7; cursor: not-allowed; }

/* Info strip */
.pu-info-strip {
    display: flex; align-items: flex-start; gap: .6rem;
    background: #F8FAFF; border: 1px solid #E2E8F0; border-radius: 10px;
    padding: .75rem 1rem; font-size: .78rem; color: #64748B; margin-top: 1rem;
}
.pu-info-strip i { color: #4F46E5; margin-top: 1px; flex-shrink: 0; }

/* Track link */
.pu-track-link { text-align: center; margin-top: 1.25rem; font-size: .82rem; color: #64748B; }
.pu-track-link a { color: #4F46E5; font-weight: 600; text-decoration: none; }
.pu-track-link a:hover { text-decoration: underline; }
</style>

<div class="pu-wrap">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 px-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="pu-payment-info">
        <div class="pu-payment-title"><i class="bi bi-bank2"></i> Payment Account Details</div>
        <div class="pu-method-grid">
            @if(isset($systemSettings['payment_easypaisa']))
            <div class="pu-method-item" style="border-left: 4px solid #4F46E5;">
                <span class="pu-method-label">EasyPaisa</span>
                <span class="pu-method-value">{{ $systemSettings['payment_easypaisa'] }}</span>
            </div>
            @endif

            @if(isset($systemSettings['payment_jazzcash']))
            <div class="pu-method-item" style="border-left: 4px solid #EF4444;">
                <span class="pu-method-label">JazzCash</span>
                <span class="pu-method-value">{{ $systemSettings['payment_jazzcash'] }}</span>
            </div>
            @endif

            @if(isset($systemSettings['payment_bank_name']))
            <div class="pu-method-item pu-bank-full" style="border-left: 4px solid #10B981;">
                <span class="pu-method-label">Bank Account</span>
                <div class="d-flex flex-wrap gap-x-4 gap-y-1">
                    <div style="font-size: 0.85rem;"><span class="text-muted">Bank:</span> <strong>{{ $systemSettings['payment_bank_name'] }}</strong></div>
                    @if(isset($systemSettings['payment_bank_title']))
                    <div style="font-size: 0.85rem;"><span class="text-muted">Title:</span> <strong>{{ $systemSettings['payment_bank_title'] }}</strong></div>
                    @endif
                    @if(isset($systemSettings['payment_bank_number']))
                    <div style="font-size: 0.85rem;"><span class="text-muted">A/C:</span> <strong>{{ $systemSettings['payment_bank_number'] }}</strong></div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="pu-card">

        <div class="pu-card-head">
            <div class="pu-card-head-icon"><i class="bi bi-receipt-cutoff"></i></div>
            <h3>Payment Verification</h3>
            <p>Upload your bank transaction slip for verification.<br>You'll receive login credentials by email once approved.</p>
        </div>

        <div class="pu-card-body">
            <form action="{{ route('payment.submit') }}" method="POST" enctype="multipart/form-data" id="pu-form">
                @csrf

                <div class="mb-4">
                    <label class="pu-label" for="pu-email">Registered Email Address <span style="color:#EF4444;">*</span></label>
                    <input type="email" id="pu-email" name="email" class="pu-input"
                           placeholder="your@email.com"
                           value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label class="pu-label">Payment Slip / Transaction Screenshot <span style="color:#EF4444;">*</span></label>

                    <div class="pu-drop" id="pu-drop">
                        <input type="file" name="slip" id="pu-file"
                               accept="image/jpeg,image/png,image/webp,application/pdf" required>
                        <div id="pu-drop-content">
                            <div class="pu-drop-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <p class="pu-drop-text"><strong>Click to browse</strong> or drag & drop your file here</p>
                            <p class="pu-drop-types">JPG, PNG, WEBP, PDF &nbsp;·&nbsp; Max 4 MB</p>
                        </div>
                        <div class="pu-preview" id="pu-preview">
                            <img id="pu-img-preview" src="" alt="preview">
                            <div id="pu-pdf-preview" style="display:none;padding:1rem;">
                                <i class="bi bi-file-earmark-pdf" style="font-size:2.5rem;color:#EF4444;"></i>
                            </div>
                            <div class="pu-preview-name">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span id="pu-file-name">file.jpg</span>
                            </div>
                            <a href="#" class="pu-remove" id="pu-remove">
                                <i class="bi bi-x-circle"></i> Remove & change file
                            </a>
                        </div>
                    </div>
                </div>

                <button type="submit" class="pu-submit" id="pu-submit">
                    <span class="spinner-border spinner-border-sm d-none" id="pu-spinner"></span>
                    <i class="bi bi-send-check" id="pu-btn-icon"></i>
                    <span id="pu-btn-text">Submit for Verification</span>
                </button>
            </form>

            <div class="pu-info-strip">
                <i class="bi bi-info-circle-fill"></i>
                <span>After upload, our admin will verify your slip and send your username & password to your registered email. This usually takes 1–2 business days.</span>
            </div>
        </div>
    </div>

    <div class="pu-track-link">
        Already submitted? <a href="{{ route('home') }}#track">Track your registration status</a>
        &nbsp;·&nbsp;
        <a href="{{ route('home') }}">Back to Home</a>
    </div>

</div>

<script>
(function () {
    const fileInput    = document.getElementById('pu-file');
    const dropZone     = document.getElementById('pu-drop');
    const dropContent  = document.getElementById('pu-drop-content');
    const preview      = document.getElementById('pu-preview');
    const imgPreview   = document.getElementById('pu-img-preview');
    const pdfPreview   = document.getElementById('pu-pdf-preview');
    const fileName     = document.getElementById('pu-file-name');
    const removeBtn    = document.getElementById('pu-remove');
    const form         = document.getElementById('pu-form');
    const submitBtn    = document.getElementById('pu-submit');
    const spinner      = document.getElementById('pu-spinner');
    const btnIcon      = document.getElementById('pu-btn-icon');
    const btnText      = document.getElementById('pu-btn-text');

    function showPreview(file) {
        fileName.textContent = file.name;
        if (file.type === 'application/pdf') {
            imgPreview.style.display = 'none';
            pdfPreview.style.display = 'block';
        } else {
            pdfPreview.style.display = 'none';
            imgPreview.style.display = 'block';
            imgPreview.src = URL.createObjectURL(file);
        }
        dropContent.style.display = 'none';
        preview.style.display = 'block';
    }

    function resetPreview() {
        fileInput.value = '';
        imgPreview.src = '';
        preview.style.display = 'none';
        dropContent.style.display = 'block';
    }

    fileInput.addEventListener('change', function () {
        if (this.files[0]) showPreview(this.files[0]);
    });

    removeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        resetPreview();
    });

    // Drag & drop
    ['dragenter','dragover'].forEach(evt => {
        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    });
    ['dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, e => { e.preventDefault(); dropZone.classList.remove('drag-over'); });
    });
    dropZone.addEventListener('drop', function (e) {
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showPreview(file);
        }
    });

    // Loading state on submit
    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnIcon.classList.add('d-none');
        btnText.textContent = 'Uploading…';
    });
})();
</script>

@endsection
