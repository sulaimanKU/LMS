@extends('applayouts.app')
@section('contents')
<style>
/* ── Page Wrapper ── */
.ai-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.ai-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem; }
.ai-title    { font-size:1.3rem; font-weight: 800; color:#1E293B; margin:0; }
.ai-subtitle { font-size:.82rem; color:#64748B; margin:.15rem 0 0; }
.ai-btn-new {
    display:inline-flex; align-items:center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    border:none; border-radius:10px; padding:.6rem 1.25rem;
    font-size:.85rem; font-weight:700; cursor:pointer;
    box-shadow:0 4px 12px rgba(79,70,229,.25); transition:all .2s;
    text-decoration: none;
}
.ai-btn-new:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(79,70,229,.35); color: #fff; }

/* ── Stats ── */
.ai-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.75rem; }
.ai-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:16px; padding:1.1rem; display:flex; align-items:center; gap:.9rem; box-shadow:0 1px 6px rgba(0,0,0,.04); }
.ai-stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
.ai-stat-num { font-size:1.4rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.ai-stat-lbl { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:#94A3B8; margin:.2rem 0 0; }

/* ── List Card ── */
.ai-card { background:#fff; border-radius:16px; border:1.5px solid #F1F5F9; box-shadow:0 1px 8px rgba(0,0,0,.05); overflow:hidden; }
.ai-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:1.1rem 1.5rem; border-bottom:1.5px solid #F1F5F9;
    font-size:.9rem; font-weight:700; color:#1E293B;
}
.ai-count-badge { background:#EEF2FF; color:#4F46E5; padding:.2rem .75rem; border-radius:50px; font-size:.72rem; font-weight:800; }

/* ── Assignment Row ── */
.ai-row { display:flex; align-items:center; gap:1.25rem; padding:1.25rem 1.5rem; border-bottom:1px solid #F8FAFF; transition: background 0.2s; }
.ai-row:last-child { border-bottom:none; }
.ai-row:hover { background: #FAFBFF; }

.ai-row-icon {
    width:42px; height:42px; border-radius:12px; flex-shrink:0;
    background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:1.1rem;
}
.ai-row-icon.overdue { background:#FEE2E2; color:#DC2626; }

.ai-row-info { flex:1; min-width:0; }
.ai-row-title { font-size:.92rem; font-weight:700; color:#1E293B; margin:0; }
.ai-row-meta  { display:flex; flex-wrap:wrap; gap:.85rem; font-size:.73rem; color:#64748B; margin:.3rem 0 0; }
.ai-row-meta span { display:inline-flex; align-items:center; gap:.35rem; }
.ai-row-desc  { font-size:.75rem; color:#94A3B8; margin:.4rem 0 0; line-height: 1.4; }
.ai-overdue-tag { background:#EF4444; color:#fff; padding:.1rem .5rem; border-radius:4px; font-size:.6rem; font-weight:800; text-transform: uppercase; margin-left: 5px; }

.ai-row-right { display:flex; align-items:center; gap:.6rem; flex-shrink:0; }
.ai-subs-pill { font-size:.7rem; font-weight:800; color:#94A3B8; text-transform: uppercase; }
.ai-subs-pill.has { color:#4F46E5; background:#EEF2FF; padding:.25rem .75rem; border-radius:50px; }

.ai-btn-file {
    display:inline-flex; align-items:center; justify-content:center;
    width:34px; height:34px; background:#fff; color:#475569;
    border:1.5px solid #E2E8F0; border-radius:9px; font-size:.9rem; text-decoration:none; transition:all .15s;
}
.ai-btn-file:hover { border-color:#4F46E5; color:#4F46E5; background:#F5F7FF; }

.ai-btn-del {
    width:34px; height:34px; background:#fff; color:#64748B; border:1.5px solid #E2E8F0;
    border-radius:9px; display:flex; align-items:center; justify-content:center;
    font-size:.85rem; cursor:pointer; transition:all .15s;
}
.ai-btn-del:hover { background:#FEF2F2; color:#EF4444; border-color:#FECACA; }

/* ── Modal ── */
.ai-modal { border:none; border-radius:20px; overflow:hidden; box-shadow:0 25px 70px rgba(0,0,0,.15); }
.ai-modal-hdr { display:flex; align-items:flex-start; justify-content:space-between; padding:1.5rem 1.75rem 1.25rem; background:linear-gradient(135deg,#4F46E5,#7C3AED); }
.ai-modal-title { font-size:1.1rem; font-weight:700; color:#fff; margin:0; }
.ai-modal-sub   { font-size:.78rem; color:rgba(255,255,255,.8); margin:.25rem 0 0; }
.ai-modal-close {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; width:32px; height:32px; border-radius:10px;
    display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.9rem;
}
.ai-modal-body { padding:1.5rem 1.75rem; display:flex; flex-direction:column; gap:1.1rem; }
.ai-modal-foot { padding:1.25rem 1.75rem 1.5rem; display:flex; justify-content:flex-end; gap:.75rem; border-top:1px solid #F1F5F9; }

.ai-field { display:flex; flex-direction:column; gap:.4rem; }
.ai-label { font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.5px; }
.ai-input {
    border:1.5px solid #E2E8F0; border-radius:12px; padding:.65rem .9rem;
    font-size:.9rem; color:#1E293B; background:#F8FAFF; outline:none;
    transition:all .2s; width:100%;
}
.ai-input:focus { border-color:#4F46E5; background:#fff; box-shadow:0 0 0 4px rgba(79,70,229,.1); }
textarea.ai-input { resize:vertical; min-height: 80px; }

.ai-btn-cancel { padding:.65rem 1.35rem; border:1.5px solid #E2E8F0; border-radius:12px; background:#fff; color:#64748B; font-size:.88rem; font-weight:700; cursor:pointer; }
.ai-btn-post {
    padding:.65rem 1.5rem; border:none; border-radius:12px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.88rem; font-weight:700; cursor:pointer;
    display:inline-flex; align-items:center;
    box-shadow:0 4px 12px rgba(79,70,229,.25); transition: all 0.2s;
}
.ai-btn-post:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.35); }

@media(max-width:991.98px) { .ai-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) { .ai-stats { grid-template-columns:repeat(2,1fr); } .ai-row-2 { grid-template-columns:1fr; } }
</style>

<div class="ai-page">

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
            <ul class="mb-0 ps-3 mt-1" style="font-size: 0.85rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="ai-header">
        <div>
            <h5 class="ai-title">Assignment Portal</h5>
            <p class="ai-subtitle">Create and manage academic tasks for your active modules.</p>
        </div>
        <button class="ai-btn-new" data-bs-toggle="modal" data-bs-target="#postModal">
            <i class="fa-solid fa-plus me-2"></i>Create New Assignment
        </button>
    </div>

    {{-- ── Stats ── --}}
    <div class="ai-stats">
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-file-signature"></i></div>
            <div><p class="ai-stat-num">{{ $assignments->count() }}</p><p class="ai-stat-lbl">Active Tasks</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-layer-group"></i></div>
            <div><p class="ai-stat-num">{{ $myModules->count() }}</p><p class="ai-stat-lbl">My Modules</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-clock"></i></div>
            @php $overdueCount = $assignments->filter(fn($a) => now()->gt($a->due_date))->count(); @endphp
            <div><p class="ai-stat-num">{{ $overdueCount }}</p><p class="ai-stat-lbl">Past Deadlines</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#F5F3FF;color:#7C3AED;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div><p class="ai-stat-num">{{ $assignments->sum('submissions_count') }}</p><p class="ai-stat-lbl">Total Workloads</p></div>
        </div>
    </div>

    {{-- ── Assignment List ── --}}
    <div class="ai-card">
        <div class="ai-card-head">
            <span><i class="fa-solid fa-box-archive me-2 text-primary"></i>Task History</span>
            <span class="ai-count-badge">{{ $assignments->count() }} Recorded</span>
        </div>

        @forelse($assignments as $a)
        @php 
            $overdue = now()->gt($a->due_date);
            $classTitle = $a->onlineClass?->title ?? 'General Assignment';
            $moduleTitle = $a->onlineClass?->module?->title ?? 'N/A';
        @endphp
        <div class="ai-row">
            <div class="ai-row-icon {{ $overdue ? 'overdue' : '' }}">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div class="ai-row-info">
                <p class="ai-row-title">{{ $a->title }} @if($overdue)<span class="ai-overdue-tag">Closed</span>@endif</p>
                <div class="ai-row-meta">
                    <span title="Module"><i class="fa-solid fa-layer-group me-1"></i>{{ $moduleTitle }}</span>
                    <span>
                        <i class="fa-solid fa-calendar-check me-1"></i>
                        <span class="{{ $overdue ? 'text-danger fw-bold' : '' }}">
                            Due: {{ \Carbon\Carbon::parse($a->due_date)->format('d M Y, h:i A') }}
                        </span>
                    </span>
                    <span><i class="fa-solid fa-star me-1"></i>{{ $a->total_points }} Points</span>
                </div>
                @if($a->description)
                    <p class="ai-row-desc">{{ Str::limit($a->description, 100) }}</p>
                @endif
            </div>
            <div class="ai-row-right">
                <a href="{{ route('assignmentReviews.view') }}?assignment_id={{ $a->id }}" class="ai-subs-pill {{ $a->submissions_count > 0 ? 'has' : '' }}" style="text-decoration: none;">
                    {{ $a->submissions_count }} Submission{{ $a->submissions_count != 1 ? 's' : '' }}
                </a>
                <div class="ai-action-group d-flex gap-2">
                    <a href="{{ route('assignmentReviews.view') }}?assignment_id={{ $a->id }}" class="ai-btn-file" title="View Student Submissions">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </a>
                    @if($a->file_path)
                        <a href="{{ asset('storage/'.$a->file_path) }}" target="_blank" class="ai-btn-file" title="Download Material">
                            <i class="fa-solid fa-paperclip"></i>
                        </a>
                    @endif
                    <form action="{{ route('teacher.assignments.destroy', $a->id) }}" method="POST"
                          onsubmit="return confirm('Delete \'{{ addslashes($a->title) }}\'? This will also delete all student submissions for this task.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="ai-btn-del" title="Permanently Delete">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="ai-empty">
            <div style="font-size: 3rem; opacity: 0.15; margin-bottom: 1rem;"><i class="fa-solid fa-file-circle-plus"></i></div>
            <p class="fw-bold text-dark">No assignments found</p>
            <p class="text-muted small">Post your first assignment to start tracking student progress.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ══ POST ASSIGNMENT MODAL ══ --}}
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
        <div class="modal-content ai-modal">
            <div class="ai-modal-hdr">
                <div>
                    <h5 class="ai-modal-title"><i class="fa-solid fa-paper-plane me-2"></i>Publish New Task</h5>
                    <p class="ai-modal-sub">Assignment will be immediately visible to enrolled students</p>
                </div>
                <button type="button" class="ai-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ai-modal-body">
                    <div class="ai-field">
                        <label class="ai-label">Target Module <span class="text-danger">*</span></label>
                        <select name="module_id" class="ai-input" required>
                            <option value="" disabled selected>Select a module...</option>
                            @foreach($myModules as $mod)
                                <option value="{{ $mod->id }}">{{ $mod->title }}</option>
                            @endforeach
                        </select>
                        @if($myModules->isEmpty())
                            <p class="ai-hint text-warning mt-1" style="font-size: 0.7rem;"><i class="fa-solid fa-circle-exclamation me-1"></i> You have no modules assigned to you yet.</p>
                        @endif
                    </div>
                    
                    <div class="ai-field">
                        <label class="ai-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="ai-input" placeholder="e.g. Analysis of Research Paper" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="ai-field">
                            <label class="ai-label">Deadline <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="due_date" class="ai-input" required>
                        </div>
                        <div class="ai-field">
                            <label class="ai-label">Max Points <span class="text-danger">*</span></label>
                            <input type="number" name="total_points" class="ai-input" value="100" min="1" required>
                        </div>
                    </div>

                    <div class="ai-field">
                        <label class="ai-label">Submission Instructions</label>
                        <textarea name="description" class="ai-input" placeholder="Enter requirements, file formats, or specific tasks..."></textarea>
                    </div>

                    <div class="ai-field">
                        <label class="ai-label">Reference Material <span style="font-size: 0.65rem; font-weight: 400;" class="text-muted">(Optional · PDF, JPG, PPT, ZIP · Max 5MB)</span></label>
                        <input type="file" name="file" class="ai-input" style="padding-top: 0.55rem;" accept=".pdf,.docx,.zip,.jpg,.jpeg,.png,.ppt,.pptx">
                    </div>
                </div>
                <div class="ai-modal-foot">
                    <button type="button" class="ai-btn-cancel" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="ai-btn-post"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Publish Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

