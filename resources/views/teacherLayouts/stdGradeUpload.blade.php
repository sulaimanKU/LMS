@extends('applayouts.app')

@section('contents')
<style>
    :root {
        --upload-primary: #4f46e5;
        --excel-green: #107c41;
    }

    #progress-upload-hub {
        padding: 30px 15px;
        background: #f8fafc;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* 1. CENTERED FORM CONTAINER */
    .form-container {
        width: 100%;
        max-width: 700px; /* Limits width for better readability */
    }

    .upload-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 35px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
    }

    /* 2. FORM STYLING */
    .form-label-custom {
        font-weight: 700;
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 10px;
        display: block;
    }

    .input-pro {
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 14px;
        padding: 14px 18px;
        transition: 0.3s;
        font-weight: 500;
        width: 100%;
    }

    .input-pro:focus {
        background: #fff;
        border-color: var(--upload-primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    /* 3. FILE SELECTOR */
    .file-drop-area {
        position: relative;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        transition: 0.3s;
    }

    .file-drop-area:hover {
        border-color: var(--upload-primary);
        background: #f5f7ff;
    }

    /* 4. MAIN ACTION BUTTON */
    .btn-upload-now {
        background: var(--upload-primary);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-upload-now:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
    }

    /* TEMPLATE LINK */
    .template-link {
        color: var(--excel-green);
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .template-link:hover { text-decoration: underline; }

</style>

<div id="progress-upload-hub">

    <div class="form-container">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark mb-1">Bulk Progress Upload</h2>
            <p class="text-muted small">Import student grades or attendance using a spreadsheet.</p>
        </div>

        <div class="upload-card">
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label-custom">Select Module/Course</label>
                        <a href="#" class="template-link" title="Download sample CSV format">
                            <i class="fa-solid fa-file-excel"></i> Get Template
                        </a>
                    </div>
                    <select class="form-select input-pro" name="module_id" required>
                        <option selected disabled>Which module is this for?</option>
                        @foreach($myModules as $mod)
                            <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom">Import Category</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="dataType" id="marks" value="marks" checked>
                            <label class="btn btn-outline-primary w-100 fw-bold py-3 rounded-4" for="marks">
                                <i class="fa-solid fa-graduation-cap d-block mb-1 fs-5"></i>
                                <span style="font-size: 0.75rem;">Exam Marks</span>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="dataType" id="attendance" value="attendance">
                            <label class="btn btn-outline-primary w-100 fw-bold py-3 rounded-4" for="attendance">
                                <i class="fa-solid fa-calendar-check d-block mb-1 fs-5"></i>
                                <span style="font-size: 0.75rem;">Attendance</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label-custom">Upload Spreadsheet (CSV/XLSX)</label>
                    <div class="file-drop-area">
                        <i class="fa-solid fa-cloud-arrow-up fs-2 text-primary opacity-50 mb-2"></i>
                        <input type="file" name="spreadsheet" class="form-control" id="bulkFile" accept=".csv, .xlsx, .xls" required>
                        <p class="small text-muted mt-2 mb-0">Max size 5MB. Ensure Email column matches student records.</p>
                    </div>
                </div>

                <button type="submit" class="btn-upload-now shadow-sm">
                    <i class="fa-solid fa-bolt fs-5"></i>
                    Process and Save Data
                </button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('teacher.main') }}" class="text-muted small text-decoration-none fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

</div>
@endsection
