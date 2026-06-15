@extends('applayouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('contents')
<div class="cr-page">

    <div class="cr-header mb-4">
        <div>
            <h5 class="cr-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Workshop</h5>
            <p class="cr-subtitle">Updating: <strong>{{ $workshop->title }}</strong></p>
        </div>
        <a href="{{ route('workshops.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <form action="{{ route('workshops.update', $workshop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-4 p-md-5">
                
                <div class="row g-4">
                    <div class="col-md-9">
                        <label class="cr-label">Workshop Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="cr-input shadow-none" required value="{{ old('title', $workshop->title) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="cr-label">Edition #</label>
                        <input type="number" name="workshop_number" class="cr-input shadow-none" value="{{ old('workshop_number', $workshop->workshop_number) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="cr-label">Price (PKR) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="cr-input shadow-none" min="0" required value="{{ old('price', $workshop->price) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="cr-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="cr-input shadow-none">
                            <option value="active" {{ $workshop->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $workshop->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="cr-label">Short Summary</label>
                        <input type="text" name="short_description" class="cr-input shadow-none" value="{{ old('short_description', $workshop->short_description) }}">
                    </div>

                    <div class="col-12">
                        <label class="cr-label">Workshop Details</label>
                        <textarea name="details" class="summernote">{{ old('details', $workshop->details) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="cr-label">Workshop Banner</label>
                        @if($workshop->image)
                            <div class="mb-2"><img src="{{ asset('storage/'.$workshop->image) }}" style="width:150px; border-radius:10px;"></div>
                        @endif
                        <input type="file" name="image" class="cr-input" accept="image/*">
                    </div>
                </div>

            </div>
            <div class="card-footer bg-light p-4 text-end">
                <button type="submit" class="cr-btn-save rounded-pill px-5 py-2">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
.cr-page { padding: 2rem; background: #F8FAFF; min-height: 100vh; }
.cr-label { font-size: .75rem; font-weight: 800; color: #4F46E5; text-transform: uppercase; margin-bottom: 8px; display: block; }
.cr-input { width: 100%; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 12px 16px; }
.cr-btn-save { background: linear-gradient(135deg, #4F46E5, #7C3AED); color: #fff; border: none; font-weight: 700; box-shadow: 0 10px 20px rgba(79,70,229,.25); }
</style>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({ height: 300 });
        });
    </script>
@endpush
@endsection
