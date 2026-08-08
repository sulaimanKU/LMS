@extends('applayouts.app')

@push('styles')
    {{-- Summernote CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame { border: 1.5px solid #E2E8F0 !important; border-radius: 12px !important; overflow: hidden; }
        .note-toolbar { background: #F8FAFF !important; border-bottom: 1.5px solid #E2E8F0 !important; }
    </style>
@endpush

@section('contents')

<div class="cr-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #ECFDF5; border-left: 4px solid #10B981 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
                <span class="fw-semibold text-dark">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
                <span class="fw-semibold text-dark">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FFFBEB; border-left: 4px solid #F59E0B !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fs-5 me-2 text-warning"></i>
                <span class="fw-semibold text-dark">{{ session('warning') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #EEF2FF; border-left: 4px solid #6366F1 !important;">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info fs-5 me-2 text-primary"></i>
                <span class="fw-semibold text-dark">{{ session('info') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 14px; background: #FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger mt-1"></i>
                <div>
                    <strong class="text-dark d-block mb-1">Please correct the following errors:</strong>
                    <ul class="mb-0 ps-3 text-danger">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="cr-header mb-4">
        <div>
            <h5 class="cr-title"><i class="fa-solid fa-plus-circle me-2"></i>Create New Course</h5>
            <p class="cr-subtitle">Fill in the details to launch a new module</p>
        </div>
        <a href="{{ route('course.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-4 p-md-5">
                
                <div class="row g-4">
                    {{-- Title & Workshop Number --}}
                    <div class="col-md-9">
                        <label class="cr-label">Course Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="cr-input shadow-none @error('title') is-invalid @enderror" placeholder="e.g. Advanced Quantum Physics" required value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="cr-label">Workshop # <span class="text-muted small">(Optional)</span></label>
                        <input type="number" name="workshop_number" class="cr-input shadow-none @error('workshop_number') is-invalid @enderror" placeholder="1" value="{{ old('workshop_number') }}">
                        @error('workshop_number')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Category & Status --}}
                    <div class="col-md-6">
                        <label class="cr-label">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="cr-input shadow-none @error('category') is-invalid @enderror" placeholder="e.g. Science" required value="{{ old('category') }}">
                        @error('category')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="cr-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="cr-input shadow-none @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') !== 'inactive' ? 'selected' : '' }}>Active (Visible to public)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Price & Duration --}}
                    <div class="col-md-6">
                        <label class="cr-label">Price (PKR) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="cr-input shadow-none @error('price') is-invalid @enderror" placeholder="3000" min="0" required value="{{ old('price', 3000) }}">
                        @error('price')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="cr-label">Duration <span class="text-muted small">(Optional)</span></label>
                        <input type="text" name="duration" class="cr-input shadow-none @error('duration') is-invalid @enderror" placeholder="e.g. 4 Weeks / 20 Hours" value="{{ old('duration') }}">
                        @error('duration')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="cr-label">Short Description <span class="text-muted small">(Appears on course cards)</span></label>
                        <input type="text" name="short_description" class="cr-input shadow-none @error('short_description') is-invalid @enderror" placeholder="A brief one-line summary..." value="{{ old('short_description') }}">
                        @error('short_description')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Full Details (Summernote) --}}
                    <div class="col-12">
                        <label class="cr-label">Detailed Course Information <span class="text-muted small">(Rich Text)</span></label>
                        <textarea name="details" id="details_editor" class="summernote shadow-none @error('details') is-invalid @enderror">{{ old('details') }}</textarea>
                        @error('details')
                            <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Course Image --}}
                    <div class="col-12">
                        <label class="cr-label">Featured Image</label>
                        <div class="d-flex align-items-start gap-3 flex-wrap">
                            <div id="imagePreviewContainer" style="display:none;">
                                <img id="imagePreview" src="" style="width:180px; height:120px; object-fit:cover; border-radius:12px; border:2px solid #E2E8F0;">
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="image" id="courseImageInput" class="cr-input @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback d-block text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <p class="text-muted small mt-1">Recommended size: 800x500px. Max size: 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light p-4 text-end">
                <button type="button" onclick="history.back()" class="btn btn-light px-4 me-2 rounded-pill fw-bold border">Cancel</button>
                <button type="submit" class="cr-btn-save rounded-pill px-5 py-2">
                    <i class="fa-solid fa-rocket me-2"></i>Publish Course
                </button>
            </div>
        </form>
    </div>

</div>

<style>
.cr-page { padding: 2rem; background: #F8FAFF; min-height: 100vh; }
.cr-title { font-size: 1.5rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-subtitle { font-size: .9rem; color: #64748B; margin: .2rem 0 0; }
.cr-label { font-size: .75rem; font-weight: 800; color: #4F46E5; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; display: block; }
.cr-input {
    width: 100%; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 12px 16px;
    font-size: .95rem; color: #1E293B; background: #fff; transition: all .2s;
}
.cr-input:focus { border-color: #4F46E5; box-shadow: 0 0 0 4px rgba(79,70,229,.1); outline: none; }
.cr-input.is-invalid { border-color: #EF4444 !important; }
.cr-input.is-invalid:focus { box-shadow: 0 0 0 4px rgba(239,68,68,.15) !important; }
.invalid-feedback { font-size: .8rem; font-weight: 500; }
.cr-btn-save {
    background: linear-gradient(135deg, #4F46E5, #7C3AED); color: #fff;
    border: none; font-weight: 700; box-shadow: 0 10px 20px rgba(79,70,229,.25);
    transition: all 0.3s;
}
.cr-btn-save:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(79,70,229,.35); color: #fff; }

/* Summernote custom styling */
.note-editor.note-frame { border: 1.5px solid #E2E8F0 !important; border-radius: 12px !important; overflow: hidden; }
.note-toolbar { background: #F8FAFF !important; border-bottom: 1.5px solid #E2E8F0 !important; }
</style>

@push('scripts')
    {{-- Summernote JS --}}
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Write the complete course description here. You can include syllabus, learning outcomes, and requirements...',
            tabsize: 2,
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'strikethrough']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Image preview
        $('#courseImageInput').on('change', function() {
            const file = this.files[0];
            if (file) {
                $('#imagePreview').attr('src', URL.createObjectURL(file));
                $('#imagePreviewContainer').fadeIn();
            }
        });
    });
    </script>
@endpush
@endsection
