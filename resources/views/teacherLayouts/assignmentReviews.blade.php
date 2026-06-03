@extends('applayouts.app')
@section('contents')
<div class="ar-page">

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
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
    <div class="ar-header">
        <div>
            <h5 class="ar-title">My Assignments</h5>
            <p class="ar-subtitle">Post assignments to your classes and review student submissions</p>
        </div>
        <button class="ar-btn-new" data-bs-toggle="modal" data-bs-target="#postAssignmentModal">
            <i class="fa-solid fa-plus me-2"></i>New Assignment
        </button>
    </div>

    {{-- ── Stats ── --}}
    <div class="ar-stats">
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-file-lines"></i></div>
            <div><p class="ar-stat-num">{{ $myAssignments->count() }}</p><p class="ar-stat-lbl">Posted</p></div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div><p class="ar-stat-num">{{ $studentSubmissions->count() }}</p><p class="ar-stat-lbl">Submissions</p></div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><p class="ar-stat-num">{{ $studentSubmissions->where('status','pending')->count() }}</p><p class="ar-stat-lbl">Pending</p></div>
        </div>
        <div class="ar-stat">
            <div class="ar-stat-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-star"></i></div>
            <div><p class="ar-stat-num">{{ $studentSubmissions->where('status','graded')->count() }}</p><p class="ar-stat-lbl">Graded</p></div>
        </div>
    </div>

    {{-- ── Tabs ── --}}
    <div class="ar-tab-bar">
        <div class="ar-tabs">
            <button class="ar-tab active" data-target="tab-posted">
                <i class="fa-solid fa-upload me-2"></i>My Uploads
                <span class="ar-tab-count">{{ $myAssignments->count() }}</span>
            </button>
            <button class="ar-tab" data-target="tab-submissions">
                <i class="fa-solid fa-inbox me-2"></i>Student Submissions
                @if($studentSubmissions->where('status','pending')->count() > 0)
                    <span class="ar-tab-count ar-tab-count-warn">{{ $studentSubmissions->where('status','pending')->count() }}</span>
                @endif
            </button>
        </div>
    </div>

    {{-- ── Tab: My Uploads ── --}}
    <div class="ar-tab-panel active" id="tab-posted">
        @forelse($myAssignments as $a)
        @php $overdue = now()->gt($a->due_date); @endphp
        <div class="ar-assign-row">
            <div class="ar-assign-main">
                <div class="ar-assign-icon {{ $overdue ? 'overdue' : '' }}">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div class="ar-assign-info">
                    <p class="ar-assign-title">{{ $a->title }}</p>
                    <p class="ar-assign-meta">
                        <span><i class="fa-solid fa-video me-1"></i>{{ $a->onlineClass?->title ?? 'Deleted class' }}</span>
                        <span><i class="fa-solid fa-calendar me-1"></i>Due: <strong class="{{ $overdue ? 'text-danger' : '' }}">{{ \Carbon\Carbon::parse($a->due_date)->format('d M Y, h:i A') }}</strong></span>
                        <span><i class="fa-solid fa-star me-1"></i>{{ $a->total_points }} pts</span>
                    </p>
                    @if($a->description)
                        <p class="ar-assign-desc">{{ $a->description }}</p>
                    @endif
                </div>
            </div>
            <div class="ar-assign-right">
                <span class="ar-subs-badge {{ $a->submissions_count > 0 ? 'has' : '' }}">
                    <i class="fa-solid fa-file-import me-1"></i>{{ $a->submissions_count }} submission{{ $a->submissions_count != 1 ? 's' : '' }}
                </span>
                @if($a->file_path)
                    <a href="{{ asset('storage/'.$a->file_path) }}" target="_blank" class="ar-btn-file">
                        <i class="fa-solid fa-paperclip me-1"></i>File
                    </a>
                @endif
                <form action="{{ route('teacher.assignments.destroy', $a->id) }}" method="POST"
                      onsubmit="return confirm('Delete \'{{ addslashes($a->title) }}\'?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="ar-btn-delete" title="Delete assignment">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="ar-empty">
            <i class="fa-solid fa-file-circle-plus"></i>
            <p>No assignments posted yet. Click <strong>New Assignment</strong> to get started.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Tab: Student Submissions ── --}}
    <div class="ar-tab-panel" id="tab-submissions">
        @forelse($studentSubmissions as $sub)
        @php
            $isLate   = $sub->submitted_at && $sub->assignment && $sub->submitted_at->gt($sub->assignment->due_date);
            $isGraded = $sub->status === 'graded';
        @endphp
        <div class="ar-sub-row">
            <div class="ar-sub-avatar">{{ strtoupper(substr($sub->user?->name ?? 'S', 0, 1)) }}</div>
            <div class="ar-sub-info">
                <p class="ar-sub-student">{{ $sub->user?->name ?? '—' }}</p>
                <p class="ar-sub-assign">{{ $sub->assignment?->title ?? '—' }}</p>
                <p class="ar-sub-meta">
                    <span><i class="fa-solid fa-clock me-1"></i>{{ $sub->submitted_at ? $sub->submitted_at->format('d M, h:i A') : '—' }}</span>
                    @if($isLate)<span class="ar-late-tag">Late</span>@endif
                </p>
            </div>
            <div class="ar-sub-right">
                @if($isGraded)
                    <span class="ar-grade-badge">{{ $sub->grade }}/{{ $sub->assignment?->total_points ?? 100 }}</span>
                @endif
                <span class="ar-status-pill {{ $isGraded ? 'graded' : 'pending' }}">{{ ucfirst($sub->status) }}</span>
                <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank" class="ar-btn-file">
                    <i class="fa-solid fa-download me-1"></i>File
                </a>
                <button class="ar-btn-grade {{ $isGraded ? 'regraded' : '' }}"
                        data-bs-toggle="modal" data-bs-target="#gradeModal{{ $sub->id }}">
                    <i class="fa-solid fa-{{ $isGraded ? 'pen' : 'star' }} me-1"></i>{{ $isGraded ? 'Edit' : 'Grade' }}
                </button>
            </div>
        </div>
        @empty
        <div class="ar-empty">
            <i class="fa-solid fa-inbox"></i>
            <p>No student submissions yet.</p>
        </div>
        @endforelse
    </div>

</div>

{{-- ══ POST ASSIGNMENT MODAL ══ --}}
<div class="modal fade" id="postAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content ar-modal">
            <div class="ar-modal-hdr">
                <div>
                    <h5 class="ar-modal-title"><i class="fa-solid fa-file-lines me-2"></i>Post New Assignment</h5>
                    <p class="ar-modal-sub">Students in the selected class will receive this</p>
                </div>
                <button type="button" class="ar-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ar-modal-body">
                    <div class="ar-field">
                        <label class="ar-label">Class <span class="ar-req">*</span></label>
                        <select name="online_class_id" class="ar-input" required>
                            <option value="">Select your class…</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->title }}</option>
                            @endforeach
                        </select>
                        @if($classes->isEmpty())
                            <p class="ar-field-hint">You have no scheduled classes yet.</p>
                        @endif
                    </div>
                    <div class="ar-field">
                        <label class="ar-label">Assignment Title <span class="ar-req">*</span></label>
                        <input type="text" name="title" class="ar-input" placeholder="e.g. Week 3 Research Task" required>
                    </div>
                    <div class="ar-row-2">
                        <div class="ar-field">
                            <label class="ar-label">Due Date & Time <span class="ar-req">*</span></label>
                            <input type="datetime-local" name="due_date" class="ar-input" required>
                        </div>
                        <div class="ar-field">
                            <label class="ar-label">Total Points <span class="ar-req">*</span></label>
                            <input type="number" name="total_points" class="ar-input" value="100" min="1" required>
                        </div>
                    </div>
                    <div class="ar-field">
                        <label class="ar-label">Instructions <span class="ar-opt">optional</span></label>
                        <textarea name="description" class="ar-input" rows="2" placeholder="Describe what students should submit…"></textarea>
                    </div>
                    <div class="ar-field">
                        <label class="ar-label">Attachment <span class="ar-opt">PDF / DOCX / ZIP — max 5 MB</span></label>
                        <input type="file" name="file" class="ar-input" accept=".pdf,.docx,.zip">
                    </div>
                </div>
                <div class="ar-modal-foot">
                    <button type="button" class="ar-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="ar-btn-post"><i class="fa-solid fa-paper-plane me-2"></i>Publish to Students</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══ GRADE MODALS ══ --}}
@foreach($studentSubmissions as $sub)
<div class="modal fade" id="gradeModal{{ $sub->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content ar-modal">
            <div class="ar-modal-hdr">
                <div>
                    <h5 class="ar-modal-title"><i class="fa-solid fa-star me-2"></i>Grade Submission</h5>
                    <p class="ar-modal-sub">{{ $sub->user?->name }} — {{ $sub->assignment?->title }}</p>
                </div>
                <button type="button" class="ar-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('teacher.submissions.grade') }}" method="POST">
                @csrf
                <input type="hidden" name="submission_id" value="{{ $sub->id }}">
                <div class="ar-modal-body">
                    <div class="ar-grade-display">
                        <p class="ar-grade-lbl">Score out of {{ $sub->assignment?->total_points ?? 100 }}</p>
                        <input type="number" name="grade" class="ar-grade-input"
                               value="{{ $sub->grade }}" min="0" max="{{ $sub->assignment?->total_points ?? 100 }}"
                               placeholder="0" required>
                    </div>
                    <div class="ar-field">
                        <label class="ar-label">Feedback <span class="ar-opt">optional</span></label>
                        <textarea name="teacher_comment" class="ar-input" rows="3"
                                  placeholder="Write your feedback…">{{ $sub->teacher_comment }}</textarea>
                    </div>
                    <a href="{{ asset('storage/'.$sub->file_path) }}" target="_blank" class="ar-sub-preview">
                        <i class="fa-solid fa-file-arrow-down me-2"></i>Download & Review Submission
                    </a>
                </div>
                <div class="ar-modal-foot">
                    <button type="button" class="ar-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="ar-btn-post"><i class="fa-solid fa-check me-2"></i>Submit Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
.ar-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* Header */
.ar-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.ar-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.ar-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }
.ar-btn-new {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    border: none; border-radius: 10px; padding: .55rem 1.1rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.ar-btn-new:hover { transform: translateY(-1px); }

/* Stats */
.ar-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
.ar-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.ar-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.ar-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.ar-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Tabs */
.ar-tab-bar { background:#fff; border:1.5px solid #F1F5F9; border-radius:12px 12px 0 0; border-bottom:none; padding:.5rem .75rem 0; }
.ar-tabs { display:flex; gap:0; }
.ar-tab {
    display:inline-flex; align-items:center; gap:6px;
    padding:.6rem 1rem; border:none; background:transparent;
    font-size:.82rem; font-weight:600; color:#64748B; cursor:pointer;
    border-bottom:2.5px solid transparent; transition:all .15s;
}
.ar-tab.active { color:#4F46E5; border-bottom-color:#4F46E5; }
.ar-tab-count { background:#EEF2FF; color:#4F46E5; padding:.1rem .45rem; border-radius:50px; font-size:.68rem; font-weight:800; }
.ar-tab-count-warn { background:#FEF3C7 !important; color:#D97706 !important; }

/* Panels */
.ar-tab-panel { background:#fff; border:1.5px solid #F1F5F9; border-radius:0 0 12px 12px; display:none; }
.ar-tab-panel.active { display:block; }

/* Assignment row */
.ar-assign-row {
    display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap;
    gap:.75rem; padding:1rem 1.25rem; border-bottom:1px solid #F8FAFF;
}
.ar-assign-row:last-child { border-bottom:none; }
.ar-assign-main { display:flex; align-items:flex-start; gap:.9rem; flex:1; min-width:0; }
.ar-assign-icon {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.ar-assign-icon.overdue { background:#FEE2E2; color:#DC2626; }
.ar-assign-title { font-size:.9rem; font-weight:700; color:#1E293B; margin:0; }
.ar-assign-meta  { display:flex; flex-wrap:wrap; gap:.6rem; font-size:.73rem; color:#64748B; margin:.25rem 0 0; }
.ar-assign-meta span { display:inline-flex; align-items:center; }
.ar-assign-desc  { font-size:.75rem; color:#94A3B8; margin:.25rem 0 0; }

.ar-assign-right { display:flex; align-items:center; gap:.5rem; flex-shrink:0; }
.ar-subs-badge { font-size:.73rem; font-weight:600; color:#94A3B8; }
.ar-subs-badge.has { color:#4F46E5; background:#EEF2FF; padding:.18rem .6rem; border-radius:50px; }
.ar-btn-file {
    display:inline-flex; align-items:center;
    background:#F1F5F9; color:#475569; border:none; border-radius:7px;
    padding:.3rem .65rem; font-size:.73rem; font-weight:600; text-decoration:none; cursor:pointer;
    transition:all .15s;
}
.ar-btn-file:hover { background:#4F46E5; color:#fff; }
.ar-btn-delete {
    background:#FEE2E2; color:#DC2626; border:none; width:30px; height:30px;
    border-radius:7px; display:flex; align-items:center; justify-content:center;
    font-size:.72rem; cursor:pointer; transition:all .15s;
}
.ar-btn-delete:hover { background:#DC2626; color:#fff; }

/* Submission row */
.ar-sub-row {
    display:flex; align-items:center; gap:.9rem; flex-wrap:wrap;
    padding:1rem 1.25rem; border-bottom:1px solid #F8FAFF;
}
.ar-sub-row:last-child { border-bottom:none; }
.ar-sub-avatar {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.85rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.ar-sub-info { flex:1; min-width:0; }
.ar-sub-student { font-size:.88rem; font-weight:700; color:#1E293B; margin:0; }
.ar-sub-assign  { font-size:.75rem; color:#64748B; margin:.1rem 0 .15rem; }
.ar-sub-meta    { display:flex; align-items:center; gap:.5rem; font-size:.72rem; color:#94A3B8; margin:0; }
.ar-late-tag    { background:#FEF3C7; color:#92400E; padding:.1rem .4rem; border-radius:50px; font-size:.65rem; font-weight:700; }

.ar-sub-right { display:flex; align-items:center; gap:.5rem; flex-shrink:0; flex-wrap:wrap; }
.ar-grade-badge { font-size:.85rem; font-weight:800; color:#065F46; }
.ar-status-pill { padding:.18rem .6rem; border-radius:50px; font-size:.7rem; font-weight:700; }
.ar-status-pill.graded  { background:#D1FAE5; color:#065F46; }
.ar-status-pill.pending { background:#FEF3C7; color:#92400E; }
.ar-btn-grade {
    display:inline-flex; align-items:center;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff; border:none;
    border-radius:8px; padding:.35rem .8rem; font-size:.75rem; font-weight:600; cursor:pointer;
}
.ar-btn-grade.regraded { background:#F1F5F9; color:#475569; }

/* Empty */
.ar-empty { text-align:center; padding:3rem 1rem; color:#CBD5E1; }
.ar-empty i { font-size:2rem; display:block; margin-bottom:.5rem; }
.ar-empty p { margin:0; font-size:.85rem; }

/* Modal */
.ar-modal { border:none; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.12); }
.ar-modal-hdr {
    display:flex; align-items:flex-start; justify-content:space-between;
    padding:1.3rem 1.5rem 1rem; background:linear-gradient(135deg,#4F46E5,#7C3AED);
}
.ar-modal-title { font-size:.975rem; font-weight:700; color:#fff; margin:0; }
.ar-modal-sub   { font-size:.78rem; color:rgba(255,255,255,.75); margin:.2rem 0 0; }
.ar-modal-close {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.8rem;
}
.ar-modal-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.75rem; max-height:65vh; overflow-y:auto; }
.ar-modal-foot { padding:1rem 1.5rem 1.3rem; display:flex; justify-content:flex-end; gap:.6rem; border-top:1px solid #F1F5F9; }

.ar-field { display:flex; flex-direction:column; gap:.3rem; }
.ar-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
.ar-label { font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.4px; }
.ar-req { color:#EF4444; } .ar-opt { font-weight:400; text-transform:none; letter-spacing:0; color:#CBD5E1; }
.ar-field-hint { font-size:.72rem; color:#F59E0B; margin:.2rem 0 0; }
.ar-input {
    border:1.5px solid #E2E8F0; border-radius:9px; padding:.5rem .75rem;
    font-size:.845rem; color:#1E293B; background:#fff; outline:none;
    transition:border-color .15s; width:100%;
}
.ar-input:focus { border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.08); }
textarea.ar-input { resize:vertical; }

.ar-grade-display { text-align:center; padding:.5rem 0; }
.ar-grade-lbl  { font-size:.75rem; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; margin:0 0 .5rem; }
.ar-grade-input {
    width:120px; font-size:2.5rem; font-weight:800; color:#4F46E5; text-align:center;
    border:2px solid #E2E8F0; border-radius:12px; padding:.3rem .5rem; outline:none;
}
.ar-grade-input:focus { border-color:#7C3AED; }

.ar-sub-preview {
    display:flex; align-items:center; justify-content:center;
    background:#F8FAFF; border:1.5px dashed #C7D2FE; border-radius:10px;
    padding:.65rem 1rem; font-size:.82rem; font-weight:600; color:#4F46E5; text-decoration:none;
}

.ar-btn-cancel { padding:.5rem 1.1rem; border:1.5px solid #E2E8F0; border-radius:9px; background:#fff; color:#64748B; font-size:.855rem; font-weight:600; cursor:pointer; }
.ar-btn-post {
    padding:.5rem 1.25rem; border:none; border-radius:9px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.855rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center;
    box-shadow:0 4px 12px rgba(79,70,229,.25);
}

@media(max-width:991.98px) { .ar-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) {
    .ar-stats { grid-template-columns:repeat(2,1fr); }
    .ar-row-2 { grid-template-columns:1fr; }
    .ar-assign-row, .ar-sub-row { flex-direction:column; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs   = document.querySelectorAll('.ar-tab');
    const panels = document.querySelectorAll('.ar-tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.target).classList.add('active');
        });
    });
});
</script>

@endsection
