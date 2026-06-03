@extends('applayouts.app')
@section('contents')
<div class="su-page">

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
    <div class="su-header">
        <div>
            <h5 class="su-title">My Assignments</h5>
            <p class="su-subtitle">View tasks from your enrolled classes and submit your work</p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    @php
        $submitted  = $assignments->filter(fn($a) => $a->submissions->isNotEmpty())->count();
        $pending    = $assignments->filter(fn($a) => $a->submissions->isEmpty() && now()->lte($a->due_date))->count();
        $overdueCnt = $assignments->filter(fn($a) => $a->submissions->isEmpty() && now()->gt($a->due_date))->count();
    @endphp
    <div class="su-stats">
        <div class="su-stat">
            <div class="su-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-file-lines"></i></div>
            <div><p class="su-stat-num">{{ $assignments->count() }}</p><p class="su-stat-lbl">Total Tasks</p></div>
        </div>
        <div class="su-stat">
            <div class="su-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="su-stat-num">{{ $submitted }}</p><p class="su-stat-lbl">Submitted</p></div>
        </div>
        <div class="su-stat">
            <div class="su-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><p class="su-stat-num">{{ $pending }}</p><p class="su-stat-lbl">Pending</p></div>
        </div>
        <div class="su-stat">
            <div class="su-stat-icon" style="background:#FEE2E2;color:#DC2626;"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div><p class="su-stat-num">{{ $overdueCnt }}</p><p class="su-stat-lbl">Overdue</p></div>
        </div>
    </div>

    {{-- ── Assignment Cards ── --}}
    @if($assignments->isEmpty())
    <div class="su-empty-wrap">
        <i class="fa-solid fa-file-circle-xmark"></i>
        <p>No assignments found for your enrolled classes yet.</p>
    </div>
    @else
    <div class="su-grid">
        @foreach($assignments as $a)
        @php
            $submission = $a->submissions->first();
            $isOverdue  = now()->gt($a->due_date);
            $isGraded   = $submission && $submission->status === 'graded';
            $isLate     = $submission && (
                $submission->status === 'late' ||
                ($submission->submitted_at && $submission->submitted_at->gt($a->due_date))
            );

            if ($submission && $isGraded)    $state = 'graded';
            elseif ($submission && $isLate)  $state = 'late';
            elseif ($submission)             $state = 'submitted';
            elseif ($isOverdue)              $state = 'overdue';
            else                             $state = 'pending';
        @endphp
        <div class="su-card su-card--{{ $state }}">

            {{-- status strip --}}
            <div class="su-strip su-strip--{{ $state }}"></div>

            <div class="su-card-body">
                {{-- header row --}}
                <div class="su-card-top">
                    <div class="su-card-icon su-card-icon--{{ $state }}">
                        <i class="fa-solid fa-{{ $state === 'graded' ? 'star' : ($state === 'submitted' ? 'circle-check' : ($state === 'late' ? 'clock-rotate-left' : ($state === 'overdue' ? 'triangle-exclamation' : 'file-lines'))) }}"></i>
                    </div>
                    <div class="su-card-status">
                        @if($state === 'graded')
                            <span class="su-badge su-badge--graded"><i class="fa-solid fa-star me-1"></i>Graded</span>
                        @elseif($state === 'late')
                            <span class="su-badge su-badge--late"><i class="fa-solid fa-clock-rotate-left me-1"></i>Late Submission</span>
                        @elseif($state === 'submitted')
                            <span class="su-badge su-badge--submitted"><i class="fa-solid fa-circle-check me-1"></i>Submitted</span>
                        @elseif($state === 'overdue')
                            <span class="su-badge su-badge--overdue"><i class="fa-solid fa-triangle-exclamation me-1"></i>Overdue</span>
                        @else
                            <span class="su-badge su-badge--pending"><i class="fa-solid fa-hourglass-half me-1"></i>Pending</span>
                        @endif
                    </div>
                </div>

                {{-- title + class --}}
                <p class="su-card-title">{{ $a->title }}</p>
                <p class="su-card-class"><i class="fa-solid fa-video me-1"></i>{{ $a->onlineClass?->title ?? '—' }}</p>

                @if($a->description)
                    <p class="su-card-desc">{{ Str::limit($a->description, 70) }}</p>
                @endif

                {{-- due date --}}
                <div class="su-card-due {{ $isOverdue ? 'overdue' : '' }}">
                    <i class="fa-solid fa-calendar me-1"></i>
                    Due: <strong>{{ \Carbon\Carbon::parse($a->due_date)->format('d M Y, h:i A') }}</strong>
                </div>

                {{-- grade badge --}}
                @if($isGraded)
                <div class="su-grade-box">
                    <span class="su-grade-num">{{ $submission->grade }}</span>
                    <span class="su-grade-of">/ {{ $a->total_points }} pts</span>
                </div>
                @if($submission->teacher_comment)
                    <p class="su-feedback"><i class="fa-solid fa-comment me-1"></i>{{ Str::limit($submission->teacher_comment, 80) }}</p>
                @endif
                @endif

                {{-- actions --}}
                <div class="su-card-actions">
                    @if($a->file_path)
                        <a href="{{ asset('storage/'.$a->file_path) }}" target="_blank" class="su-btn su-btn-outline">
                            <i class="fa-solid fa-download me-1"></i>Instructions
                        </a>
                    @endif

                    @if($state === 'pending')
                        <button class="su-btn su-btn-primary" data-bs-toggle="modal" data-bs-target="#submitModal{{ $a->id }}">
                            <i class="fa-solid fa-cloud-arrow-up me-1"></i>Submit Work
                        </button>
                    @elseif($state === 'submitted')
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="su-btn su-btn-outline">
                            <i class="fa-solid fa-eye me-1"></i>View My Work
                        </a>
                        <span class="su-waiting-lbl"><i class="fa-solid fa-clock me-1"></i>Awaiting grade…</span>
                    @elseif($state === 'late')
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="su-btn su-btn-outline">
                            <i class="fa-solid fa-eye me-1"></i>View My Work
                        </a>
                        <span class="su-waiting-lbl"><i class="fa-solid fa-clock me-1"></i>Awaiting grade…</span>
                    @elseif($state === 'graded')
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="su-btn su-btn-outline">
                            <i class="fa-solid fa-eye me-1"></i>View My Work
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══ SUBMIT MODALS ══ --}}
@foreach($assignments as $a)
@php $canSubmit = $a->submissions->isEmpty() && now()->lte($a->due_date); @endphp
@if($canSubmit)
<div class="modal fade" id="submitModal{{ $a->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content su-modal">
            <div class="su-modal-hdr">
                <div>
                    <h5 class="su-modal-title"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Submit Assignment</h5>
                    <p class="su-modal-sub">{{ $a->title }}</p>
                </div>
                <button type="button" class="su-modal-close" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('student.submissions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="assignment_id" value="{{ $a->id }}">
                <div class="su-modal-body">
                    <div class="su-modal-info">
                        <i class="fa-solid fa-calendar-day me-2 text-primary"></i>
                        Due <strong>{{ \Carbon\Carbon::parse($a->due_date)->format('d M Y, h:i A') }}</strong>
                        &nbsp;·&nbsp; {{ $a->total_points }} pts
                    </div>
                    <div class="su-field">
                        <label class="su-label">Upload Your Work <span class="su-req">*</span></label>
                        <input type="file" name="submission_file" class="su-input" accept=".pdf,.doc,.docx,.zip" required>
                        <p class="su-file-hint">Accepted: PDF, DOC, DOCX, ZIP</p>
                    </div>
                    <div class="su-field">
                        <label class="su-label">Note to Teacher <span class="su-opt">optional</span></label>
                        <textarea name="student_note" class="su-input" rows="2" placeholder="Any notes about your submission…"></textarea>
                    </div>
                </div>
                <div class="su-modal-foot">
                    <button type="button" class="su-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="su-btn-submit"><i class="fa-solid fa-paper-plane me-2"></i>Submit Now</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<style>
.su-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

.su-header { margin-bottom:1.25rem; }
.su-title    { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.su-subtitle { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.su-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
.su-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.su-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.su-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.su-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Grid */
.su-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }

/* Card */
.su-card { background:#fff; border-radius:14px; border:1.5px solid #F1F5F9; box-shadow:0 1px 6px rgba(0,0,0,.05); overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .2s; }
.su-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.09); }

.su-strip { height:4px; }
.su-strip--pending   { background:linear-gradient(90deg,#F59E0B,#FCD34D); }
.su-strip--submitted { background:linear-gradient(90deg,#4F46E5,#7C3AED); }
.su-strip--graded    { background:linear-gradient(90deg,#10B981,#059669); }
.su-strip--overdue   { background:linear-gradient(90deg,#EF4444,#DC2626); }
.su-strip--late      { background:linear-gradient(90deg,#F97316,#EA580C); }

.su-card-body { padding:1.1rem 1.15rem; display:flex; flex-direction:column; gap:.55rem; flex:1; }

.su-card-top { display:flex; align-items:flex-start; justify-content:space-between; }
.su-card-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
.su-card-icon--pending   { background:#FEF3C7; color:#D97706; }
.su-card-icon--submitted { background:#EEF2FF; color:#4F46E5; }
.su-card-icon--graded    { background:#D1FAE5; color:#059669; }
.su-card-icon--overdue   { background:#FEE2E2; color:#DC2626; }
.su-card-icon--late      { background:#FFEDD5; color:#EA580C; }

.su-card-status { display:flex; align-items:center; gap:.35rem; flex-wrap:wrap; }

.su-badge { display:inline-flex; align-items:center; padding:.18rem .6rem; border-radius:50px; font-size:.7rem; font-weight:700; }
.su-badge--pending   { background:#FEF3C7; color:#92400E; }
.su-badge--submitted { background:#EEF2FF; color:#3730A3; }
.su-badge--graded    { background:#D1FAE5; color:#065F46; }
.su-badge--overdue   { background:#FEE2E2; color:#DC2626; }
.su-badge--late      { background:#FFEDD5; color:#9A3412; }

.su-card-title { font-size:.92rem; font-weight:700; color:#1E293B; margin:0; }
.su-card-class { font-size:.73rem; color:#64748B; margin:0; }
.su-card-desc  { font-size:.75rem; color:#94A3B8; margin:0; }

.su-card-due { font-size:.75rem; color:#64748B; }
.su-card-due.overdue { color:#DC2626; font-weight:600; }

.su-grade-box { display:flex; align-items:baseline; gap:.3rem; margin:.1rem 0; }
.su-grade-num { font-size:1.6rem; font-weight:800; color:#059669; line-height:1; }
.su-grade-of  { font-size:.8rem; color:#94A3B8; font-weight:600; }
.su-feedback  { font-size:.73rem; color:#64748B; background:#F8FAFF; border-radius:8px; padding:.4rem .6rem; margin:0; border-left:2.5px solid #C7D2FE; }

.su-card-actions { display:flex; flex-wrap:wrap; gap:.45rem; margin-top:auto; padding-top:.4rem; }
.su-btn { display:inline-flex; align-items:center; padding:.38rem .8rem; border-radius:8px; font-size:.775rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all .15s; border:none; }
.su-btn-primary { background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff; box-shadow:0 3px 10px rgba(79,70,229,.2); }
.su-btn-primary:hover { transform:translateY(-1px); }
.su-btn-outline { background:#F1F5F9; color:#475569; }
.su-btn-outline:hover { background:#4F46E5; color:#fff; }
.su-waiting-lbl { font-size:.72rem; color:#94A3B8; display:inline-flex; align-items:center; }

/* Empty */
.su-empty-wrap { text-align:center; padding:4rem 1rem; color:#CBD5E1; }
.su-empty-wrap i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
.su-empty-wrap p { margin:0; font-size:.9rem; }

/* Modal */
.su-modal { border:none; border-radius:16px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.12); }
.su-modal-hdr { display:flex; align-items:flex-start; justify-content:space-between; padding:1.3rem 1.5rem 1rem; background:linear-gradient(135deg,#4F46E5,#7C3AED); }
.su-modal-title { font-size:.975rem; font-weight:700; color:#fff; margin:0; }
.su-modal-sub   { font-size:.78rem; color:rgba(255,255,255,.75); margin:.2rem 0 0; }
.su-modal-close {
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; width:30px; height:30px; border-radius:8px;
    display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:.8rem;
}
.su-modal-body { padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:.75rem; }
.su-modal-foot { padding:1rem 1.5rem 1.3rem; display:flex; justify-content:flex-end; gap:.6rem; border-top:1px solid #F1F5F9; }

.su-modal-info { background:#F8FAFF; border-radius:9px; padding:.6rem .85rem; font-size:.8rem; color:#475569; }

.su-field { display:flex; flex-direction:column; gap:.3rem; }
.su-label { font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.4px; }
.su-req { color:#EF4444; }
.su-opt { font-weight:400; text-transform:none; letter-spacing:0; color:#CBD5E1; }
.su-input {
    border:1.5px solid #E2E8F0; border-radius:9px; padding:.5rem .75rem;
    font-size:.845rem; color:#1E293B; background:#fff; outline:none;
    transition:border-color .15s; width:100%;
}
.su-input:focus { border-color:#7C3AED; box-shadow:0 0 0 3px rgba(124,58,237,.08); }
textarea.su-input { resize:vertical; }
.su-file-hint { font-size:.7rem; color:#94A3B8; margin:.2rem 0 0; }

.su-btn-cancel { padding:.5rem 1.1rem; border:1.5px solid #E2E8F0; border-radius:9px; background:#fff; color:#64748B; font-size:.855rem; font-weight:600; cursor:pointer; }
.su-btn-submit {
    padding:.5rem 1.25rem; border:none; border-radius:9px;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    font-size:.855rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center;
    box-shadow:0 4px 12px rgba(79,70,229,.25);
}

@media(max-width:991.98px) { .su-stats { grid-template-columns:repeat(2,1fr); } .su-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:767.98px) { .su-stats { grid-template-columns:repeat(2,1fr); } .su-grid { grid-template-columns:1fr; } }
</style>
@endsection
