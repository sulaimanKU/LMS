@extends('applayouts.app')
@section('contents')
<div class="lm-page">

    {{-- ── Header ── --}}
    <div class="lm-header">
        <div>
            <h5 class="lm-title">Learning Materials</h5>
            <p class="lm-subtitle">Resources and files uploaded by your instructors</p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    @php
        $totalFiles   = $courses->sum(fn($c) => $c->lessons->sum(fn($l) => $l->resources->count()));
        $totalLessons = $courses->sum(fn($c) => $c->lessons->count());
        $courseCount  = $courses->count();
    @endphp
    <div class="lm-stats">
        <div class="lm-stat">
            <div class="lm-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-book"></i></div>
            <div><p class="lm-stat-num">{{ $courseCount }}</p><p class="lm-stat-lbl">Modules</p></div>
        </div>
        <div class="lm-stat">
            <div class="lm-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-list-check"></i></div>
            <div><p class="lm-stat-num">{{ $totalLessons }}</p><p class="lm-stat-lbl">Lessons</p></div>
        </div>
        <div class="lm-stat">
            <div class="lm-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-file-lines"></i></div>
            <div><p class="lm-stat-num">{{ $totalFiles }}</p><p class="lm-stat-lbl">Files Available</p></div>
        </div>
        <div class="lm-stat">
            <div class="lm-stat-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <p class="lm-stat-num">{{ $courses->sum(fn($c) => $c->teacher->count()) }}</p>
                <p class="lm-stat-lbl">Instructors</p>
            </div>
        </div>
    </div>

    {{-- ── Courses ── --}}
    @forelse($courses as $course)
    @php
        $courseFiles   = $course->lessons->sum(fn($l) => $l->resources->count());
        $instructorName = $course->teacher->first()?->name ?? 'Not Assigned';
    @endphp
    <div class="lm-course-block">

        {{-- Course Header --}}
        <div class="lm-course-hdr">
            <div class="lm-course-icon"><i class="fa-solid fa-book-open"></i></div>
            <div class="lm-course-meta">
                <p class="lm-course-name">{{ $course->title }}</p>
                <div class="lm-course-tags">
                    @if($course->category)
                        <span class="lm-tag lm-tag-cat">{{ $course->category }}</span>
                    @endif
                    <span class="lm-tag"><i class="fa-solid fa-user me-1"></i>{{ $instructorName }}</span>
                    <span class="lm-tag"><i class="fa-solid fa-list-check me-1"></i>{{ $course->lessons->count() }} lessons</span>
                    <span class="lm-tag"><i class="fa-solid fa-file me-1"></i>{{ $courseFiles }} files</span>
                    <span class="lm-tag" style="background:#{{ $course->pivot->status === 'completed' ? 'D1FAE5' : 'EEF2FF' }};color:#{{ $course->pivot->status === 'completed' ? '065F46' : '4338CA' }};">
                        {{ ucfirst($course->pivot->status ?? 'enrolled') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Lessons & Resources --}}
        @if($course->lessons->isEmpty())
        <div class="lm-no-lessons">
            <i class="fa-solid fa-folder-open"></i>
            <span>No lessons added yet for this module.</span>
        </div>
        @else
        @foreach($course->lessons->sortBy('order_number') as $lesson)
        <div class="lm-lesson-block">
            <div class="lm-lesson-hdr">
                <div class="lm-lesson-num">{{ $lesson->order_number ?? $loop->iteration }}</div>
                <div class="lm-lesson-info">
                    <p class="lm-lesson-title">{{ $lesson->title }}</p>
                    @if($lesson->short_text)
                        <p class="lm-lesson-desc">{{ Str::limit($lesson->short_text, 80) }}</p>
                    @endif
                </div>
                <span class="lm-files-count">{{ $lesson->resources->count() }} file{{ $lesson->resources->count() != 1 ? 's' : '' }}</span>
            </div>

            @if($lesson->resources->isEmpty())
            <div class="lm-no-files">No files uploaded for this lesson yet.</div>
            @else
            <div class="lm-files-grid">
                @foreach($lesson->resources as $resource)
                @php
                    $ext  = strtolower(pathinfo($resource->file_path, PATHINFO_EXTENSION));
                    $meta = match($ext) {
                        'pdf'           => ['icon'=>'fa-file-pdf',    'bg'=>'#FEE2E2', 'color'=>'#DC2626', 'label'=>'PDF'],
                        'doc','docx'    => ['icon'=>'fa-file-word',   'bg'=>'#DBEAFE', 'color'=>'#2563EB', 'label'=>strtoupper($ext)],
                        'ppt','pptx'   => ['icon'=>'fa-file-powerpoint','bg'=>'#FFEDD5','color'=>'#EA580C','label'=>strtoupper($ext)],
                        'zip','rar'     => ['icon'=>'fa-file-zipper', 'bg'=>'#FEF3C7', 'color'=>'#D97706', 'label'=>strtoupper($ext)],
                        'jpg','jpeg','png','gif' => ['icon'=>'fa-file-image','bg'=>'#D1FAE5','color'=>'#059669','label'=>strtoupper($ext)],
                        default         => ['icon'=>'fa-file-lines',  'bg'=>'#F1F5F9', 'color'=>'#64748B', 'label'=>strtoupper($ext) ?: 'FILE'],
                    };
                @endphp
                <div class="lm-file-row">
                    <div class="lm-file-icon" style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }};">
                        <i class="fa-solid {{ $meta['icon'] }}"></i>
                    </div>
                    <div class="lm-file-info">
                        <p class="lm-file-name">{{ $resource->title }}</p>
                        <p class="lm-file-meta">{{ $meta['label'] }}</p>
                    </div>
                    <div class="lm-file-actions">
                        <a href="{{ asset('storage/'.$resource->file_path) }}" target="_blank" class="lm-file-btn lm-btn-view" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ asset('storage/'.$resource->file_path) }}" download class="lm-file-btn lm-btn-dl" title="Download">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
        @endif

    </div>
    @empty
    <div class="lm-empty">
        <i class="fa-solid fa-graduation-cap"></i>
        <p>You are not enrolled in any modules yet.</p>
    </div>
    @endforelse

</div>

<style>
.lm-page { padding:1.5rem; background:#F8FAFF; min-height:100%; }

.lm-header { margin-bottom:1.25rem; }
.lm-title    { font-size:1.2rem; font-weight:800; color:#1E293B; margin:0; }
.lm-subtitle { font-size:.8rem; color:#94A3B8; margin:.1rem 0 0; }

/* Stats */
.lm-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.5rem; }
.lm-stat { background:#fff; border:1.5px solid #F1F5F9; border-radius:14px; padding:.9rem 1.1rem; display:flex; align-items:center; gap:.85rem; box-shadow:0 1px 4px rgba(0,0,0,.04); }
.lm-stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
.lm-stat-num { font-size:1.3rem; font-weight:800; color:#1E293B; margin:0; line-height:1; }
.lm-stat-lbl { font-size:.68rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#94A3B8; margin:.15rem 0 0; }

/* Course block */
.lm-course-block {
    background:#fff; border:1.5px solid #F1F5F9; border-radius:16px;
    box-shadow:0 1px 6px rgba(0,0,0,.05); margin-bottom:1.25rem; overflow:hidden;
}

.lm-course-hdr {
    display:flex; align-items:flex-start; gap:.9rem;
    padding:1.1rem 1.25rem; border-bottom:1px solid #F1F5F9;
    background:linear-gradient(135deg,#FAFBFF,#F5F3FF);
}
.lm-course-icon {
    width:40px; height:40px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#4F46E5,#7C3AED); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.lm-course-meta { flex:1; min-width:0; }
.lm-course-name { font-size:.98rem; font-weight:800; color:#1E293B; margin:0 0 .45rem; }
.lm-course-tags { display:flex; flex-wrap:wrap; gap:.35rem; }
.lm-tag { background:#F1F5F9; color:#64748B; padding:.18rem .55rem; border-radius:50px; font-size:.68rem; font-weight:600; display:inline-flex; align-items:center; }
.lm-tag-cat { background:#EEF2FF; color:#4338CA; }

/* Lesson block */
.lm-lesson-block { border-bottom:1px solid #F8FAFF; }
.lm-lesson-block:last-child { border-bottom:none; }

.lm-lesson-hdr {
    display:flex; align-items:flex-start; gap:.8rem;
    padding:.85rem 1.25rem; background:#FAFBFF; border-bottom:1px solid #F1F5F9;
}
.lm-lesson-num {
    width:26px; height:26px; border-radius:7px; flex-shrink:0;
    background:#4F46E5; color:#fff;
    font-size:.72rem; font-weight:800; display:flex; align-items:center; justify-content:center;
}
.lm-lesson-info { flex:1; min-width:0; }
.lm-lesson-title { font-size:.85rem; font-weight:700; color:#1E293B; margin:0; }
.lm-lesson-desc  { font-size:.73rem; color:#94A3B8; margin:.1rem 0 0; }
.lm-files-count  { font-size:.68rem; font-weight:700; color:#4F46E5; background:#EEF2FF; padding:.15rem .5rem; border-radius:50px; white-space:nowrap; flex-shrink:0; }

/* Files grid */
.lm-files-grid { padding:.6rem 1.25rem; display:flex; flex-direction:column; gap:.4rem; }
.lm-file-row { display:flex; align-items:center; gap:.75rem; padding:.55rem .7rem; border-radius:9px; background:#F8FAFF; border:1px solid #F1F5F9; transition:border-color .15s; }
.lm-file-row:hover { border-color:#C7D2FE; }

.lm-file-icon { width:34px; height:34px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.85rem; }
.lm-file-info { flex:1; min-width:0; }
.lm-file-name { font-size:.8rem; font-weight:700; color:#1E293B; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.lm-file-meta { font-size:.65rem; color:#94A3B8; font-weight:600; margin:.1rem 0 0; text-transform:uppercase; }

.lm-file-actions { display:flex; gap:.35rem; flex-shrink:0; }
.lm-file-btn {
    width:30px; height:30px; border-radius:7px; display:flex; align-items:center; justify-content:center;
    font-size:.75rem; text-decoration:none; transition:all .15s; border:1.5px solid #E2E8F0; color:#64748B; background:#fff;
}
.lm-btn-view:hover { background:#EEF2FF; color:#4F46E5; border-color:#C7D2FE; }
.lm-btn-dl:hover   { background:#4F46E5; color:#fff; border-color:#4F46E5; }

/* Empty states */
.lm-no-lessons { display:flex; align-items:center; gap:.6rem; padding:1.1rem 1.25rem; color:#CBD5E1; font-size:.8rem; }
.lm-no-files   { padding:.5rem 1.25rem .9rem; color:#CBD5E1; font-size:.75rem; font-style:italic; }

.lm-empty { text-align:center; padding:5rem 1rem; color:#CBD5E1; background:#fff; border:1.5px solid #F1F5F9; border-radius:16px; }
.lm-empty i { font-size:3rem; display:block; margin-bottom:.75rem; }
.lm-empty p { margin:0; font-size:.9rem; }

@media(max-width:991.98px) { .lm-stats { grid-template-columns:repeat(2,1fr); } }
@media(max-width:640px)    { .lm-stats { grid-template-columns:repeat(2,1fr); } }
</style>
@endsection
