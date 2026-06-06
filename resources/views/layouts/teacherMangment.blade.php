@extends('applayouts.app')

@section('contents')
<div class="tm-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3 mt-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Page header ── --}}
    <div class="tm-header">
        <div>
            <h5 class="tm-title">Teacher Directory</h5>
            <p class="tm-subtitle">{{ $teachers->count() }} instructor{{ $teachers->count() !== 1 ? 's' : '' }} registered</p>
        </div>
        <button class="tm-add-btn" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
            <i class="fa-solid fa-user-plus me-2"></i>Add Teacher
        </button>
    </div>

    {{-- ── Table card ── --}}
    <div class="tm-card">
        <div class="table-responsive">
            <table class="table tm-table align-middle mb-0" id="teacherDataTable">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Assigned Modules</th>
                        <th>Bio</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        {{-- Avatar + name --}}
                        <td>
                            <div class="d-flex align-items-center gap-3">
@if($teacher->profile_image)
    <img src="{{ asset('storage/teachers/' . $teacher->profile_image) }}"
         class="tm-avatar-img"
         alt="{{ $teacher->name }}">
@else
    <img src="{{ asset('assets/images/default-avatar.png') }}"
         class="tm-avatar-img"
         alt="Default Avatar">
@endif
                                <div>
                                    <div class="tm-name">{{ $teacher->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="tm-email">{{ $teacher->email }}</td>
                        <td>
                            <span class="tm-badge-desig">{{ $teacher->designation ?? '—' }}</span>
                        </td>
                        {{-- Assigned courses --}}
                        <td>
                            @if($teacher->courses->isEmpty())
                                <span class="tm-no-course">No module assigned</span>
                            @else
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($teacher->courses as $course)
                                        <span class="tm-badge-course" title="{{ $course->title }}">
                                            {{ Str::limit($course->title, 25) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="tm-bio-text" title="{{ $teacher->bio }}">
                                {{ $teacher->bio ? Str::limit($teacher->bio, 60) : '—' }}
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                {{-- Reset Password --}}
                                <button class="tm-icon-btn tm-btn-pw"
                                        data-bs-toggle="modal"
                                        data-bs-target="#passwordModal"
                                        data-name="{{ $teacher->name }}"
                                        data-email="{{ $teacher->email }}"
                                        title="Reset Password">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                                {{-- Assign course --}}
                                <button class="tm-icon-btn tm-btn-assign"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignCourseModal"
                                        data-teacher-id="{{ $teacher->id }}"
                                        data-teacher-name="{{ $teacher->name }}"
                                        title="Assign module">
                                    <i class="fa-solid fa-link"></i>
                                </button>
                                {{-- Edit --}}
                                <button class="tm-icon-btn tm-btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editTeacherModal"
                                        data-id="{{ $teacher->id }}"
                                        data-name="{{ $teacher->name }}"
                                        data-email="{{ $teacher->email }}"
                                        data-designation="{{ $teacher->designation }}"
                                        data-scopus="{{ $teacher->scopus_link }}"
                                        data-bio="{{ $teacher->bio }}"
                                        data-specialization="{{ $teacher->specialization }}"
                                        data-linkedin="{{ $teacher->linkedin_url }}"
                                        title="Edit teacher">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                {{-- Delete --}}
                                <button class="tm-icon-btn tm-btn-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTeacherModal"
                                        data-id="{{ $teacher->id }}"
                                        data-name="{{ $teacher->name }}"
                                        title="Delete teacher">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5" style="font-size:.875rem;">
                            <i class="fa-solid fa-chalkboard-user mb-2 d-block" style="font-size:2rem; opacity:.3;"></i>
                            No teachers registered yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     MODAL — Add Teacher
═══════════════════════════════════════════ --}}
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content tm-modal">

            <div class="tm-modal-header">
                <div>
                    <h5 class="tm-modal-title"><i class="fa-solid fa-user-plus me-2"></i>Add New Teacher</h5>
                    <p class="tm-modal-sub">Fill in the details below. Default password is <strong>12345678</strong>.</p>
                </div>
                <button type="button" class="tm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('regester.teacher') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tm-modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="tm-label">Full Name <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-user tm-input-icon"></i>
                                <input type="text" name="name" class="tm-input"
                                       value="{{ old('name') }}" placeholder="Dr. John Smith" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="tm-label">Email Address <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-envelope tm-input-icon"></i>
                                <input type="email" name="email" class="tm-input"
                                       value="{{ old('email') }}" placeholder="john@university.edu" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="tm-label">Designation <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-id-badge tm-input-icon"></i>
                                <input type="text" name="designation" class="tm-input"
                                       value="{{ old('designation') }}" placeholder="e.g. Associate Professor" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="tm-label">Scopus / Profile Link</label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-link tm-input-icon"></i>
                                <input type="url" name="scopus_link" class="tm-input"
                                       value="{{ old('scopus_link') }}" placeholder="https://...">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="tm-label">Assign Modules <span class="tm-optional">(optional — can assign later)</span></label>
                            <div class="tm-course-grid">
                                @foreach($courses as $course)
                                    <label class="tm-course-check">
                                        <input type="checkbox" name="course_id[]" value="{{ $course->id }}"
                                               {{ in_array($course->id, old('course_id', [])) ? 'checked' : '' }}>
                                        <span class="tm-course-check-box"><i class="fa-solid fa-check"></i></span>
                                        <span class="tm-course-check-label">{{ $course->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="tm-label">Specialization</label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-graduation-cap tm-input-icon"></i>
                                <input type="text" name="specialization" class="tm-input"
                                       value="{{ old('specialization') }}" placeholder="e.g. Data Analysis, AI">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="tm-label">LinkedIn URL</label>
                            <div class="tm-input-wrap">
                                <i class="fa-brands fa-linkedin tm-input-icon"></i>
                                <input type="url" name="linkedin_url" class="tm-input"
                                       value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="tm-label">Short Biography</label>
                            <textarea name="bio" class="tm-input" rows="3"
                                      placeholder="Brief professional summary...">{{ old('bio') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="tm-label">Profile Picture <span class="tm-optional">(JPG, PNG — max 2 MB)</span></label>
                            <input type="file" name="profile_image" class="tm-file-input" accept="image/*">
                        </div>

                    </div>
                </div>

                <div class="tm-modal-footer">
                    <button type="button" class="tm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tm-btn-submit">
                        <i class="fa-solid fa-user-plus me-2"></i>Register Teacher
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL — Assign Course to existing teacher
═══════════════════════════════════════════ --}}
<div class="modal fade" id="assignCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tm-modal">

            <div class="tm-modal-header">
                <div>
                    <h5 class="tm-modal-title"><i class="fa-solid fa-link me-2"></i>Assign Module</h5>
                    <p class="tm-modal-sub" id="assignModalSubtitle">Assigning to teacher</p>
                </div>
                <button type="button" class="tm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="assignCourseForm" action="" method="POST">
                @csrf
                @method('POST')
                <div class="tm-modal-body">
                    <label class="tm-label">Select Module <span class="text-danger">*</span></label>
                    <select name="course_id" class="tm-input" required>
                        <option value="" disabled selected>Choose a module...</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tm-modal-footer">
                    <button type="button" class="tm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tm-btn-submit">
                        <i class="fa-solid fa-link me-2"></i>Assign Module
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* ── Page ── */
.tm-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.tm-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.tm-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.tm-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }

.tm-add-btn {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    border: none;
    padding: .52rem 1.15rem;
    border-radius: 9px;
    font-size: .855rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(79,70,229,.28);
    transition: all .2s;
    text-decoration: none;
}
.tm-add-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.38); color: #fff; }

/* ── Table card ── */
.tm-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    border: 1.5px solid #F1F5F9;
    overflow: hidden;
}

.tm-table thead th {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #94A3B8;
    background: #F8FAFF;
    border-bottom: 1px solid #F1F5F9;
    padding: .8rem 1rem;
}
.tm-table tbody td {
    padding: .8rem 1rem;
    border-bottom: 1px solid #F8FAFF;
    vertical-align: middle;
}
.tm-table tbody tr:last-child td { border-bottom: none; }
.tm-table tbody tr:hover { background: #FAFAFF; }

/* ── Avatar ── */
.tm-avatar-img {
    width: 38px; height: 38px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #EEF2FF;
}
.tm-avatar-ini {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700;
    flex-shrink: 0;
}
.tm-name  { font-size: .875rem; font-weight: 700; color: #1E293B; }
.tm-email { font-size: .8rem; color: #64748B; }

/* ── Badges ── */
.tm-badge-desig {
    background: #EEF2FF;
    color: #4F46E5;
    padding: .22rem .7rem;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
}
.tm-badge-course {
    display: inline-block;
    background: #F0FDF4;
    color: #065F46;
    border: 1px solid #BBF7D0;
    padding: .18rem .6rem;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}
.tm-no-course { font-size: .78rem; color: #CBD5E1; font-style: italic; }

/* ── Bio ── */
.tm-bio-text {
    font-size: .78rem;
    color: #64748B;
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── Action buttons ── */
.tm-icon-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    color: #64748B;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: .75rem;
    transition: all .15s;
}
.tm-btn-assign:hover { background: #EEF2FF; color: #4F46E5; border-color: #C7D2FE; }

/* ── Modal ── */
.tm-modal {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.12);
}
.tm-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1.4rem 1.5rem 1rem;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
}
.tm-modal-title { font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }
.tm-modal-sub   { font-size: .78rem; color: rgba(255,255,255,.75); margin: .2rem 0 0; }
.tm-modal-close {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .8rem;
    transition: background .15s;
    flex-shrink: 0;
}
.tm-modal-close:hover { background: rgba(255,255,255,.28); }

.tm-modal-body   { padding: 1.4rem 1.5rem; }
.tm-modal-footer { padding: 1rem 1.5rem 1.4rem; display: flex; justify-content: flex-end; gap: .6rem; border-top: 1px solid #F1F5F9; }

/* ── Form elements inside modal ── */
.tm-label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748B;
    margin-bottom: .35rem;
}
.tm-optional { font-size: .7rem; font-weight: 400; text-transform: none; letter-spacing: 0; color: #94A3B8; }

.tm-input-wrap { position: relative; }
.tm-input-icon {
    position: absolute;
    left: 11px; top: 50%; transform: translateY(-50%);
    color: #94A3B8; font-size: .8rem; pointer-events: none;
}
.tm-input {
    width: 100%;
    padding: .55rem .85rem .55rem 2.2rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    font-size: .875rem;
    color: #1E293B;
    background: #F8FAFF;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    font-family: inherit;
}
textarea.tm-input  { padding: .65rem .85rem; resize: vertical; }
select.tm-input    { padding-left: .85rem; cursor: pointer; }
.tm-input:focus {
    border-color: #7C3AED;
    box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    background: #fff;
}

/* ── Course checkbox grid ── */
.tm-course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
    gap: 7px;
    max-height: 220px;
    overflow-y: auto;
    padding: 2px;
}
.tm-course-check {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: .45rem .7rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
    user-select: none;
}
.tm-course-check:hover { border-color: #7C3AED; background: #FAF5FF; }
.tm-course-check input[type="checkbox"] { display: none; }
.tm-course-check-box {
    width: 18px; height: 18px;
    border: 2px solid #CBD5E1;
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    font-size: .6rem; color: #fff;
    transition: all .15s; flex-shrink: 0; background: #fff;
}
.tm-course-check input:checked ~ .tm-course-check-box {
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    border-color: #4F46E5;
}
.tm-course-check input:checked ~ .tm-course-check-box i { display: block; }
.tm-course-check-box i { display: none; }
.tm-course-check-label { font-size: .8rem; color: #1E293B; line-height: 1.3; }

/* ── File input ── */
.tm-file-input {
    width: 100%;
    padding: .5rem .75rem;
    border: 1.5px dashed #CBD5E1;
    border-radius: 9px;
    font-size: .845rem;
    color: #64748B;
    background: #F8FAFF;
    cursor: pointer;
    transition: border-color .2s;
}
.tm-file-input:hover { border-color: #7C3AED; }

/* ── Modal action buttons ── */
.tm-btn-cancel {
    padding: .52rem 1.1rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    background: #fff;
    color: #64748B;
    font-size: .855rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
}
.tm-btn-cancel:hover { background: #F8FAFF; border-color: #CBD5E1; }
.tm-btn-submit {
    padding: .52rem 1.25rem;
    border: none;
    border-radius: 9px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff;
    font-size: .855rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25);
    transition: all .2s;
    display: inline-flex; align-items: center;
}
.tm-btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Assign course modal ──
    const assignModal = document.getElementById('assignCourseModal');
    if (assignModal) {
        assignModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            assignModal.querySelector('#assignModalSubtitle').textContent = 'Assigning to: ' + btn.dataset.teacherName;
            assignModal.querySelector('#assignCourseForm').action =
                '/admin/teacher/' + btn.dataset.teacherId + '/assign-course';
        });
    }

    // ── Edit teacher modal ──
    const editModal = document.getElementById('editTeacherModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            editModal.querySelector('#edit_name').value        = btn.dataset.name        || '';
            editModal.querySelector('#edit_email').value       = btn.dataset.email       || '';
            editModal.querySelector('#edit_designation').value = btn.dataset.designation || '';
            editModal.querySelector('#edit_scopus').value      = btn.dataset.scopus      || '';
            editModal.querySelector('#edit_bio').value             = btn.dataset.bio             || '';
            editModal.querySelector('#edit_specialization').value  = btn.dataset.specialization  || '';
            editModal.querySelector('#edit_linkedin').value        = btn.dataset.linkedin        || '';
            editModal.querySelector('#editTeacherForm').action =
                '/admin/teacher/' + btn.dataset.id + '/update';
        });
    }

    // ── Delete teacher modal ──
    const deleteModal = document.getElementById('deleteTeacherModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            deleteModal.querySelector('#deleteTeacherName').textContent = btn.dataset.name;
            deleteModal.querySelector('#deleteTeacherForm').action =
                '/admin/teacher/' + btn.dataset.id + '/delete';
        });
    }

    // ── Handle Password Modal ──
    const passwordModal = document.getElementById('passwordModal');
    if (passwordModal) {
        passwordModal.addEventListener('show.bs.modal', function (e) {
            const btn   = e.relatedTarget;
            const name  = btn.dataset.name;
            const email = btn.dataset.email;

            passwordModal.querySelector('#passwordSubtitle').textContent = 'Resetting password for: ' + name;
            passwordModal.querySelector('#passwordEmail').value = email;
            
            // Clear previous inputs
            passwordModal.querySelectorAll('input[type=password]').forEach(i => i.value = '');
        });
    }

});
</script>

{{-- ═══════════════════════════════════════════
     MODAL — Reset Password
═══════════════════════════════════════════ --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content tm-modal">

            <div class="tm-modal-header" style="background: linear-gradient(135deg, #475569, #1E293B);">
                <div>
                    <h5 class="tm-modal-title"><i class="fa-solid fa-key me-2"></i>Reset Password</h5>
                    <p class="tm-modal-sub" id="passwordSubtitle">Updating teacher credentials</p>
                </div>
                <button type="button" class="tm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.user.updatePassword') }}" method="POST">
                @csrf
                <div class="tm-modal-body" style="gap: 0.75rem;">
                    <input type="hidden" name="email" id="passwordEmail">
                    
                    <div class="mb-2">
                        <label class="tm-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="tm-input" style="padding-left: 1rem;" required minlength="8" placeholder="Enter new password">
                    </div>
                    
                    <div class="mb-2">
                        <label class="tm-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="tm-input" style="padding-left: 1rem;" required minlength="8" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="tm-modal-footer">
                    <button type="button" class="tm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tm-btn-submit" style="background: linear-gradient(135deg, #475569, #1E293B);">
                        <i class="fa-solid fa-save me-2"></i>Update Password
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL — Edit Teacher
═══════════════════════════════════════════ --}}
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content tm-modal">

            <div class="tm-modal-header">
                <div>
                    <h5 class="tm-modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Teacher</h5>
                    <p class="tm-modal-sub">Update the teacher's profile information</p>
                </div>
                <button type="button" class="tm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="editTeacherForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="tm-modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="tm-label">Full Name <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-user tm-input-icon"></i>
                                <input type="text" id="edit_name" name="name" class="tm-input" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="tm-label">Email Address <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-envelope tm-input-icon"></i>
                                <input type="email" id="edit_email" name="email" class="tm-input" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="tm-label">Designation <span class="text-danger">*</span></label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-id-badge tm-input-icon"></i>
                                <input type="text" id="edit_designation" name="designation" class="tm-input" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="tm-label">Scopus / Profile Link</label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-link tm-input-icon"></i>
                                <input type="url" id="edit_scopus" name="scopus_link" class="tm-input" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="tm-label">Specialization</label>
                            <div class="tm-input-wrap">
                                <i class="fa-solid fa-graduation-cap tm-input-icon"></i>
                                <input type="text" id="edit_specialization" name="specialization" class="tm-input" placeholder="e.g. Data Analysis, AI">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="tm-label">LinkedIn URL</label>
                            <div class="tm-input-wrap">
                                <i class="fa-brands fa-linkedin tm-input-icon"></i>
                                <input type="url" id="edit_linkedin" name="linkedin_url" class="tm-input" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="tm-label">Short Biography</label>
                            <textarea id="edit_bio" name="bio" class="tm-input" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="tm-label">Replace Profile Picture <span class="tm-optional">(leave blank to keep current)</span></label>
                            <input type="file" name="profile_image" class="tm-file-input" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="tm-modal-footer">
                    <button type="button" class="tm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tm-btn-submit">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL — Delete Teacher
═══════════════════════════════════════════ --}}
<div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content tm-modal">

            <div class="tm-modal-header" style="background:linear-gradient(135deg,#EF4444,#DC2626);">
                <div>
                    <h5 class="tm-modal-title"><i class="fa-solid fa-triangle-exclamation me-2"></i>Delete Teacher</h5>
                    <p class="tm-modal-sub">This action cannot be undone</p>
                </div>
                <button type="button" class="tm-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="tm-modal-body text-center py-4">
                <div style="width:60px;height:60px;border-radius:50%;background:#FEF2F2;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="fa-solid fa-trash" style="font-size:1.4rem;color:#EF4444;"></i>
                </div>
                <p style="font-size:.95rem;color:#1E293B;font-weight:600;margin-bottom:.4rem;">
                    Are you sure you want to delete<br>
                    <span id="deleteTeacherName" class="text-danger"></span>?
                </p>
                <p style="font-size:.82rem;color:#64748B;margin:0;">
                    Their user account, course assignments, and profile will all be permanently removed.
                </p>
            </div>

            <form id="deleteTeacherForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="tm-modal-footer" style="justify-content:center;">
                    <button type="button" class="tm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tm-btn-submit" style="background:linear-gradient(135deg,#EF4444,#DC2626);box-shadow:0 4px 12px rgba(239,68,68,.28);">
                        <i class="fa-solid fa-trash me-2"></i>Yes, Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
.tm-btn-edit:hover   { background:#EFF6FF; color:#2563EB; border-color:#BFDBFE; }
.tm-btn-delete:hover { background:#FEF2F2; color:#EF4444; border-color:#FECACA; }
</style>

@endsection
