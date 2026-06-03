@extends('applayouts.app')

@section('contents')
<div class="cd-page">

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
    <div class="cd-header">
        <div>
            <h5 class="cd-title">Virtual Classrooms</h5>
            <p class="cd-subtitle">All online sessions across every module and teacher</p>
        </div>
        <button class="cd-btn-add" data-bs-toggle="modal" data-bs-target="#scheduleModal">
            <i class="fa-solid fa-video me-2"></i>Schedule Class
        </button>
    </div>

    {{-- ── Stat pills ── --}}
    <div class="cd-stats">
        <div class="cd-stat">
            <span class="cd-stat-num">{{ $classes->total() }}</span>
            <span class="cd-stat-label">Total</span>
        </div>
        <div class="cd-stat cd-stat-live">
            <span class="cd-stat-num">{{ $counts['live'] ?? 0 }}</span>
            <span class="cd-stat-label">Live Now</span>
        </div>
        <div class="cd-stat cd-stat-upcoming">
            <span class="cd-stat-num">{{ $counts['upcoming'] ?? 0 }}</span>
            <span class="cd-stat-label">Upcoming</span>
        </div>
        <div class="cd-stat cd-stat-finished">
            <span class="cd-stat-num">{{ $counts['finished'] ?? 0 }}</span>
            <span class="cd-stat-label">Finished</span>
        </div>
        <div class="cd-stat cd-stat-cancelled">
            <span class="cd-stat-num">{{ $counts['cancelled'] ?? 0 }}</span>
            <span class="cd-stat-label">Cancelled</span>
        </div>
    </div>

    {{-- ── Filter tabs ── --}}
    <div class="cd-tabs">
        @foreach(['all' => 'All', 'live' => 'Live', 'upcoming' => 'Upcoming', 'finished' => 'Finished', 'cancelled' => 'Cancelled'] as $key => $label)
            <a class="cd-tab {{ $filter === $key ? 'active' : '' }}"
               href="{{ request()->fullUrlWithQuery(['filter' => $key, 'page' => 1]) }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- ── Cards grid ── --}}
    <div class="cd-grid">
        @forelse($classes as $class)
        @php
            $statusConfig = match($class->status) {
                'live'      => ['bg' => '#FEE2E2', 'color' => '#DC2626', 'icon' => 'fa-circle',     'label' => 'Live Now'],
                'upcoming'  => ['bg' => '#EEF2FF', 'color' => '#4F46E5', 'icon' => 'fa-clock',     'label' => 'Upcoming'],
                'finished'  => ['bg' => '#D1FAE5', 'color' => '#065F46', 'icon' => 'fa-check',     'label' => 'Finished'],
                'cancelled' => ['bg' => '#F1F5F9', 'color' => '#64748B', 'icon' => 'fa-ban',       'label' => 'Cancelled'],
                default     => ['bg' => '#FEF3C7', 'color' => '#92400E', 'icon' => 'fa-hourglass', 'label' => ucfirst($class->status)],
            };
        @endphp

        <div class="cd-card {{ $class->status === 'live' ? 'cd-card-live' : '' }}">

            {{-- Status + title --}}
            <div class="cd-card-top">
                <div class="cd-status-pill" style="background:{{ $statusConfig['bg'] }};color:{{ $statusConfig['color'] }}">
                    @if($class->status === 'live')
                        <span class="cd-live-dot"></span>
                    @else
                        <i class="fa-solid {{ $statusConfig['icon'] }} me-1" style="font-size:.6rem;"></i>
                    @endif
                    {{ $statusConfig['label'] }}
                </div>
                <form action="{{ route('admin.class.delete', $class->id) }}" method="POST" class="cd-delete-form"
                      onsubmit="return confirm('Delete this class?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="cd-delete-btn" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>

            <h6 class="cd-card-title">{{ $class->title }}</h6>

            {{-- Module + Teacher --}}
            <div class="cd-meta-row">
                <span class="cd-meta-pill cd-module-pill">
                    <i class="fa-solid fa-book-open me-1"></i>
                    {{ $class->module?->title ?? 'Unknown Module' }}
                </span>
                <span class="cd-meta-pill cd-teacher-pill">
                    <i class="fa-solid fa-chalkboard-user me-1"></i>
                    {{ $class->teacher?->name ?? 'Unknown Teacher' }}
                </span>
            </div>

            {{-- Date / Time / Duration --}}
            <div class="cd-info-row">
                <div class="cd-info-item">
                    <i class="fa-solid fa-calendar-day"></i>
                    {{ \Carbon\Carbon::parse($class->class_date)->format('M d, Y') }}
                </div>
                <div class="cd-info-item">
                    <i class="fa-solid fa-clock"></i>
                    {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                    @if($class->duration)
                        &nbsp;· {{ $class->duration }} min
                    @endif
                </div>
            </div>

            {{-- Meeting link --}}
            @if($class->meeting_link)
            <div class="cd-link-row">
                <a href="{{ $class->meeting_link }}" target="_blank" class="cd-link">
                    <i class="fa-solid fa-video me-1"></i>
                    {{ Str::limit($class->meeting_link, 40) }}
                </a>
                <button class="cd-copy-btn" onclick="navigator.clipboard.writeText('{{ $class->meeting_link }}');this.innerHTML='<i class=\'fa-solid fa-check\'></i>'; setTimeout(()=>this.innerHTML='<i class=\'fa-solid fa-clipboard\'></i>',1500)">
                    <i class="fa-solid fa-clipboard"></i>
                </button>
            </div>
            @endif

        </div>
        @empty
        <div class="cd-empty">
            <i class="fa-solid fa-video-slash"></i>
            <p>No classes found{{ $filter !== 'all' ? ' for filter "' . $filter . '"' : '' }}.</p>
        </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($classes->hasPages())
    <div class="cd-pagination">{{ $classes->links('pagination::bootstrap-5') }}</div>
    @endif

</div>

{{-- ═══════════════════════════════════════════
     MODAL — Schedule Class
═══════════════════════════════════════════ --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
        <div class="modal-content cd-modal">

            <div class="cd-modal-header">
                <div>
                    <h5 class="cd-modal-title"><i class="fa-solid fa-video me-2"></i>Schedule a Class</h5>
                    <p class="cd-modal-sub">Fill in the details and assign a teacher</p>
                </div>
                <button type="button" class="cd-modal-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.class.store') }}" method="POST">
                @csrf
                <div class="cd-modal-body">

                    <div class="cd-field">
                        <label class="cd-label">Session Title</label>
                        <input type="text" name="title" class="cd-input" placeholder="e.g. Introduction to AI" required value="{{ old('title') }}">
                    </div>

                    <div class="cd-row-2">
                        <div class="cd-field">
                            <label class="cd-label">Module</label>
                            <select name="module_id" class="cd-input" required>
                                <option value="">Select module…</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" {{ old('module_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cd-field">
                            <label class="cd-label">Teacher</label>
                            <select name="teacher_id" class="cd-input" required>
                                <option value="">Select teacher…</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="cd-row-2">
                        <div class="cd-field">
                            <label class="cd-label">Date</label>
                            <input type="date" name="class_date" class="cd-input" required value="{{ old('class_date') }}">
                        </div>
                        <div class="cd-field">
                            <label class="cd-label">Start Time</label>
                            <input type="time" name="start_time" class="cd-input" required value="{{ old('start_time') }}">
                        </div>
                    </div>

                    <div class="cd-row-2">
                        <div class="cd-field">
                            <label class="cd-label">Duration (minutes)</label>
                            <input type="number" name="duration" class="cd-input" placeholder="60" min="1" value="{{ old('duration') }}">
                        </div>
                        <div class="cd-field">
                            <label class="cd-label">Meeting ID <span class="cd-optional">optional</span></label>
                            <input type="text" name="meeting_id" class="cd-input" placeholder="Zoom ID…" value="{{ old('meeting_id') }}">
                        </div>
                    </div>

                    <div class="cd-field">
                        <label class="cd-label">Meeting Link (Zoom / Meet / Teams)</label>
                        <input type="url" name="meeting_link" class="cd-input" placeholder="https://zoom.us/j/…" required value="{{ old('meeting_link') }}">
                    </div>

                    <div class="cd-field">
                        <label class="cd-label">Meeting Password <span class="cd-optional">optional</span></label>
                        <input type="text" name="meeting_password" class="cd-input" placeholder="password…" value="{{ old('meeting_password') }}">
                    </div>

                    <div class="cd-field">
                        <label class="cd-label">Description <span class="cd-optional">optional</span></label>
                        <textarea name="description" class="cd-input" rows="2" placeholder="Brief session overview…">{{ old('description') }}</textarea>
                    </div>

                </div>

                <div class="cd-modal-footer">
                    <button type="button" class="cd-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="cd-btn-save">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Schedule Class
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* ── Page ── */
.cd-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

/* ── Header ── */
.cd-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem; }
.cd-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.cd-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }
.cd-btn-add {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,#4F46E5,#7C3AED); color: #fff;
    border: none; border-radius: 10px; padding: .55rem 1.1rem;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 12px rgba(79,70,229,.25); transition: all .2s;
}
.cd-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,70,229,.36); }

/* ── Stats ── */
.cd-stats { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.cd-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 12px;
    padding: .65rem 1.1rem; display: flex; align-items: center; gap: .6rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.cd-stat-num   { font-size: 1.2rem; font-weight: 800; color: #1E293B; }
.cd-stat-label { font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; }
.cd-stat-live      { border-color: #FCA5A5; } .cd-stat-live .cd-stat-num      { color: #DC2626; }
.cd-stat-upcoming  { border-color: #C7D2FE; } .cd-stat-upcoming .cd-stat-num  { color: #4F46E5; }
.cd-stat-finished  { border-color: #A7F3D0; } .cd-stat-finished .cd-stat-num  { color: #065F46; }
.cd-stat-cancelled { border-color: #E2E8F0; } .cd-stat-cancelled .cd-stat-num { color: #94A3B8; }

/* ── Tabs ── */
.cd-tabs { display: flex; gap: 4px; background: #EEF2FF; padding: 4px; border-radius: 10px; width: fit-content; margin-bottom: 1.25rem; }
.cd-tab {
    padding: .32rem .85rem; border-radius: 7px;
    font-size: .8rem; font-weight: 600; color: #64748B;
    text-decoration: none; transition: all .15s;
}
.cd-tab.active { background: #fff; color: #4F46E5; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.cd-tab:hover:not(.active) { background: rgba(255,255,255,.6); }

/* ── Grid ── */
.cd-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1rem; }

/* ── Card ── */
.cd-card {
    background: #fff; border-radius: 14px;
    border: 1.5px solid #F1F5F9; padding: 1.1rem 1.2rem;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    display: flex; flex-direction: column; gap: .7rem;
    transition: box-shadow .2s, transform .2s;
}
.cd-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
.cd-card-live { border-color: #FCA5A5; border-left: 4px solid #DC2626; }

.cd-card-top { display: flex; align-items: center; justify-content: space-between; }
.cd-status-pill {
    display: inline-flex; align-items: center;
    padding: .22rem .7rem; border-radius: 50px;
    font-size: .72rem; font-weight: 700;
}
.cd-live-dot {
    width: 7px; height: 7px; border-radius: 50%; background: #DC2626;
    display: inline-block; margin-right: 5px;
    animation: livePulse 1.4s infinite;
}
@keyframes livePulse {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .5; transform: scale(1.3); }
}

.cd-delete-btn {
    background: #FEE2E2; color: #DC2626; border: none;
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; cursor: pointer; transition: all .15s;
}
.cd-delete-btn:hover { background: #DC2626; color: #fff; }

.cd-card-title { font-size: .9rem; font-weight: 700; color: #1E293B; margin: 0; line-height: 1.35; }

/* ── Meta pills ── */
.cd-meta-row { display: flex; flex-wrap: wrap; gap: 5px; }
.cd-meta-pill {
    display: inline-flex; align-items: center;
    padding: .18rem .65rem; border-radius: 50px; font-size: .72rem; font-weight: 600;
}
.cd-module-pill  { background: #EEF2FF; color: #4338CA; }
.cd-teacher-pill { background: #F0FDF4; color: #166534; }

/* ── Info row ── */
.cd-info-row { display: flex; flex-wrap: wrap; gap: .6rem; }
.cd-info-item { display: flex; align-items: center; gap: 6px; font-size: .78rem; color: #64748B; }
.cd-info-item i { color: #94A3B8; font-size: .75rem; }

/* ── Meeting link ── */
.cd-link-row {
    display: flex; align-items: center; gap: 6px;
    background: #F8FAFF; border: 1px dashed #C7D2FE;
    border-radius: 8px; padding: .45rem .7rem;
}
.cd-link {
    font-size: .75rem; color: #4F46E5; text-decoration: none; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.cd-link:hover { text-decoration: underline; }
.cd-copy-btn {
    background: none; border: none; color: #94A3B8; cursor: pointer; font-size: .75rem; padding: 0; flex-shrink: 0; transition: color .15s;
}
.cd-copy-btn:hover { color: #4F46E5; }

/* ── Empty ── */
.cd-empty { grid-column: 1/-1; text-align: center; padding: 4rem 1rem; color: #CBD5E1; }
.cd-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
.cd-empty p { font-size: .9rem; margin: 0; }

/* ── Pagination ── */
.cd-pagination { margin-top: 1.5rem; display: flex; justify-content: center; }
.cd-pagination .page-link { color: #4F46E5; border-color: #E2E8F0; font-size: .82rem; }
.cd-pagination .page-item.active .page-link { background: #4F46E5; border-color: #4F46E5; }

/* ── Modal ── */
.cd-modal { border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.12); }
.cd-modal-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.3rem 1.5rem 1rem;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
}
.cd-modal-title { font-size: .975rem; font-weight: 700; color: #fff; margin: 0; }
.cd-modal-sub   { font-size: .78rem; color: rgba(255,255,255,.75); margin: .2rem 0 0; }
.cd-modal-close {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.25);
    color: #fff; width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .8rem; transition: background .15s;
}
.cd-modal-close:hover { background: rgba(255,255,255,.28); }

.cd-modal-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .75rem; max-height: 68vh; overflow-y: auto; }
.cd-modal-footer { padding: 1rem 1.5rem 1.3rem; display: flex; justify-content: flex-end; gap: .6rem; border-top: 1px solid #F1F5F9; }

.cd-field { display: flex; flex-direction: column; gap: .3rem; }
.cd-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.cd-label { font-size: .75rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: .4px; }
.cd-optional { font-weight: 400; text-transform: none; letter-spacing: 0; color: #CBD5E1; }
.cd-input {
    border: 1.5px solid #E2E8F0; border-radius: 9px; padding: .5rem .75rem;
    font-size: .845rem; color: #1E293B; background: #fff; outline: none;
    transition: border-color .15s; width: 100%;
}
.cd-input:focus { border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,.08); }
textarea.cd-input { resize: vertical; }

.cd-btn-cancel {
    padding: .5rem 1.1rem; border: 1.5px solid #E2E8F0; border-radius: 9px;
    background: #fff; color: #64748B; font-size: .855rem; font-weight: 600; cursor: pointer;
}
.cd-btn-save {
    padding: .5rem 1.25rem; border: none; border-radius: 9px;
    background: linear-gradient(135deg,#4F46E5,#7C3AED);
    color: #fff; font-size: .855rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center;
    box-shadow: 0 4px 12px rgba(79,70,229,.25);
}
.cd-btn-save:hover { box-shadow: 0 6px 18px rgba(79,70,229,.36); }

@media(max-width:767.98px) {
    .cd-grid { grid-template-columns: 1fr; }
    .cd-row-2 { grid-template-columns: 1fr; }
    .cd-stats { gap: .5rem; }
}
</style>

@endsection
