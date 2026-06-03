@extends('applayouts.app')

@section('contents')
    <style>
        /* 1. THE VIEWPORT ANCHOR */
        #resource-library-container {
            padding: 15px;
            background: #f8fafc;
            /* This is the secret: It limits the width to the actual screen width */
            width: 100% !important;
            max-width: calc(100vw - 40px) !important;
            overflow-x: hidden !important;
            display: block !important;
        }

        /* 2. HEADER: Forced Wrapping */
        .header-responsive {
            display: flex !important;
            flex-wrap: wrap !important;
            /* Forces right side to drop down if no space */
            justify-content: space-between !important;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            width: 100%;
        }

        /* 3. UPLOAD CARD: Grid Reset */
        .upload-card-pro {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin-bottom: 24px;
            width: 100% !important;
        }

        /* 4. TABLE SAFETY: The "Inner Window" */
        .resource-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 15px;
            width: 100% !important;
            overflow: hidden !important;
            /* Prevents the table from growing the card */
        }

        .table-responsive {
            display: block !important;
            width: 100% !important;
            overflow-x: auto !important;
            /* Table scrolls INSIDE the card */
            -webkit-overflow-scrolling: touch;
        }

        /* 5. UI COMPONENTS */
        .bg-pdf-icon {
            background: #fee2e2;
            color: #ef4444;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* MOBILE SPECIFIC OVERRIDES */
        @media (max-width: 768px) {
            #resource-library-container {
                max-width: calc(100vw - 20px) !important;
                padding: 10px;
            }

            .header-responsive>div {
                width: 100%;
                /* Title takes full width */
            }

            .header-badge-container {
                width: 100%;
                display: flex;
                justify-content: flex-start;
                /* Keeps badge visible on left if pushed */
            }
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div id="resource-library-container">

        <div class="header-responsive">
            <div>
                <h4 class="fw-bold text-dark mb-0">Resources Library</h4>
                <p class="text-muted small mb-0">Subject materials and notes.</p>
            </div>
            <div class="header-badge-container">
                <div class="bg-white border px-3 py-2 rounded-3 shadow-sm d-flex align-items-center">
                    <div class="text-start">
                        <div class="fw-bold text-dark lh-1">42</div>
                        <small class="text-muted" style="font-size: 10px;">TOTAL FILES</small>
                    </div>
                    <i class="fa-solid fa-folder-open ms-3 text-primary opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="upload-card-pro">
            <form action="{{ route('teacher.resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="small fw-bold text-muted mb-1">SUBJECT</label>
                        <select class="form-select bg-light border-0" name="lesson_id" required>
                            <option selected disabled>Select Lesson...</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}">
                                    {{ $lesson->module->title ?? 'General' }} - {{ $lesson->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="small fw-bold text-muted mb-1">TITLE</label>
                        <input type="text" name="title" class="form-control bg-light border-0" placeholder="Chapter 1"
                            required>
                    </div>

                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="small fw-bold text-muted mb-1">FILE</label>
                        <input type="file" name="file" class="form-control bg-light border-0" required>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Post</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="resource-card shadow-sm">
            <div class="table-responsive">
                <table id="studentTable" class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="min-width: 250px;">Material & Subject</th>
                            <th>Category</th>

                            <th>Date Uploaded</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resources as $resource)
                            @php
                                $extension = strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION));
                                $iconClass = match ($extension) {
                                    'pdf' => 'fa-file-pdf text-danger',
                                    'doc', 'docx' => 'fa-file-word text-primary',
                                    'zip', 'rar' => 'fa-file-archive text-warning',
                                    'jpg', 'png', 'jpeg' => 'fa-file-image text-success',
                                    default => 'fa-file-lines text-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="fs-3">
                                            <i class="fa-solid {{ $iconClass }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small mb-0">{{ $resource->title }}</div>
                                            <span class="text-primary fw-bold" style="font-size: 10px;">
                                                {{ $resource->lesson->module->title ?? 'General' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border text-uppercase">
                                        {{ pathinfo($resource->file_path, PATHINFO_EXTENSION) }}
                                    </span>
                                </td>

                                <td class="small text-muted">{{ $resource->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="btn-group shadow-sm rounded">
                                        <a href="{{ asset('storage/' . $resource->file_path) }}" target="_blank"
                                            class="btn btn-sm btn-white border" title="View/Open File">
                                            <i class="fa-solid fa-external-link text-primary me-1"></i> View
                                        </a>



                                        {{-- DELETE BUTTON --}}
                                        <form action="{{ route('teacher.resource.delete', $resource->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-white border text-danger"
                                                onclick="return confirm('Delete this permanent file?')" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
