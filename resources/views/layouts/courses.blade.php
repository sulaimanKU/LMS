@extends('applayouts.app')

@section('contents')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<div class="cr-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="cr-header">
        <div>
            <h5 class="cr-title">Course Management</h5>
            <p class="cr-subtitle">Create, edit and manage all modules offered by MyLMS</p>
        </div>
        <button class="cr-btn-add" data-bs-toggle="modal" data-bs-target="#addCourseModal">
            <i class="fa-solid fa-plus me-2"></i>Add Course
        </button>
    </div>

    {{-- ── Stat cards ── --}}
    <div class="cr-stats">
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#EEF2FF;color:#4F46E5;">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalCourses }}</p>
                <p class="cr-stat-label">Total Courses</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#D1FAE5;color:#065F46;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $activeCourses }}</p>
                <p class="cr-stat-label">Active</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#F1F5F9;color:#64748B;">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $inactiveCourses }}</p>
                <p class="cr-stat-label">Inactive</p>
            </div>
        </div>
        <div class="cr-stat">
            <div class="cr-stat-icon" style="background:#FEF3C7;color:#92400E;">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="cr-stat-num">{{ $totalEnrolled }}</p>
                <p class="cr-stat-label">Enrolled Students</p>
            </div>
        </div>
    </div>

    {{-- ── Filter tabs ── --}}
    <div class="cr-tabs">
        <a class="cr-tab {{ $filter === 'all'      ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'all',      'page' => 1]) }}">All ({{ $totalCourses }})</a>
        <a class="cr-tab {{ $filter === 'active'   ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'active',   'page' => 1]) }}">Active ({{ $activeCourses }})</a>
        <a class="cr-tab {{ $filter === 'inactive' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['filter' => 'inactive', 'page' => 1]) }}">Inactive ({{ $inactiveCourses }})</a>
    </div>

    {{-- ── Course grid ── --}}
    <div class="cr-grid">
        @forelse($courses as $course)
        <div class="cr-card {{ $course->status === 'inactive' ? 'cr-card-inactive' : '' }}">

            {{-- Course image --}}
            <div class="cr-card-img">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
                @else
                    <div class="cr-card-img-placeholder">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                @endif
                <span class="cr-status-badge cr-status-overlay {{ $course->status === 'active' ? 'cr-badge-active' : 'cr-badge-inactive' }}">
                    {{ ucfirst($course->status) }}
                </span>
                <div class="cr-card-actions cr-actions-overlay">
                    <button class="cr-action-edit"
                            data-bs-toggle="modal" data-bs-target="#editCourseModal"
                            data-id="{{ $course->id }}"
                            data-title="{{ $course->title }}"
                            data-category="{{ $course->category }}"
                            data-price="{{ $course->price }}"
                            data-duration="{{ $course->duration }}"
                            data-short="{{ $course->short_description }}"
                            data-details="{{ $course->details }}"
                            data-status="{{ $course->status }}"
                            data-image="{{ $course->image ? asset('storage/' . $course->image) : '' }}"
                            title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($course->title) }}\'? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cr-action-delete" title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card body --}}
            <div class="cr-card-body">
                <span class="cr-category-pill">{{ $course->category }}</span>

                <h6 class="cr-card-title">{{ $course->title }}</h6>
                @if($course->short_description)
                    <p class="cr-card-desc">{{ Str::limit($course->short_description, 80) }}</p>
                @endif

                <div class="cr-meta">
                    @if($course->duration)
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-clock"></i> {{ $course->duration }}
                    </span>
                    @endif
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-book"></i> {{ $course->lessons_count }} lesson{{ $course->lessons_count != 1 ? 's' : '' }}
                    </span>
                    <span class="cr-meta-item">
                        <i class="fa-solid fa-users"></i> {{ $course->enrollments_count }} enrolled
                    </span>
                </div>

                <div class="cr-card-foot">
                    <span class="cr-price">PKR {{ number_format($course->price, 0) }}</span>
                    @php $teacher = $course->teacher->first(); @endphp
                    @if($teacher)
                        <span class="cr-teacher-tag">
                            <i class="fa-solid fa-chalkboard-user me-1"></i>{{ Str::limit($teacher->name, 18) }}
                        </span>
                    @else
                        <span class="cr-no-teacher">No teacher assigned</span>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="cr-empty">
            <i class="fa-solid fa-book-open-reader"></i>
            <p>No courses found{{ $filter !== 'all' ? ' for filter "' . $filter . '"' : '' }}.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($courses->hasPages())
    <div class="cr-pagination">{{ $courses->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

{{-- ═══════════════ ADD COURSE MODAL ═══════════════ --}}
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
        <div class="modal-content cr-modal">
            <div class="cr-modal-header">
                <div>
                    <h5 class="cr-modal-title"><i class="fa-solid fa-plus me-2"></i>Add New Course</h5>
                    <p class="cr-modal-sub">Fill in the module details below</p>
                </div>
                <button type="button" class="cr-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="cr-modal-body">
                    <div class="cr-field">
                        <label class="cr-label">Course Title <span class="cr-req">*</span></label>
                        <input type="text" name="title" class="cr-input" placeholder="e.g. Advanced Data Analysis" required value="{{ old('title') }}">
                    </div>
                    <div class="cr-row-2">
                        <div class="cr-field">
                            <label class="cr-label">Category <span class="cr-req">*</span></label>
                            <input type="text" name="category" class="cr-input" placeholder="e.g. Digital Research" required value="{{ old('category') }}">
                        </div>
                        <div class="cr-field">
                            <label class="cr-label">Status <span class="cr-req">*</span></label>
                            <select name="status" class="cr-input">
                                <option value="active" {{ old('status') !== 'inactive' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="cr-row-2">
                        <div class="cr-field">
                            <label class="cr-label">Price (PKR) <span class="cr-req">*</span></label>
                            <input type="number" name="price" class="cr-input" placeholder="3000" min="0" required value="{{ old('price', 3000) }}">
                        </div>
                        <div class="cr-field">
                            <label class="cr-label">Duration <span class="cr-opt">optional</span></label>
                            <input type="text" name="duration" class="cr-input" placeholder="e.g. 2 Weeks" value="{{ old('duration') }}">
                        </div>
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Short Description <span class="cr-opt">optional</span></label>
                        <input type="text" name="short_description" class="cr-input" placeholder="One-line summary…" value="{{ old('short_description') }}">
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Full Details <span class="cr-opt">optional</span></label>
                        <textarea name="details" id="add_details" class="summernote" rows="3" placeholder="Detailed course description…">{{ old('details') }}</textarea>
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Course Image <span class="cr-opt">optional</span></label>
                        <input type="file" name="image" class="cr-input" accept="image/*" id="addImageInput">
                        <div id="addImagePreview" style="display:none;margin-top:.4rem;">
                            <img id="addImagePreviewImg" src="" alt="Preview" style="width:100%;height:120px;object-fit:cover;border-radius:8px;border:1.5px solid #E2E8F0;">
                        </div>
                    </div>
                </div>
                <div class="cr-modal-footer">
                    <button type="button" class="cr-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cr-btn-save"><i class="fa-solid fa-floppy-disk me-2"></i>Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════ EDIT COURSE MODAL ═══════════════ --}}
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:560px;">
        <div class="modal-content cr-modal">
            <div class="cr-modal-header">
                <div>
                    <h5 class="cr-modal-title"><i class="fa-solid fa-pen me-2"></i>Edit Course</h5>
                    <p class="cr-modal-sub" id="editModalSub">Updating course details</p>
                </div>
                <button type="button" class="cr-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editCourseForm" action="" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="cr-modal-body">
                    <div class="cr-field">
                        <label class="cr-label">Course Title <span class="cr-req">*</span></label>
                        <input type="text" name="title" id="edit_title" class="cr-input" required>
                    </div>
                    <div class="cr-row-2">
                        <div class="cr-field">
                            <label class="cr-label">Category <span class="cr-req">*</span></label>
                            <input type="text" name="category" id="edit_category" class="cr-input" required>
                        </div>
                        <div class="cr-field">
                            <label class="cr-label">Status <span class="cr-req">*</span></label>
                            <select name="status" id="edit_status" class="cr-input">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="cr-row-2">
                        <div class="cr-field">
                            <label class="cr-label">Price (PKR) <span class="cr-req">*</span></label>
                            <input type="number" name="price" id="edit_price" class="cr-input" min="0" required>
                        </div>
                        <div class="cr-field">
                            <label class="cr-label">Duration <span class="cr-opt">optional</span></label>
                            <input type="text" name="duration" id="edit_duration" class="cr-input">
                        </div>
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Short Description <span class="cr-opt">optional</span></label>
                        <input type="text" name="short_description" id="edit_short" class="cr-input">
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Full Details <span class="cr-opt">optional</span></label>
                        <textarea name="details" id="edit_details" class="summernote" rows="3"></textarea>
                    </div>
                    <div class="cr-field">
                        <label class="cr-label">Course Image <span class="cr-opt">optional — leave blank to keep existing</span></label>
                        <div id="editCurrentImage" style="display:none;margin-bottom:.4rem;">
                            <img id="editCurrentImageImg" src="" alt="Current image" style="width:100%;height:120px;object-fit:cover;border-radius:8px;border:1.5px solid #E2E8F0;">
                        </div>
                        <input type="file" name="image" class="cr-input" accept="image/*" id="editImageInput">
                        <div id="editImagePreview" style="display:none;margin-top:.4rem;">
                            <img id="editImagePreviewImg" src="" alt="New preview" style="width:100%;height:120px;object-fit:cover;border-radius:8px;border:1.5px solid #E2E8F0;">
                        </div>
                    </div>
                </div>
                <div class="cr-modal-footer">
                    <button type="button" class="cr-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cr-btn-save"><i class="fa-solid fa-floppy-disk me-2"></i>Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ── Page ── */
.cr-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.cr-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.cr-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.cr-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }
.cr-btn-add {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    border: none; border-radius: 10px; padding: .55rem 1.1rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cr-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }

/* ── Stats ── */
.cr-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
.cr-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 14px;
    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.cr-stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; }
.cr-stat-num   { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; line-height: 1; }
.cr-stat-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; margin: .15rem 0 0; }

/* ── Tabs ── */
.cr-tabs { display: flex; gap: 4px; background: #EEF2FF; padding: 4px; border-radius: 10px; width: fit-content; margin-bottom: 1.25rem; }
.cr-tab { padding: .32rem .9rem; border-radius: 7px; font-size: .8rem; font-weight: 600; color: #64748B; text-decoration: none; transition: all .15s; }
.cr-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.cr-tab:hover:not(.active) { background: rgba(255,255,255,.6); }

/* ── Grid ── */
.cr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }

/* ── Card ── */
.cr-card {
    background: #fff; border-radius: 14px; border: 1.5px solid #F1F5F9;
    padding: 0; display: flex; flex-direction: column; gap: 0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05); transition: box-shadow .2s, transform .2s;
    overflow: hidden;
}
.cr-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
.cr-card-inactive { opacity: .7; background: #F8FAFF; }

/* ── Card image area ── */
.cr-card-img {
    position: relative; width: 100%; height: 150px; overflow: hidden;
    background: linear-gradient(135deg,#EEF2FF,#E0E7FF); flex-shrink: 0;
}
.cr-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.cr-card-img-placeholder {
    width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: #A5B4FC;
}
.cr-status-overlay {
    position: absolute; top: .55rem; left: .65rem;
}
.cr-actions-overlay {
    position: absolute; top: .45rem; right: .55rem;
}
.cr-card-body {
    padding: .85rem 1.2rem .9rem;
    display: flex; flex-direction: column; gap: .55rem;
    flex: 1;
}

.cr-card-top { display: flex; align-items: center; justify-content: space-between; }
.cr-status-badge { padding: .2rem .65rem; border-radius: 50px; font-size: .7rem; font-weight: 700; }
.cr-badge-active   { background: #D1FAE5; color: #065F46; }
.cr-badge-inactive { background: #F1F5F9; color: #64748B; }

.cr-card-actions { display: flex; gap: 5px; }
.cr-action-edit, .cr-action-delete {
    width: 28px; height: 28px; border-radius: 7px; border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer; transition: all .15s;
}
.cr-action-edit   { background: #EEF2FF; color: #4F46E5; }
.cr-action-edit:hover   { background: #4F46E5; color: #fff; }
.cr-action-delete { background: #FEE2E2; color: #DC2626; }
.cr-action-delete:hover { background: #DC2626; color: #fff; }

.cr-category-pill {
    display: inline-block; background: #F1F5F9; color: #475569;
    padding: .15rem .6rem; border-radius: 50px; font-size: .68rem; font-weight: 600; width: fit-content;
}
.cr-card-title { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; line-height: 1.3; }
.cr-card-desc  { font-size: .76rem; color: #64748B; margin: 0; line-height: 1.5; }

.cr-meta { display: flex; flex-wrap: wrap; gap: .5rem; }
.cr-meta-item { display: flex; align-items: center; gap: 5px; font-size: .73rem; color: #64748B; }
.cr-meta-item i { color: #94A3B8; font-size: .68rem; }

.cr-card-foot { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: .6rem; border-top: 1px solid #F1F5F9; }
.cr-price { font-size: .95rem; font-weight: 800; color: #4F46E5; }
.cr-teacher-tag { font-size: .72rem; font-weight: 600; color: #065F46; background: #D1FAE5; padding: .18rem .6rem; border-radius: 50px; }
.cr-no-teacher  { font-size: .72rem; color: #CBD5E1; font-style: italic; }

/* ── Empty ── */
.cr-empty { grid-column: 1/-1; text-align: center; padding: 4rem 1rem; color: #CBD5E1; }
.cr-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
.cr-empty p { font-size: .9rem; margin: 0; }

/* ── Pagination ── */
.cr-pagination { margin-top: 1.5rem; display: flex; justify-content: center; }
.cr-pagination .page-link { color: #4F46E5; border-color: #E2E8F0; font-size: .82rem; }
.cr-pagination .page-item.active .page-link { background: #4F46E5; border-color: #4F46E5; }

/* ── Modal ── */
.cr-modal { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.12); }
.cr-modal-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.3rem 1.5rem 1rem; background: linear-gradient(135deg,#4F46E5,#7C3AED);
}
.cr-modal-title { font-size: .975rem; font-weight: 700; color: #fff; margin: 0; }
.cr-modal-sub   { font-size: .78rem; color: rgba(255,255,255,.75); margin: .2rem 0 0; }
.cr-modal-close {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .8rem;
}
.cr-modal-body   { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .75rem; max-height: 65vh; overflow-y: auto; }
.cr-modal-footer { padding: 1rem 1.5rem 1.3rem; display: flex; justify-content: flex-end; gap: .6rem; border-top: 1px solid #F1F5F9; }

.cr-field { display: flex; flex-direction: column; gap: .3rem; }
.cr-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.cr-label { font-size: .75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .4px; }
.cr-req { color: #EF4444; }
.cr-opt { font-weight: 400; text-transform: none; letter-spacing: 0; color: #CBD5E1; }
.cr-input {
    border: 1.5px solid #E2E8F0; border-radius: 9px; padding: .5rem .75rem;
    font-size: .845rem; color: #1E293B; background: #fff; outline: none;
    transition: border-color .15s; width: 100%;
}
.cr-input:focus { border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
textarea.cr-input { resize: vertical; }

.cr-btn-cancel {
    padding: .5rem 1.1rem; border: 1.5px solid #E2E8F0; border-radius: 9px;
    background: #fff; color: #64748B; font-size: .855rem; font-weight: 600; cursor: pointer;
}
.cr-btn-save {
    padding: .5rem 1.25rem; border: none; border-radius: 9px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    font-size: .855rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center;
    box-shadow: 0 4px 12px rgba(79,70,229,.25);
}

@media(max-width:991.98px) { .cr-stats { grid-template-columns: repeat(2,1fr); } }
@media(max-width:767.98px) {
    .cr-grid { grid-template-columns: 1fr; }
    .cr-stats { grid-template-columns: repeat(2,1fr); }
    .cr-row-2 { grid-template-columns: 1fr; }
}
</style>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Summernote
    $('.summernote').summernote({
        placeholder: 'Enter detailed course information...',
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    const editModal = document.getElementById('editCourseModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('editCourseForm').action =
                '{{ url("courses") }}/' + btn.dataset.id + '/update';
            document.getElementById('editModalSub').textContent = 'Editing: ' + btn.dataset.title;
            document.getElementById('edit_title').value    = btn.dataset.title;
            document.getElementById('edit_category').value = btn.dataset.category;
            document.getElementById('edit_price').value    = btn.dataset.price;
            document.getElementById('edit_duration').value = btn.dataset.duration;
            document.getElementById('edit_short').value    = btn.dataset.short;
            
            // Populate Summernote
            $('#edit_details').summernote('code', btn.dataset.details);

            document.getElementById('edit_status').value   = btn.dataset.status;

            // Reset new image preview
            document.getElementById('editImageInput').value = '';
            document.getElementById('editImagePreview').style.display = 'none';

            // Show existing image if present
            const currentImg = document.getElementById('editCurrentImage');
            const currentImgEl = document.getElementById('editCurrentImageImg');
            if (btn.dataset.image) {
                currentImgEl.src = btn.dataset.image;
                currentImg.style.display = 'block';
            } else {
                currentImg.style.display = 'none';
            }
        });
    }

    // Add modal image preview
    const addInput = document.getElementById('addImageInput');
    if (addInput) {
        addInput.addEventListener('change', function () {
            const preview = document.getElementById('addImagePreview');
            const img = document.getElementById('addImagePreviewImg');
            if (this.files && this.files[0]) {
                img.src = URL.createObjectURL(this.files[0]);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    }

    // Edit modal image preview
    const editInput = document.getElementById('editImageInput');
    if (editInput) {
        editInput.addEventListener('change', function () {
            const preview = document.getElementById('editImagePreview');
            const img = document.getElementById('editImagePreviewImg');
            if (this.files && this.files[0]) {
                img.src = URL.createObjectURL(this.files[0]);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    }
});
</script>

@endsection
