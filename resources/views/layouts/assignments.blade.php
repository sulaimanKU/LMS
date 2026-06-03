@extends('applayouts.app')
@section('contents')
<div class="as-page">

    {{-- ── Header ── --}}
    <div class="as-header">
        <div>
            <h5 class="as-title">Assignments Overview</h5>
            <p class="as-subtitle">All assignments posted across every module and teacher</p>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="as-stats">
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-file-lines"></i></div>
            <div><p class="as-stat-num">{{ $totalAssignments }}</p><p class="as-stat-lbl">Total Assignments</p></div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#DCFCE7;color:#16A34A;"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div><p class="as-stat-num">{{ $totalSubmissions }}</p><p class="as-stat-lbl">Total Submissions</p></div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><p class="as-stat-num">{{ $pendingGrading }}</p><p class="as-stat-lbl">Pending Review</p></div>
        </div>
        <div class="as-stat">
            <div class="as-stat-icon" style="background:#D1FAE5;color:#065F46;"><i class="fa-solid fa-circle-check"></i></div>
            <div><p class="as-stat-num">{{ $graded }}</p><p class="as-stat-lbl">Graded</p></div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="as-card">
        <div class="as-card-head">
            <span><i class="fa-solid fa-list-check me-2 text-primary"></i>All Assignments</span>
            <span class="as-count-badge">{{ $assignments->total() }} total</span>
        </div>

        <div class="table-responsive">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Assignment</th>
                        <th>Module</th>
                        <th>Teacher</th>
                        <th>Due Date</th>
                        <th>Points</th>
                        <th>Submissions</th>
                        <th>Pending / Graded</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $a)
                    @php $overdue = now()->gt($a->due_date); @endphp
                    <tr>
                        <td class="as-td-num">{{ $a->id }}</td>
                        <td>
                            <p class="as-assign-title">{{ $a->title }}</p>
                            @if($a->description)
                                <p class="as-assign-desc">{{ Str::limit($a->description, 60) }}</p>
                            @endif
                            @if($a->file_path)
                                <a href="{{ asset('storage/'.$a->file_path) }}" target="_blank" class="as-file-link">
                                    <i class="fa-solid fa-paperclip me-1"></i>Attachment
                                </a>
                            @endif
                        </td>
                        <td>
                            <span class="as-module-pill">{{ $a->onlineClass?->module?->title ?? '—' }}</span>
                            <p class="as-class-name">{{ $a->onlineClass?->title ?? 'Class deleted' }}</p>
                        </td>
                        <td class="as-teacher">{{ $a->teacher?->name ?? '—' }}</td>
                        <td>
                            <span class="{{ $overdue ? 'as-date-over' : 'as-date-ok' }}">
                                <i class="fa-solid fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($a->due_date)->format('d M Y') }}
                            </span>
                            @if($overdue)<span class="as-overdue-tag">Overdue</span>@endif
                        </td>
                        <td class="as-points">{{ $a->total_points }} pts</td>
                        <td>
                            <span class="as-sub-count {{ $a->submissions_count > 0 ? 'has-subs' : '' }}">
                                <i class="fa-solid fa-file-import me-1"></i>{{ $a->submissions_count }}
                            </span>
                        </td>
                        <td>
                            <div class="as-grade-row">
                                @if($a->pending_count > 0)
                                    <span class="as-pill-pending">{{ $a->pending_count }} pending</span>
                                @endif
                                @if($a->graded_count > 0)
                                    <span class="as-pill-graded">{{ $a->graded_count }} graded</span>
                                @endif
                                @if($a->submissions_count === 0)
                                    <span class="as-pill-none">No submissions</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="as-empty">
                            <i class="fa-solid fa-file-circle-xmark"></i>
                            <p>No assignments have been posted yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
        <div class="as-pagination">{{ $assignments->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>

<style>
.as-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; }

.as-header { margin-bottom: 1.25rem; }
.as-title    { font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0; }
.as-subtitle { font-size: .8rem; color: #94A3B8; margin: .1rem 0 0; }

/* Stats */
.as-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: .75rem; margin-bottom: 1.25rem; }
.as-stat {
    background: #fff; border: 1.5px solid #F1F5F9; border-radius: 14px;
    padding: .9rem 1.1rem; display: flex; align-items: center; gap: .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.as-stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; }
.as-stat-num { font-size: 1.3rem; font-weight: 800; color: #1E293B; margin: 0; line-height: 1; }
.as-stat-lbl { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; margin: .15rem 0 0; }

/* Card */
.as-card { background: #fff; border-radius: 14px; border: 1.5px solid #F1F5F9; box-shadow: 0 1px 6px rgba(0,0,0,.05); overflow: hidden; }
.as-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: .9rem 1.25rem; border-bottom: 1px solid #F1F5F9;
    font-size: .85rem; font-weight: 700; color: #1E293B;
}
.as-count-badge { background: #EEF2FF; color: #4F46E5; padding: .2rem .7rem; border-radius: 50px; font-size: .72rem; font-weight: 700; }

/* Table */
.as-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.as-table thead tr { background: #F8FAFF; border-bottom: 1.5px solid #F1F5F9; }
.as-table th { padding: .7rem 1rem; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94A3B8; white-space: nowrap; }
.as-table td { padding: .8rem 1rem; border-bottom: 1px solid #F8FAFF; vertical-align: middle; }
.as-table tbody tr:hover { background: #FAFBFF; }

.as-td-num { color: #94A3B8; font-weight: 600; font-size: .75rem; }
.as-assign-title { font-weight: 700; color: #1E293B; margin: 0; }
.as-assign-desc  { font-size: .73rem; color: #94A3B8; margin: .1rem 0 .2rem; }
.as-file-link    { font-size: .72rem; color: #4F46E5; text-decoration: none; }
.as-file-link:hover { text-decoration: underline; }

.as-module-pill { background: #EEF2FF; color: #4338CA; padding: .15rem .55rem; border-radius: 50px; font-size: .7rem; font-weight: 600; display: inline-block; }
.as-class-name  { font-size: .72rem; color: #94A3B8; margin: .2rem 0 0; }

.as-teacher { font-weight: 600; color: #475569; }
.as-points  { font-weight: 700; color: #4F46E5; white-space: nowrap; }

.as-date-ok   { color: #16A34A; font-weight: 600; white-space: nowrap; }
.as-date-over { color: #DC2626; font-weight: 600; white-space: nowrap; }
.as-overdue-tag { display: inline-block; background: #FEE2E2; color: #DC2626; padding: .1rem .45rem; border-radius: 50px; font-size: .65rem; font-weight: 700; margin-left: 4px; }

.as-sub-count { font-weight: 700; color: #94A3B8; }
.as-sub-count.has-subs { color: #4F46E5; }

.as-grade-row { display: flex; flex-wrap: wrap; gap: 4px; }
.as-pill-pending { background: #FEF3C7; color: #92400E; padding: .18rem .55rem; border-radius: 50px; font-size: .7rem; font-weight: 700; white-space: nowrap; }
.as-pill-graded  { background: #D1FAE5; color: #065F46; padding: .18rem .55rem; border-radius: 50px; font-size: .7rem; font-weight: 700; white-space: nowrap; }
.as-pill-none    { color: #CBD5E1; font-size: .73rem; }

.as-empty { text-align: center; padding: 3rem 1rem; color: #CBD5E1; }
.as-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; }
.as-empty p { margin: 0; font-size: .85rem; }

.as-pagination { padding: 1rem 1.25rem; display: flex; justify-content: center; border-top: 1px solid #F1F5F9; }
.as-pagination .page-link { color: #4F46E5; border-color: #E2E8F0; font-size: .82rem; }
.as-pagination .page-item.active .page-link { background: #4F46E5; border-color: #4F46E5; }

@media(max-width:991.98px) { .as-stats { grid-template-columns: repeat(2,1fr); } }
@media(max-width:767.98px) { .as-stats { grid-template-columns: repeat(2,1fr); } }
</style>
@endsection
