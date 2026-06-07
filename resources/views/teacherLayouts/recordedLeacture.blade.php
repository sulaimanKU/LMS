@extends('applayouts.app')

@section('contents')
<style>
    :root {
        --yt-red: #ff0000;
        --card-radius: 20px;
    }

    #recordings-page {
        padding: 25px;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* VIDEO CARD DESIGN */
    .lecture-card {
        background: white;
        border-radius: var(--card-radius);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .lecture-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .thumb-wrapper {
        position: relative;
        overflow: hidden;
        background: #000;
        aspect-ratio: 16/9;
    }

    .thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.8;
        transition: 0.5s;
    }

    .lecture-card:hover .thumb-wrapper img {
        opacity: 1;
        scale: 1.1;
    }

    .yt-play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 50px;
        text-shadow: 0 4px 10px rgba(0,0,0,0.5);
        pointer-events: none;
    }

    .lecture-body { padding: 18px; flex-grow: 1; display: flex; flex-direction: column; }

    .btn-manage {
        padding: 8px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s;
    }
    .btn-manage:hover { background: #6366f1; color: white; }

</style>

<div id="recordings-page">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Lecture Vault</h2>
            <p class="text-muted small mb-0">Library of your recorded sessions and video materials.</p>
        </div>
        <button class="btn btn-dark fw-bold px-4 py-2 shadow-sm" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#addLectureModal">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Add New Recording
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($recordedLessons as $lesson)
            @php
                // YouTube Thumbnail Extraction
                $url = $lesson->documnet_path;
                $videoId = '';
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                    $videoId = $matches[1];
                }
                $thumbUrl = $videoId ? "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg" : "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop";
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
                <div class="lecture-card">
                    <div class="thumb-wrapper">
                        <img src="{{ $thumbUrl }}" alt="Video Thumbnail">
                        <i class="fa-brands fa-youtube yt-play-btn"></i>
                    </div>
                    <div class="lecture-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary border-primary">{{ $lesson->module->title ?? 'N/A' }}</span>
                            <span class="text-muted small fw-bold">{{ $lesson->created_at->format('d M Y') }}</span>
                        </div>
                        <h5 class="fw-bold text-dark text-truncate" title="{{ $lesson->title }}">{{ $lesson->title }}</h5>
                        <p class="text-muted small mb-3 text-truncate">Source: <span class="text-primary">{{ $lesson->documnet_path }}</span></p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ $lesson->documnet_path }}" target="_blank" class="btn btn-manage flex-grow-1 text-center text-decoration-none">
                                <i class="fa-solid fa-play me-1"></i> Play Recording
                            </a>
                            <form action="{{ route('teacher.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Delete this recording?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-manage text-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                    <i class="fa-solid fa-video-slash fs-1 text-muted opacity-25 mb-3"></i>
                    <p class="text-muted mb-0">No recorded lectures found for your modules.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL — Add New Recording (Reuses Lesson Storage) --}}
<div class="modal fade" id="addLectureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-film me-2"></i>Add Recorded Lecture</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('teacher.lessons.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_number" value="{{ $recordedLessons->count() + 1 }}">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">MODULE / COURSE</label>
                        <select class="form-select bg-light border-0 py-2" name="module_id" required>
                            <option selected disabled>Choose module...</option>
                            @foreach($myModules as $mod)
                                <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">LECTURE TITLE</label>
                        <input type="text" name="title" class="form-control bg-light border-0 py-2" placeholder="e.g. Session 1: Research Methodology" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">VIDEO URL (YOUTUBE/DRIVE)</label>
                        <input type="url" name="documnet_path" class="form-control border-primary-subtle py-2" placeholder="https://youtube.com/watch?v=..." required>
                        <small class="text-muted" style="font-size: 0.65rem;">Paste the full link to the recorded video.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">SHORT DESCRIPTION (OPTIONAL)</label>
                        <textarea name="short_text" class="form-control bg-light border-0" rows="2" placeholder="Brief summary of the lecture..."></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 12px;">
                            <i class="fa-solid fa-paper-plane me-2"></i>Publish to Student Portal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
