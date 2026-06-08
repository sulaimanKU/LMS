@extends('applayouts.app')

@section('contents')
<style>
/* ── Page Wrapper ── */
.cm-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.cm-header {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;
}
.cm-title    { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; }
.cm-subtitle { font-size: .82rem; color: #64748B; margin: .15rem 0 0; }

.cm-btn-new {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg, #4F46E5, #7C3AED); color: #fff;
    border: none; border-radius: 12px; padding: .65rem 1.25rem;
    font-size: .85rem; font-weight: 700; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cm-btn-new:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.35); color: #fff; }

/* ── Module Cards ── */
.cm-module-card {
    background: #fff; border-radius: 16px; border: 1.5px solid #F1F5F9;
    padding: 1.25rem; height: 100%; transition: all .2s;
    box-shadow: 0 2px 4px rgba(0,0,0,.02);
}
.cm-module-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border-color: #E2E8F0; }

.cm-mod-tag {
    font-size: .62rem; font-weight: 800; text-transform: uppercase;
    color: #4F46E5; background: #EEF2FF; padding: .2rem .6rem; border-radius: 6px;
}
.cm-mod-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin: .75rem 0 .5rem; }
.cm-mod-desc  { font-size: .75rem; color: #64748B; line-height: 1.4; margin-bottom: 1.25rem; }

.cm-mod-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: auto; padding-top: 1rem; border-top: 1px solid #F8FAFF;
}
.cm-mod-duration { font-size: .7rem; font-weight: 600; color: #94A3B8; }
.cm-mod-btn {
    padding: .35rem .75rem; font-size: .72rem; font-weight: 700;
    border-radius: 8px; border: 1.5px solid #4F46E5; background: #fff; color: #4F46E5;
    transition: all .15s;
}
.cm-mod-btn:hover { background: #4F46E5; color: #fff; }

/* ── Lessons Table ── */
.cm-table-card {
    background: #fff; border-radius: 20px; border: 1.5px solid #F1F5F9;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden;
}
.cm-table-head {
    padding: 1.25rem 1.5rem; border-bottom: 1.5px solid #F1F5F9;
    display: flex; align-items: center; gap: .75rem;
}
.cm-table-title { font-size: .95rem; font-weight: 700; color: #1E293B; margin: 0; }

.cm-table thead th {
    background: #F8FAFF; font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #94A3B8; padding: 1rem 1.25rem; border-bottom: 1.5px solid #F1F5F9;
}
.cm-table tbody td { padding: 1.1rem 1.25rem; border-bottom: 1px solid #F8FAFF; vertical-align: middle; }
.cm-table tbody tr:last-child td { border-bottom: none; }
.cm-table tbody tr:hover { background: #FAFBFF; }

.cm-order-num { font-size: .85rem; font-weight: 700; color: #94A3B8; }
.cm-lesson-title { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; }
.cm-lesson-slug  { font-size: .68rem; color: #94A3B8; margin-top: .15rem; }

.cm-badge-pill {
    padding: .2rem .65rem; border-radius: 50px; font-size: .68rem; font-weight: 700;
}
.cm-badge-module { background: #EEF2FF; color: #4F46E5; border: 1px solid #E2E8F0; }
.cm-badge-free   { background: #DCFCE7; color: #16A34A; }
.cm-badge-paid   { background: #F1F5F9; color: #64748B; }

/* ── Actions ── */
.cm-action-group { display: flex; align-items: center; justify-content: flex-end; gap: .5rem; }
.cm-btn-icon {
    width: 34px; height: 34px; border-radius: 10px; border: 1.5px solid #E2E8F0;
    background: #fff; color: #64748B; display: flex; align-items: center;
    justify-content: center; font-size: .85rem; transition: all .15s;
}
.cm-btn-icon:hover { border-color: #4F46E5; color: #4F46E5; background: #F5F7FF; }
.cm-btn-delete:hover { border-color: #EF4444; color: #EF4444; background: #FEF2F2; }

/* ── Modals ── */
.cm-modal { border: none; border-radius: 20px; overflow: hidden; }
.cm-modal-hdr {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.5rem 1.75rem 1.25rem; background: linear-gradient(135deg, #4F46E5, #7C3AED);
}
.cm-modal-title { font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0; }
.cm-modal-sub   { font-size: .78rem; color: rgba(255,255,255,.8); margin: .25rem 0 0; }
.cm-modal-close {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; width: 32px; height: 32px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .9rem;
}
.cm-modal-body { padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 1.1rem; }
.cm-modal-foot { padding: 1.25rem 1.75rem 1.5rem; display: flex; justify-content: flex-end; gap: .75rem; border-top: 1px solid #F1F5F9; }

.cm-label { font-size: .75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .5px; }
.cm-input {
    border: 1.5px solid #E2E8F0; border-radius: 12px; padding: .65rem .9rem;
    font-size: .9rem; color: #1E293B; background: #F8FAFF; outline: none;
    transition: all .2s; width: 100%;
}
.cm-input:focus { border-color: #4F46E5; background: #fff; box-shadow: 0 0 0 4px rgba(79,70,229,.1); }
select.cm-input { cursor: pointer; }
textarea.cm-input { resize: vertical; min-height: 80px; }

.cm-btn-cancel { padding: .65rem 1.35rem; border: 1.5px solid #E2E8F0; border-radius: 12px; background: #fff; color: #64748B; font-size: .88rem; font-weight: 700; cursor: pointer; }
.cm-btn-submit {
    padding: .65rem 1.5rem; border: none; border-radius: 12px;
    background: linear-gradient(135deg, #4F46E5, #7C3AED); color: #fff;
    font-size: .88rem; font-weight: 700; cursor: pointer;
    display: inline-flex; align-items: center;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all 0.2s;
}
.cm-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.35); }
</style>

<div class="cm-page">

    {{-- ── Alerts ── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="cm-header">
        <div>
            <h5 class="cm-title">Curriculum Management</h5>
            <p class="cm-subtitle">Build and organize lessons for your assigned modules.</p>
        </div>
        <button class="cm-btn-new" data-bs-toggle="modal" data-bs-target="#createLessonModal">
            <i class="fas fa-plus me-2"></i>Create New Lesson
        </button>
    </div>

    {{-- ── Teacher Modules ── --}}
    @if($myModules->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($myModules as $module)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="cm-module-card">
                        <span class="cm-mod-tag">Module</span>
                        <h6 class="cm-mod-title text-truncate" title="{{ $module->title }}">{{ $module->title }}</h6>
                        <p class="cm-mod-desc">{{ Str::limit($module->short_description ?? 'Build your course curriculum by adding structured lessons.', 85) }}</p>
                        <div class="cm-mod-footer">
                            <span class="cm-mod-duration"><i class="fa-regular fa-clock me-1"></i>{{ $module->duration ?? 'Ongoing' }}</span>
                            <button class="cm-mod-btn" data-bs-toggle="modal" data-bs-target="#createLessonModal" data-module-id="{{ $module->id }}">
                                <i class="fa-solid fa-circle-plus me-1"></i>Add Lesson
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning mb-5 rounded-4 border-0 shadow-sm">
            <i class="fa-solid fa-circle-exclamation me-2"></i> No modules assigned to your profile yet.
        </div>
    @endif

    {{-- ── Lessons List ── --}}
    <div class="cm-table-card">
        <div class="cm-table-head">
            <i class="fa-solid fa-layer-group text-primary"></i>
            <h6 class="cm-table-title">Full Curriculum Overview</h6>
        </div>
        <div class="table-responsive">
            <table class="table cm-table align-middle mb-0" id="lessonsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="ps-4">#</th>
                        <th style="width: 25%;">Lesson Details</th>
                        <th>Module</th>
                        <th>Access</th>
                        <th class="text-center">Video</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lessons as $lesson)
                        <tr>
                            <td class="ps-4"><span class="cm-order-num">{{ $lesson->order_number }}</span></td>
                            <td>
                                <p class="cm-lesson-title">{{ $lesson->title }}</p>
                                <p class="cm-lesson-slug">{{ $lesson->slug }}</p>
                            </td>
                            <td>
                                <span class="cm-badge-pill cm-badge-module">{{ $lesson->module->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @if ($lesson->is_preview)
                                    <span class="cm-badge-pill cm-badge-free">Free Preview</span>
                                @else
                                    <span class="cm-badge-pill cm-badge-paid">Enrolled Only</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($lesson->documnet_path)
                                    <a href="{{ $lesson->documnet_path }}" target="_blank" class="text-danger fs-5" title="Watch Video">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                @else
                                    <span class="text-muted opacity-25 small">—</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="cm-action-group">
                                    <button type="button" class="cm-btn-icon edit-lesson-btn"
                                        data-id="{{ $lesson->id }}"
                                        data-title="{{ $lesson->title }}"
                                        data-short="{{ $lesson->short_text }}"
                                        data-order="{{ $lesson->order_number }}"
                                        data-video="{{ $lesson->documnet_path }}"
                                        data-content="{{ $lesson->full_content }}"
                                        data-module="{{ $lesson->module_id }}"
                                        data-bs-toggle="modal" data-bs-target="#editLessonModal">
                                        <i class="fas fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('teacher.lessons.destroy', $lesson->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="cm-btn-icon cm-btn-delete" onclick="return confirm('Permanently delete this lesson?')">
                                            <i class="fas fa-trash-can"></i>
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

{{-- ══ CREATE LESSON MODAL ══ --}}
<div class="modal fade" id="createLessonModal" tabindex="-1" aria-hidden="true" style="z-index: 2050;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content cm-modal shadow-lg">
            <div class="cm-modal-hdr">
                <div>
                    <h5 class="cm-modal-title"><i class="fa-solid fa-circle-plus me-2"></i>Add New Lesson</h5>
                    <p class="cm-modal-sub">Create structured content for your module</p>
                </div>
                <button type="button" class="cm-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('teacher.lessons.store') }}" method="POST">
                @csrf
                <div class="cm-modal-body">
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                        <div class="cm-field">
                            <label class="cm-label">Target Module <span class="text-danger">*</span></label>
                            <select name="module_id" id="create_module_id" class="cm-input" required>
                                <option value="" selected disabled>Select Module</option>
                                @foreach ($myModules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cm-field">
                            <label class="cm-label">Order # <span class="text-danger">*</span></label>
                            <input type="number" name="order_number" class="cm-input" value="1" required>
                        </div>
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Lesson Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="cm-input" placeholder="e.g. Introduction to Variables" required>
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Short Summary (Optional)</label>
                        <input type="text" name="short_text" class="cm-input" placeholder="A brief overview of what students will learn">
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Video URL (YouTube/Vimeo)</label>
                        <input type="url" name="documnet_path" class="cm-input" placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Full Content / Notes</label>
                        <textarea name="full_content" rows="4" class="cm-input" placeholder="Detailed lesson text or transcript..."></textarea>
                    </div>
                </div>
                <div class="cm-modal-foot">
                    <button type="button" class="cm-btn-cancel" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="cm-btn-submit"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Save Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ EDIT LESSON MODAL ══ --}}
<div class="modal fade" id="editLessonModal" tabindex="-1" aria-hidden="true" style="z-index: 2050;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content cm-modal shadow-lg">
            <div class="cm-modal-hdr">
                <div>
                    <h5 class="cm-modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Lesson</h5>
                    <p class="cm-modal-sub">Update lesson details and configuration</p>
                </div>
                <button type="button" class="cm-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editLessonForm" method="POST">
                @csrf
                @method('PUT')
                <div class="cm-modal-body">
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                        <div class="cm-field">
                            <label class="cm-label">Module <span class="text-danger">*</span></label>
                            <select name="module_id" id="edit_module_id" class="cm-input" required>
                                @foreach ($myModules as $module)
                                    <option value="{{ $module->id }}">{{ $module->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cm-field">
                            <label class="cm-label">Order # <span class="text-danger">*</span></label>
                            <input type="number" name="order_number" id="edit_order_number" class="cm-input" required>
                        </div>
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Lesson Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="edit_title" class="cm-input" required>
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Video URL</label>
                        <input type="url" name="documnet_path" id="edit_video_url" class="cm-input">
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Short Description</label>
                        <textarea name="short_text" id="edit_short_text" rows="2" class="cm-input"></textarea>
                    </div>
                    <div class="cm-field">
                        <label class="cm-label">Full Content / Long Description</label>
                        <textarea name="full_content" id="edit_full_content" rows="3" class="cm-input"></textarea>
                    </div>
                </div>
                <div class="cm-modal-foot">
                    <button type="button" class="cm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cm-btn-submit"><i class="fa-solid fa-circle-check me-2"></i>Update Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable specifically for this table to avoid conflicts
        if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#lessonsTable')) {
            $('#lessonsTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: 5 } // Disable ordering on Actions column
                ],
                language: { 
                    search: '', 
                    searchPlaceholder: 'Search lessons...',
                    emptyTable: 'No lessons found. Start by adding a new lesson to your courses!'
                }
            });
        }

        // Handle Edit Button Click
        const editBtns = document.querySelectorAll('.edit-lesson-btn');
        const editForm = document.getElementById('editLessonForm');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editForm.action = `/teacher/lessons/update/${id}`;

                document.getElementById('edit_title').value = this.getAttribute('data-title');
                document.getElementById('edit_short_text').value = this.getAttribute('data-short');
                document.getElementById('edit_order_number').value = this.getAttribute('data-order');
                document.getElementById('edit_video_url').value = this.getAttribute('data-video');
                document.getElementById('edit_module_id').value = this.getAttribute('data-module');
                document.getElementById('edit_full_content').value = this.getAttribute('data-content');
            });
        });

        // Handle Add Lesson from Module Card
        const createModal = document.getElementById('createLessonModal');
        if (createModal) {
            createModal.addEventListener('show.bs.modal', function (e) {
                const btn = e.relatedTarget;
                const moduleId = btn.getAttribute('data-module-id');
                if (moduleId) {
                    document.getElementById('create_module_id').value = moduleId;
                }
            });
        }
    });
</script>
@endsection
