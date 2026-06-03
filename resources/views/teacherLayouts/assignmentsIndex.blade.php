@extends('applayouts.app')
@section('contents')
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
            <ul class="mb-0 ps-3 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="ai-header">
        <div>
            <h5 class="ai-title">Upload Assignment</h5>
            <p class="ai-subtitle">Post assignments to your classes and track student submissions</p>
        </div>
        <button class="ai-btn-new" data-bs-toggle="modal" data-bs-target="#postModal">
            <i class="fa-solid fa-plus me-2"></i>New Assignment
        </button>
    </div>

    {{-- ── Stats ── --}}
    <div class="ai-stats">
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-file-lines"></i></div>
            <div><p class="ai-stat-num">{{ $assignments->count() }}</p><p class="ai-stat-lbl">My Assignments</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-chalkboard"></i></div>
            <div><p class="ai-stat-num">{{ $classes->count() }}</p><p class="ai-stat-lbl">My Classes</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            @php $overdueCount = $assignments->filter(fn($a) => now()->gt($a->due_date))->count(); @endphp
            <div><p class="ai-stat-num">{{ $overdueCount }}</p><p class="ai-stat-lbl">Overdue</p></div>
        </div>
        <div class="ai-stat">
            <div class="ai-stat-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div><p class="ai-stat-num">{{ $assignments->sum('submissions_count') }}</p><p class="ai-stat-lbl">Total Submissions</p></div>
        </div>
    </div>

    {{-- ── Assignment List ── --}}
    <div class="ai-card">
        <div class="ai-card-head">
            <span><i class="fa-solid fa-list-check me-2 text-primary"></i>Posted Assignments</span>
            <span class="ai-count-badge">{{ $assignments->count() }} total</span>
        </div>

        @forelse($assignments as $a)
        @php $overdue = now()->gt($a->due_date); @endphp
        <div class="ai-row">
            <div class="ai-row-icon {{ $overdue ? 'overdue' : '' }}">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div class="ai-row-info">
                <p class="ai-row-title">{{ $a->title }}</p>
                <div class="ai-row-meta">
                    <span><i class="fa-solid fa-video me-1"></i>{{ $a->onlineClass?->title ?? 'Deleted class' }}</span>
                    <span>
                        <i class="fa-solid fa-calendar me-1"></i>
                        <span class="{{ $overdue ? 'text-danger fw-semibold' : '' }}">
                            {{ \Carbon\Carbon::parse($a->due_date)->format('d M Y, h:i A') }}
                        </span>
                        @if($overdue)<span class="ai-overdue-tag">Overdue</span>@endif
                    </span>
                    <span><i class="fa-solid fa-star me-1"></i>{{ $a->total_points }} pts</span>
                </div>
                @if($a->description)
                    <p class="ai-row-desc">{{ Str::limit($a->description, 80) }}</p>
                @endif
            </div>
            <div class="ai-row-right">
                <span class="ai-subs-pill {{ $a->submissions_count > 0 ? 'has' : '' }}">
                    <i class="fa-solid fa-file-import me-1"></i>{{ $a->submissions_count }} sub{{ $a->submissions_count != 1 ? 's' : '' }}
                </span>
                @if($a->file_path)
                    <a href="{{ asset('storage/'.$a->file_path) }}" target="_blank" class="ai-btn-file">
                        <i class="fa-solid fa-paperclip"></i>
                    </a>
                @endif
                <form action="{{ route('teacher.assignments.destroy', $a->id) }}" method="POST"
                      onsubmit="return confirm('Delete \'{{ addslashes($a->title) }}\'?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="ai-btn-del" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="ai-empty">
            <i class="fa-solid fa-file-circle-plus"></i>
            <p>No assignments posted yet. Click <strong>New Assignment</strong> to start.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ══ POST ASSIGNMENT MODAL ══ --}}
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content ai-modal">
            <div class="ai-modal-hdr">
                <div>
                    <h5 class="ai-modal-title"><i class="fa-solid fa-file-lines me-2"></i>Post New Assignment</h5>
                    <p class="ai-modal-sub">Students in the selected class will receive this task</p>
                </div>
                <button type="button" class="ai-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ai-modal-body">
                    <div class="ai-field">
                        <label class="ai-label">Class <span class="ai-req">*</span></label>
                        <select name="online_class_id" class="ai-input" required>
                            <option value="">Select your class…</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->title }}</option>
                            @endforeach
                        </select>
                        @if($classes->isEmpty())
                            <p class="ai-hint">You have no scheduled classes yet. Create one first.</p>
                        @endif
                    </div>
                    <div class="ai-field">
                        <label class="ai-label">Assignment Title <span class="ai-req">*</span></label>
                        <input type="text" name="title" class="ai-input" placeholder="e.g. Week 3 Research Task" required>
                    </div>
                    <div class="ai-row-2">
                        <div class="ai-field">
                            <label class="ai-label">Due Date & Time <span class="ai-req">*</span></label>
                            <input type="datetime-local" name="due_date" class="ai-input" required>
                        </div>
                        <div class="ai-field">
                            <label class="ai-label">Total Points <span class="ai-req">*</span></label>
                            <input type="number" name="total_points" class="ai-input" value="100" min="1" required>
                        </div>
                    </div>
                    <div class="ai-field">
                        <label class="ai-label">Instructions <span class="ai-opt">optional</span></label>
                        <textarea name="description" class="ai-input" rows="2" placeholder="Describe what students should submit…"></textarea>
                    </div>
                    <div class="ai-field">
                        <label class="ai-label">Attachment <span class="ai-opt">PDF / DOCX / ZIP · max 5 MB</span></label>
                        <input type="file" name="file" class="ai-input" accept=".pdf,.docx,.zip">
                    </div>
                </div>
                <div class="ai-modal-foot">
                    <button type="button" class="ai-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="ai-btn-post"><i class="fa-solid fa-paper-plane me-2"></i>Publish to Students</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.ai-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

.ai-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; }
.ai-title    { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.ai-subtitle { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }
.ai-btn-new {
    display:inline-flex; align-items:center;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    border:none; border-radius:10px; padding:.55rem 1.1rem;
    font-size:.82rem; font-weight:600; cursor:pointer;
    box-shadow:0 4px 12px rgba(79,70,229,.25); transition:all .2s;
}
.ai-btn-new:hover { transform:translateY(-1px); }

.ai-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.ai-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.ai-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.ai-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.ai-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

.ai-card { background:#fff; border-radius:14px; border:1.5px solid #F1F5F9; box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; }
.ai-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:.9rem 1.25rem; border-bottom:1px solid #F1F5F9;
    font-size:.85rem; font-weight:700; color:#1E293B;
}
.ai-count-badge { background:#EEF2FF; color:#4F46E5; padding:.2rem .7rem; border-radius:50px; font-size:.72rem; font-weight:700; }

.ai-row { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:.75rem; padding:1rem 1.25rem; border-bottom:1px solid #F8FAFF; }
.ai-row:last-child { border-bottom:none; }
.ai-row-icon {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:#EEF2FF; color:#4F46E5;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.ai-row-icon.overdue { background:#FEE2E2; color:#DC2626; }
.ai-row-info { flex:1; min-width:0; }
.ai-row-title { font-size:.9rem; font-weight:700; color:#1E293B; margin:0; }
.ai-row-meta  { display:flex; flex-wrap:wrap; gap:.6rem; font-size:.73rem; color:#64748B; margin:.25rem 0 0; }
.ai-row-meta span { display:inline-flex; align-items:center; gap:.2rem; }
.ai-row-desc  { font-size:.75rem; color:#94A3B8; margin:.25rem 0 0; }
.ai-overdue-tag { background:#FEE2E2; color:#DC2626; padding:.1rem .4rem; border-radius:50px; font-size:.65rem; font-weight:700; margin-left:3px; }

.ai-row-right { display:flex; align-items:center; gap:.5rem; flex-shrink:0; }
.ai-subs-pill { font-size:.73rem; font-weight:600; color:#94A3B8; }
.ai-subs-pill.has { color:#4F46E5; background:#EEF2FF; padding:.18rem .6rem; border-radius:50px; }
.ai-btn-file {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; background:#F1F5F9; color:#475569;
    border:none; border-radius:7px; font-size:.8rem; text-decoration:none; transition:all .15s;
}
.ai-btn-file:hover { background:#4F46E5; color:#fff; }
.ai-btn-del {
    width:30px; height:30px; background:#FEE2E2; color:#DC2626; border:none;
    border-radius:7px; display:flex; align-items:center; justify-content:center;
    font-size:.75rem; cursor:pointer; transition:all .15s;
}
.ai-btn-del:hover { background:#DC2626; color:#fff; }

.ai-empty { text-align:center; padding:3rem 1rem; color:#CBD5E1; }
.ai-empty i { font-size:2rem; display:block; margin-bottom:.5rem; }
.ai-empty p { margin:0; font-size:.85rem; }

/* Modal */
.ai-modal { border:none; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.12); }
.ai-modal-hdr { display:flex; align-items:flex-start; justify-content:space-between; padding:1.3rem 1.5rem 1rem; background:linear-gradient(135deg,#4F46E5,#7C3AED); }
.ai-modal-title { font-size:.975rem; font-weight:700; color:#fff; margin:0; }
.ai-modal-sub   { font-size:.78rem; color:rgba(255,255,255,.75); margin:.2rem 0 0; }
.ai-modal-close {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.8rem;
}
.ai-modal-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.75rem; max-height:65vh; overflow-y:auto; }
.ai-modal-foot { padding:1rem 1.5rem 1.3rem; display:flex; justify-content:flex-end; gap:.6rem; border-top:1px solid #F1F5F9; }

.ai-field { display:flex; flex-direction:column; gap:.3rem; }
.ai-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
.ai-label { font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.4px; }
.ai-req { color:#EF4444; }
.ai-opt { font-weight:400; text-transform:none; letter-spacing:0; color:#CBD5E1; }
.ai-hint { font-size:.72rem; color:#F59E0B; margin:.2rem 0 0; }
.ai-input {
    border:1.5px solid #E2E8F0; border-radius:9px; padding:.5rem .75rem;
    font-size:.845rem; color:#1E293B; background:#fff; outline:none;
    transition:border-color .15s; width:100%;
}
.ai-input:focus { border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.08); }
textarea.ai-input { resize:vertical; }

.ai-btn-cancel { padding:.5rem 1.1rem; border:1.5px solid #E2E8F0; border-radius:9px; background:#fff; color:#64748B; font-size:.855rem; font-weight:600; cursor:pointer; }
.ai-btn-post {
    padding:.5rem 1.25rem; border:none; border-radius:9px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.855rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center;
    box-shadow:0 4px 12px rgba(79,70,229,.25);
}

@media(max-width:991.98px) { .ai-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) { .ai-stats { grid-template-columns:repeat(2,1fr); } .ai-row-2 { grid-template-columns:1fr; } }
</style>
@endsection
