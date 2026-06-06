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

    <div class="cr-header mb-4">
        <div>
            <h5 class="cr-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Course</h5>
            <p class="cr-subtitle">Update information for: <strong>{{ $course->title }}</strong></p>
        </div>
        <a href="{{ route('course.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <form action="{{ route('course.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-4 p-md-5">
                
                <div class="row g-4">
                    {{-- Title --}}
                    <div class="col-12">
                        <label class="cr-label">Course Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="cr-input shadow-none" value="{{ old('title', $course->title) }}" required>
                    </div>

                    {{-- Category & Status --}}
                    <div class="col-md-6">
                        <label class="cr-label">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="cr-input shadow-none" value="{{ old('category', $course->category) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="cr-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="cr-input shadow-none">
                            <option value="active" {{ old('status', $course->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $course->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Price & Duration --}}
                    <div class="col-md-6">
                        <label class="cr-label">Price (PKR) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="cr-input shadow-none" min="0" required value="{{ old('price', $course->price) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="cr-label">Duration <span class="text-muted small">(Optional)</span></label>
                        <input type="text" name="duration" class="cr-input shadow-none" value="{{ old('duration', $course->duration) }}">
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="cr-label">Short Description</label>
                        <input type="text" name="short_description" class="cr-input shadow-none" value="{{ old('short_description', $course->short_description) }}">
                    </div>

                    {{-- Full Details (Summernote) --}}
                    <div class="col-12">
                        <label class="cr-label">Detailed Course Information</label>
                        <textarea name="details" id="details_editor" class="summernote shadow-none">{{ old('details', $course->details) }}</textarea>
                    </div>

                    {{-- Course Image --}}
                    <div class="col-12">
                        <label class="cr-label">Featured Image</label>
                        <div class="d-flex align-items-start gap-4 flex-wrap">
                            {{-- Current Image --}}
                            <div class="text-center">
                                <p class="small text-muted mb-2">Current Image</p>
                                @if($course->image)
                                    <img src="{{ asset('storage/'.$course->image) }}" style="width:180px; height:120px; object-fit:cover; border-radius:12px; border:2px solid #E2E8F0;">
                                @else
                                    <div style="width:180px; height:120px; border-radius:12px; border:2px dashed #CBD5E1; display:flex; align-items:center; justify-content:center; background:#F8FAFF; color:#94A3B8;">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            {{-- New Preview --}}
                            <div id="imagePreviewContainer" style="display:none;" class="text-center">
                                <p class="small text-primary mb-2">New Preview</p>
                                <img id="imagePreview" src="" style="width:180px; height:120px; object-fit:cover; border-radius:12px; border:2px solid var(--brand-primary);">
                            </div>

                            <div class="flex-grow-1">
                                <input type="file" name="image" id="courseImageInput" class="cr-input" accept="image/*">
                                <p class="text-muted small mt-2">Upload a new image to replace the existing one. Recommended size: 800x500px.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light p-4 text-end">
                <button type="button" onclick="history.back()" class="btn btn-light px-4 me-2 rounded-pill fw-bold border">Cancel</button>
                <button type="submit" class="cr-btn-save rounded-pill px-5 py-2">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
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
            placeholder: 'Detailed course description...',
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
