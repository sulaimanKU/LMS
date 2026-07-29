@extends('applayouts.app')

@section('contents')
<div class="ml-page">

    {{-- ── Alerts ── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="ml-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="ml-header-badge"><i class="fa-solid fa-chalkboard me-1.5"></i> Curriculum Manager</div>
                <h4 class="ml-title"><i class="fa-solid fa-layer-group text-indigo me-2"></i>Course Lessons & Content</h4>
                <p class="ml-subtitle">Create and organize video lessons, reading materials, and curriculum order</p>
            </div>
            <div>
                <button class="btn ml-btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createLessonModal">
                    <i class="fa-solid fa-plus me-1.5"></i>Create New Lesson
                </button>
            </div>
        </div>
    </div>

    {{-- ── Analytics KPI Stat Cards ── --}}
    @php
        $videoCount   = $lessons->whereNotNull('documnet_path')->count();
        $previewCount = $lessons->where('is_preview', true)->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="ml-stat-card ml-stat-indigo">
                <div class="ml-stat-icon"><i class="fa-solid fa-book"></i></div>
                <div>
                    <h3 class="ml-stat-num">{{ $myModules->count() }}</h3>
                    <span class="ml-stat-lbl">Assigned Modules</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ml-stat-card ml-stat-emerald">
                <div class="ml-stat-icon"><i class="fa-solid fa-list-check"></i></div>
                <div>
                    <h3 class="ml-stat-num">{{ $lessons->count() }}</h3>
                    <span class="ml-stat-lbl">Total Lessons</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ml-stat-card ml-stat-amber">
                <div class="ml-stat-icon"><i class="fa-solid fa-circle-play"></i></div>
                <div>
                    <h3 class="ml-stat-num">{{ $videoCount }}</h3>
                    <span class="ml-stat-lbl">Video Lectures</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="ml-stat-card ml-stat-purple">
                <div class="ml-stat-icon"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <h3 class="ml-stat-num">{{ $previewCount }}</h3>
                    <span class="ml-stat-lbl">Free Previews</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Teacher Assigned Modules Grid ── --}}
    @if($myModules->count() > 0)
        <div class="row g-3 mb-4">
            @foreach($myModules as $module)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="ml-module-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-indigo-light text-indigo border border-indigo-subtle" style="font-size:0.68rem; font-weight:700;">
                                <i class="fa-solid fa-cube me-1"></i>MODULE
                            </span>
                            <span class="text-muted small" style="font-size:0.7rem;"><i class="fa-regular fa-clock me-1 text-amber"></i>{{ $module->duration ?? 'Self-paced' }}</span>
                        </div>
                        <h6 class="ml-mod-title text-truncate" title="{{ $module->title }}">{{ $module->title }}</h6>
                        <p class="ml-mod-desc">{{ Str::limit($module->short_description ?? 'Build your course curriculum by adding structured lessons.', 75) }}</p>
                        <div class="ml-mod-footer">
                            <span class="text-muted small" style="font-size:0.72rem;"><i class="fa-solid fa-list-ul me-1 text-indigo"></i>{{ $lessons->where('module_id', $module->id)->count() }} lessons</span>
                            <button class="btn btn-sm btn-light text-indigo border shadow-sm rounded-pill px-3 py-1" style="font-size:0.72rem; font-weight:700;"
                                    data-bs-toggle="modal" data-bs-target="#createLessonModal" data-module-id="{{ $module->id }}">
                                <i class="fa-solid fa-plus me-1"></i>Add Lesson
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning mb-4 rounded-3 border-0 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> No modules assigned to your teacher profile yet.
        </div>
    @endif

    {{-- ── Curriculum Lessons Table Card ── --}}
    <div class="ml-table-card mb-4">
        <div class="ml-table-head d-flex align-items-center justify-content-between">
            <span class="ml-table-title"><i class="fa-solid fa-layer-group me-2 text-indigo"></i>Full Curriculum & Lesson List</span>
            <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace" style="font-size:0.75rem;">
                Total: {{ $lessons->count() }} Lessons
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="lessonsTable">
                <thead>
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">Order</th>
                        <th class="py-3" style="width: 30%;">Lesson Title & Slug</th>
                        <th class="py-3">Associated Module</th>
                        <th class="py-3">Access Level</th>
                        <th class="text-center py-3">Video Link</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lessons as $lesson)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1.5" style="font-size:0.75rem; font-weight:700;">
                                    #{{ $lesson->order_number }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-bold text-dark d-block mb-0.5" style="font-size:0.9rem;">{{ $lesson->title }}</span>
                                <span class="text-muted font-monospace" style="font-size:0.72rem;"><i class="fa-solid fa-link me-1 text-muted"></i>{{ $lesson->slug }}</span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-indigo-light text-indigo border border-indigo-subtle px-2.5 py-1.5" style="font-size:0.72rem; font-weight:600;">
                                    <i class="fa-solid fa-book-bookmark me-1 text-indigo"></i>{{ $lesson->module->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if ($lesson->is_preview)
                                    <span class="badge bg-emerald-light text-emerald px-2.5 py-1.5 rounded-pill" style="font-size:0.7rem; font-weight:700;">
                                        <i class="fa-solid fa-unlock me-1"></i>Free Preview
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border px-2.5 py-1.5 rounded-pill" style="font-size:0.7rem; font-weight:600;">
                                        <i class="fa-solid fa-lock me-1"></i>Enrolled Only
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3">
                                @if ($lesson->documnet_path)
                                    <a href="{{ $lesson->documnet_path }}" target="_blank" class="btn btn-sm btn-light text-danger border rounded-pill px-2.5 py-1" style="font-size:0.75rem;" title="Watch Video">
                                        <i class="fa-brands fa-youtube me-1"></i>Watch
                                    </a>
                                @else
                                    <span class="text-muted opacity-40 small">—</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="d-inline-flex gap-1.5">
                                    <button type="button" class="btn btn-sm btn-light text-indigo border edit-lesson-btn rounded-pill px-2.5 py-1" style="font-size:0.75rem;"
                                        data-id="{{ $lesson->id }}"
                                        data-title="{{ $lesson->title }}"
                                        data-short="{{ $lesson->short_text }}"
                                        data-order="{{ $lesson->order_number }}"
                                        data-video="{{ $lesson->documnet_path }}"
                                        data-content="{{ $lesson->full_content }}"
                                        data-module="{{ $lesson->module_id }}"
                                        data-bs-toggle="modal" data-bs-target="#editLessonModal"
                                        title="Edit Lesson">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                    </button>
                                    <form action="{{ route('teacher.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-pill px-2.5 py-1" style="font-size:0.75rem;" onclick="return confirm('Permanently delete this lesson?')" title="Delete Lesson">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-layer-group text-muted opacity-25 mb-2" style="font-size:2.5rem;"></i>
                                <h6 class="text-dark fw-bold mb-1">No Lessons Created Yet</h6>
                                <p class="text-muted small mb-3">Start by adding lessons to your course modules above.</p>
                                <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createLessonModal">
                                    <i class="fa-solid fa-plus me-1"></i>Create First Lesson
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ── CREATE LESSON MODAL ── --}}
<div class="modal fade" id="createLessonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-2 text-indigo"></i>Create New Lesson</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.lessons.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE / COURSE *</label>
                            <select name="module_id" id="create_module_id" class="ml-input" required>
                                <option value="" selected disabled>Select Course Module...</option>
                                @foreach ($myModules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">ORDER NUMBER *</label>
                            <input type="number" name="order_number" class="ml-input" value="1" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">LESSON TITLE *</label>
                            <input type="text" name="title" class="ml-input" placeholder="e.g. Introduction to Data Analytics Concepts" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">SHORT SUMMARY (OPTIONAL)</label>
                            <input type="text" name="short_text" class="ml-input" placeholder="Brief overview of what students will learn in this lesson">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">VIDEO LINK (YOUTUBE / VIMEO / DRIVE)</label>
                            <input type="url" name="documnet_path" class="ml-input" placeholder="https://youtube.com/watch?v=...">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">FULL LESSON CONTENT / NOTES</label>
                            <textarea name="full_content" rows="4" class="ml-input" placeholder="Detailed lesson transcript, reading materials, or instructions..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1.5"></i>Save Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── EDIT LESSON MODAL ── --}}
<div class="modal fade" id="editLessonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Lesson Content</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLessonForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE *</label>
                            <select name="module_id" id="edit_module_id" class="ml-input" required>
                                @foreach ($myModules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">ORDER NUMBER *</label>
                            <input type="number" name="order_number" id="edit_order_number" class="ml-input" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">LESSON TITLE *</label>
                            <input type="text" name="title" id="edit_title" class="ml-input" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">VIDEO LINK</label>
                            <input type="url" name="documnet_path" id="edit_video_url" class="ml-input">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">SHORT SUMMARY</label>
                            <input type="text" name="short_text" id="edit_short_text" class="ml-input">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">FULL LESSON CONTENT / NOTES</label>
                            <textarea name="full_content" id="edit_full_content" rows="4" class="ml-input"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-circle-check me-1.5"></i>Update Lesson
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $.fn.dataTable.ext.errMode = 'none';
            if ($('#lessonsTable tbody tr').length > 0 && !$('#lessonsTable tbody tr td[colspan]').length) {
                if (!$.fn.DataTable.isDataTable('#lessonsTable')) {
                    $('#lessonsTable').DataTable({
                        pageLength: 10,
                        ordering: true,
                        responsive: true,
                        columnDefs: [
                            { orderable: false, targets: 5 }
                        ],
                        language: { 
                            search: '', 
                            searchPlaceholder: 'Search lessons...',
                            emptyTable: 'No lessons found.'
                        }
                    });
                }
            }
        }


        const editBtns = document.querySelectorAll('.edit-lesson-btn');
        const editForm = document.getElementById('editLessonForm');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (editForm) {
                    editForm.action = `/teacher/lessons/update/${id}`;
                }

                if (document.getElementById('edit_title')) document.getElementById('edit_title').value = this.getAttribute('data-title') || '';
                if (document.getElementById('edit_short_text')) document.getElementById('edit_short_text').value = this.getAttribute('data-short') || '';
                if (document.getElementById('edit_order_number')) document.getElementById('edit_order_number').value = this.getAttribute('data-order') || '';
                if (document.getElementById('edit_video_url')) document.getElementById('edit_video_url').value = this.getAttribute('data-video') || '';
                if (document.getElementById('edit_module_id')) document.getElementById('edit_module_id').value = this.getAttribute('data-module') || '';
                if (document.getElementById('edit_full_content')) document.getElementById('edit_full_content').value = this.getAttribute('data-content') || '';
            });
        });

        const createModal = document.getElementById('createLessonModal');
        if (createModal) {
            createModal.addEventListener('show.bs.modal', function (e) {
                const btn = e.relatedTarget;
                if (btn) {
                    const moduleId = btn.getAttribute('data-module-id');
                    if (moduleId && document.getElementById('create_module_id')) {
                        document.getElementById('create_module_id').value = moduleId;
                    }
                }
            });
        }
    });
</script>

<style>
/* ── Page Layout ── */
.ml-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.ml-header-badge {
    display: inline-block;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    margin-bottom: 0.35rem;
}
.ml-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.ml-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

.ml-btn-primary {
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 0.55rem 1.2rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    transition: all 0.2s;
}
.ml-btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,0.3); }

/* ── Stat Cards ── */
.ml-stat-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.ml-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.ml-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.ml-stat-indigo .ml-stat-icon { background: #EEF2FF; color: #4F46E5; }
.ml-stat-emerald .ml-stat-icon{ background: #ECFDF5; color: #10B981; }
.ml-stat-amber .ml-stat-icon  { background: #FFFBEB; color: #F59E0B; }
.ml-stat-purple .ml-stat-icon { background: #F3E8FF; color: #9333EA; }

.ml-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.ml-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

/* ── Module Cards ── */
.ml-module-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #F1F5F9;
    padding: 1.15rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.ml-module-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.06); border-color: #C7D2FE; }
.ml-mod-title { font-size: 0.92rem; font-weight: 800; color: #0F172A; margin-bottom: 0.35rem; }
.ml-mod-desc  { font-size: 0.76rem; color: #64748B; line-height: 1.4; margin-bottom: 1rem; }
.ml-mod-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 0.75rem; border-top: 1.5px solid #F8FAFF; }

/* ── Table Card ── */
.ml-table-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}
.ml-table-head {
    padding: 1.1rem 1.25rem 0.85rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.ml-table-title { font-size: 0.9rem; font-weight: 800; color: #0F172A; }

.table thead th {
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748B;
    background-color: #F8FAFF;
    border-bottom: 1.5px solid #EDF2F7;
}

/* Modals Inputs */
.ml-input {
    width: 100%;
    padding: 0.5rem 0.9rem;
    font-size: 0.84rem;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.ml-input:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

/* Utility Colors */
.bg-indigo-light { background: #EEF2FF; }
.text-indigo     { color: #4F46E5; }
.border-indigo-subtle { border-color: #C7D2FE !important; }
.bg-emerald-light{ background: #ECFDF5; }
.text-emerald    { color: #047857; }
</style>
@endsection

