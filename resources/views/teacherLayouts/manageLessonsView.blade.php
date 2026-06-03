@extends('applayouts.app')

@section('contents')

    {{-- 1. THE TOP SPACER: Forces content to start exactly below your navbar --}}
    <div class="d-block w-100" style="height: 80px;"></div>

    <div class="container-fluid px-md-4">

        {{-- Header Section: Minimal & Blue --}}
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-6">
                <h4 class="fw-bold text-dark mb-1">Curriculum Management</h4>
                <p class="text-muted small mb-0">List of all lessons and modules</p>
            </div>
            <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                <button class="btn btn-primary px-4 fw-bold shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#createLessonModal">
                    <i class="fas fa-plus me-1"></i> New Lesson
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
{{-- ✅ Teacher Modules (Clean Cards UI) --}}
@if($myModules->count() > 0)
    <div class="row mb-4">
        @foreach($myModules as $module)
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark">
                                {{ $module->title }}
                            </h6>

                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                Module
                            </span>
                        </div>

                        <p class="text-muted small mt-2 mb-2">
                            {{ $module->short_description ?? 'No description' }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                {{ $module->duration ?? '--' }}
                            </small>

                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#createLessonModal">
                                Add Lesson
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-warning mb-4">
        No modules assigned to you yet.
    </div>
@endif
        <div class="card border-0 shadow-sm overflow-hidden">

            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0" id="studentTable">
                    <thead class="bg-light">
                        <tr class="small text-muted">
                            <th class="border-0 ps-3">#</th>
                            <th class="border-0">Module</th>
                            <th class="border-0">Title</th>
                            <th class="border-0">Access</th>
                            <th class="border-0 text-center">Video</th>
                            <th class="border-0 text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                   <tbody class="border-top-0">
    @forelse ($lessons as $lesson)
        <tr>
            {{-- 1. Order Number --}}
            <td class="ps-3 text-muted">{{ $lesson->order_number }}</td>

            {{-- 2. Module & Instructor Badge --}}
            <td>
                <span class="badge bg-primary bg-opacity-10 text-primary border-0">
                    {{ $lesson->module->title ?? 'N/A' }}
                </span>
                <span class="badge bg-dark bg-opacity-10 text-dark border-0">
                    {{-- Now that we only show YOUR lessons, we can just show the name directly --}}
                    <i class="fas fa-user-tie me-1"></i> {{ auth()->user()->name }}
                </span>
            </td>

            {{-- 3. Title & Slug --}}
            <td>
                <div class="fw-bold mb-0 text-dark">{{ $lesson->title }}</div>
                <small class="text-muted" style="font-size: 10px;">{{ $lesson->slug }}</small>
            </td>

            {{-- 4. Status (Free/Paid) --}}
            <td>
                @if ($lesson->is_preview)
                    <span class="badge bg-success bg-opacity-10 text-success border-0">Free</span>
                @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">Paid</span>
                @endif
            </td>

            {{-- 5. Video Link --}}
            <td class="text-center">
                @if ($lesson->documnet_path)
                    <a href="{{ $lesson->documnet_path }}" target="_blank" class="text-danger">
                        <i class="fab fa-youtube fs-5"></i>
                    </a>
                @else
                    <span class="text-muted small">---</span>
                @endif
            </td>

            {{-- 6. Actions (Edit/Delete) --}}
            <td class="text-end pe-3">
                <button type="button"
                    class="btn btn-sm btn-outline-primary border-0 edit-lesson-btn"
                    data-id="{{ $lesson->id }}"
                    data-title="{{ $lesson->title }}"
                    data-short="{{ $lesson->short_text }}"
                    data-order="{{ $lesson->order_number }}"
                    data-video="{{ $lesson->documnet_path }}"
                    data-content="{{ $lesson->full_content }}"
                    data-module="{{ $lesson->module_id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#editLessonModal">
                    <i class="fas fa-edit"></i>
                </button>

                <form action="{{ route('teacher.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                        onclick="return confirm('Are you sure you want to delete this lesson?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                    <p>No lessons found. Start by adding a new lesson to your courses!</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal remains the same as your provided code --}}
    <div class="modal fade" id="createLessonModal" tabindex="-1" aria-hidden="true" style="z-index: 2000;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold mb-0">Add New Lesson</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('teacher.lessons.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label small text-muted mb-1">Module</label>
                                <select name="module_id" class="form-select border-0 bg-light shadow-none" required>
                                    <option value="" selected disabled>Select Module</option>
                                    @foreach ($myModules as $module)
                                        <option value="{{ $module->id }}">{{ $module->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Order #</label>
                                <input type="number" name="order_number" class="form-control border-0 bg-light shadow-none"
                                    value="1" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Lesson Title</label>
                                <input type="text" name="title" class="form-control border-0 bg-light shadow-none"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Short Summary (Optional)</label>
                                <input type="text" name="short_text" class="form-control border-0 bg-light shadow-none"
                                    placeholder="Brief description...">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Video URL</label>
                                <input type="url" name="documnet_path"
                                    class="form-control border-0 bg-light shadow-none" placeholder="YouTube/Vimeo link">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Full Content</label>
                                <textarea name="full_content" rows="4" class="form-control border-0 bg-light shadow-none"
                                    placeholder="Lesson details..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm text-muted px-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">Save Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editLessonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h6 class="fw-bold mb-0">Edit Lesson</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                {{-- Note: The action will be updated by JS --}}
                <form id="editLessonForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small text-muted mb-1">Module</label>
                                <select name="module_id" id="edit_module_id" class="form-select border-0 bg-light"
                                    required>
                                    @foreach ($myModules as $module)
                                        <option value="{{ $module->id }}">{{ $module->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Order #</label>
                                <input type="number" name="order_number" id="edit_order_number"
                                    class="form-control border-0 bg-light">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Lesson Title</label>
                                <input type="text" name="title" id="edit_title"
                                    class="form-control border-0 bg-light" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Video URL</label>
                                <input type="url" name="documnet_path" id="edit_video_url"
                                    class="form-control border-0 bg-light">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Short Description</label>
                                <textarea name="short_text" id="edit_short_text" rows="2" class="form-control border-0 bg-light"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Long Description</label>
                                <textarea name="full_content" id="edit_full_content" rows="2" class="form-control border-0 bg-light"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary px-4">Update Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtns = document.querySelectorAll('.edit-lesson-btn');
            const editForm = document.getElementById('editLessonForm');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const title = this.getAttribute('data-title');
                    const short = this.getAttribute('data-short');
                    const long = this.getAttribute('data-content');
                    const order = this.getAttribute('data-order');
                    const video = this.getAttribute('data-video');
                    const moduleId = this.getAttribute('data-module');


                    editForm.action = `/teacher/lessons/update/${id}`;


                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_short_text').value = short;
                    document.getElementById('edit_order_number').value = order;
                    document.getElementById('edit_video_url').value = video;
                    document.getElementById('edit_module_id').value = moduleId;
                    document.getElementById('edit_full_content').value = long;
                });
            });
        });
    </script>
@endsection
