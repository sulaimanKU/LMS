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

    .lecture-body { padding: 18px; }

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
            <p class="text-muted small mb-0">Library of your recorded sessions and YouTube tutorials.</p>
        </div>
        <button class="btn btn-dark fw-bold px-4 py-2 shadow-sm" style="border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#uploadLectureModal">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Add New Lecture
        </button>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="lecture-card">
                <div class="thumb-wrapper">
                    <img src="https://img.youtube.com/vi/dQw4w9WgXcQ/mqdefault.jpg" alt="Video Thumb">
                    <i class="fa-brands fa-youtube yt-play-btn"></i>
                </div>
                <div class="lecture-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary-subtle text-primary border-primary">B-101</span>
                        <span class="text-muted x-small fw-bold">15 Jan 2026</span>
                    </div>
                    <h5 class="fw-bold text-dark text-truncate">Advanced Laravel Patterns</h5>
                    <p class="text-muted small mb-3">YouTube Link: <span class="text-primary">youtu.be/...</span></p>
                    <div class="d-flex gap-2">
                        <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" class="btn btn-manage flex-grow-1 text-center text-decoration-none">
                            <i class="fa-solid fa-play me-1"></i> Play
                        </a>
                        <button class="btn btn-manage"><i class="fa-solid fa-share-nodes"></i></button>
                        <button class="btn btn-manage text-danger"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>

        </div>
</div>

---

### 2. The "Better" Upload Modal
This modal allows the teacher to paste a link OR upload a file, designed with a modern, clean UI.

```blade
<div class="modal fade" id="uploadLectureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-film me-2"></i>Add Lecture</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">TITLE</label>
                        <input type="text" class="form-control bg-light border-0 py-2" placeholder="e.g. Intro to Data Science" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">SELECT BATCH</label>
                        <select class="form-select bg-light border-0 py-2">
                            <option>CS Batch A</option>
                            <option>CS Batch B</option>
                        </select>
                    </div>

                    <ul class="nav nav-pills nav-fill mb-3 bg-light p-1 rounded-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold small py-2" data-bs-toggle="pill" data-bs-target="#tab-link">YouTube Link</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold small py-2" data-bs-toggle="pill" data-bs-target="#tab-file">Local File</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="tab-link">
                            <div class="mb-3">
                                <label class="form-label x-small fw-bold text-muted">YOUTUBE URL</label>
                                <input type="url" name="yt_url" class="form-control border-primary-subtle py-2" placeholder="[https://youtube.com/watch?v=](https://youtube.com/watch?v=)...">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-file">
                            <div class="mb-3">
                                <label class="form-label x-small fw-bold text-muted">SELECT VIDEO</label>
                                <div class="border-2 border-dashed rounded-3 p-4 text-center bg-light">
                                    <i class="fa-solid fa-file-video fs-2 text-muted mb-2"></i>
                                    <input type="file" class="form-control form-control-sm border-0 bg-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 12px;">
                            Publish Lecture
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
