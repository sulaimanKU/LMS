@extends('welcome')
@section('content')

<style>
.tr-page { max-width: 760px; margin: 0 auto; padding: 2.5rem 1rem; }

.tr-page-title { font-size: 1.35rem; font-weight: 800; color: #1E293B; margin: 0 0 .3rem; }
.tr-page-sub   { font-size: .85rem; color: #94A3B8; margin: 0 0 1.75rem; }

/* Registration card */
.tr-card {
    background: #fff; border-radius: 16px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    margin-bottom: 1.25rem; overflow: hidden;
}

/* Top bar */
.tr-card-top {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 1.4rem; flex-wrap: wrap; gap: .75rem;
    border-bottom: 1px solid #F1F5F9;
}
.tr-reg-id   { font-size: .78rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: .6px; margin: 0; }
.tr-reg-date { font-size: .75rem; color: #CBD5E1; margin: .15rem 0 0; }

/* Status badges */
.tr-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .3rem .85rem; border-radius: 50px;
    font-size: .75rem; font-weight: 700;
}
.tr-badge-approved { background: #D1FAE5; color: #065F46; }
.tr-badge-partial  { background: #DBEAFE; color: #1D4ED8; }
.tr-badge-pending  { background: #FEF3C7; color: #92400E; }

/* Summary row */
.tr-summary {
    display: flex; gap: 1.25rem; flex-wrap: wrap;
    padding: .9rem 1.4rem; background: #F8FAFF;
    border-bottom: 1px solid #F1F5F9;
}
.tr-sum-item { display: flex; align-items: center; gap: .5rem; }
.tr-sum-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.tr-sum-dot-ok  { background: #10B981; }
.tr-sum-dot-pnd { background: #F59E0B; }
.tr-sum-label   { font-size: .78rem; font-weight: 600; color: #475569; }
.tr-sum-count   { font-size: .78rem; font-weight: 800; color: #1E293B; }

/* Courses section */
.tr-section { padding: 1rem 1.4rem; border-bottom: 1px solid #F1F5F9; }
.tr-section-title {
    font-size: .7rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .7px; color: #94A3B8; margin: 0 0 .7rem;
}
.tr-course-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .55rem .8rem; border-radius: 9px; margin-bottom: .4rem;
}
.tr-course-row:last-child { margin-bottom: 0; }
.tr-course-row-ok  { background: #F0FDF4; border: 1px solid #BBF7D0; }
.tr-course-row-pnd { background: #FFFBEB; border: 1px solid #FDE68A; }
.tr-course-row-wait { background: #F8FAFF; border: 1px solid #E2E8F0; }
.tr-course-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: .75rem; flex-shrink: 0; }
.tr-icon-ok   { background: #D1FAE5; color: #059669; }
.tr-icon-pnd  { background: #FEF3C7; color: #D97706; }
.tr-icon-wait { background: #F1F5F9; color: #94A3B8; }
.tr-course-name  { font-size: .84rem; font-weight: 600; color: #1E293B; flex: 1; min-width: 0; }
.tr-course-status-tag {
    font-size: .67rem; font-weight: 800; padding: .15rem .55rem;
    border-radius: 50px; white-space: nowrap; flex-shrink: 0;
}
.tr-tag-ok   { background: #D1FAE5; color: #065F46; }
.tr-tag-pnd  { background: #FEF3C7; color: #92400E; }
.tr-tag-wait { background: #F1F5F9; color: #64748B; }

/* Slips section */
.tr-slip-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .5rem .8rem; border-radius: 9px; margin-bottom: .4rem;
    background: #FFFBEB; border: 1px solid #FDE68A;
}
.tr-slip-row:last-child { margin-bottom: 0; }
.tr-slip-num  { font-size: .78rem; font-weight: 700; color: #92400E; min-width: 60px; }
.tr-slip-date { font-size: .73rem; color: #92400E; flex: 1; }
.tr-slip-badge {
    font-size: .65rem; font-weight: 800; padding: .15rem .55rem;
    border-radius: 50px; white-space: nowrap;
}
.tr-slip-approved { background: #D1FAE5; color: #065F46; }
.tr-slip-pending  { background: #FEF3C7; color: #92400E; }
.tr-slip-view {
    font-size: .73rem; font-weight: 600; color: #4F46E5;
    text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
}
.tr-slip-view:hover { text-decoration: underline; }

/* Amount row */
.tr-amount-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .85rem 1.4rem; flex-wrap: wrap; gap: .5rem;
}
.tr-amount-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #94A3B8; }
.tr-amount-val   { font-size: 1rem; font-weight: 800; color: #1E293B; }

/* Alert banners */
.tr-alert-upload {
    margin: 0 1.4rem 1rem;
    padding: .75rem 1rem; border-radius: 10px;
    background: #FFFBEB; border: 1px solid #FCD34D;
    font-size: .82rem; color: #92400E;
    display: flex; align-items: center; gap: .6rem;
}
.tr-alert-upload a { color: #92400E; font-weight: 700; }

/* Empty */
.tr-empty { text-align: center; padding: 4rem 1rem; color: #CBD5E1; }
.tr-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }

/* New search link */
.tr-search-again { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: #64748B; }
.tr-search-again a { color: #4F46E5; font-weight: 600; text-decoration: none; }
</style>

<div class="tr-page">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 px-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="tr-page-title"><i class="bi bi-search me-2"></i>Registration Status</h3>
    <p class="tr-page-sub">Showing results for <strong>{{ $trackReg->first()->email }}</strong></p>

    @foreach($trackReg as $reg)
    @php
        $courseIds     = array_map('intval', $reg->selected_courses ?? []);
        // Convert enrolledIds to array if it's a collection or non-array
        $cleanEnrolled = is_array($enrolledIds) ? $enrolledIds : (is_object($enrolledIds) ? $enrolledIds->toArray() : []);
        $enrolledCount = count(array_intersect($cleanEnrolled, $courseIds));
        $pendingCount  = count($courseIds) - $enrolledCount;
        $hasSlip       = $reg->slips->isNotEmpty();
        $allDone       = $enrolledCount > 0 && $enrolledCount >= count($courseIds);
        $partial       = $enrolledCount > 0 && $pendingCount > 0;
    @endphp

    <div class="tr-card">

        {{-- Top bar --}}
        <div class="tr-card-top">
            <div>
                <p class="tr-reg-id">Registration #{{ $reg->id }}</p>
                <p class="tr-reg-date">Submitted {{ $reg->created_at->format('d M Y') }}</p>
            </div>
            @if($allDone)
                <span class="tr-badge tr-badge-approved"><i class="bi bi-check-circle-fill"></i> Fully Enrolled</span>
            @elseif($partial)
                <span class="tr-badge tr-badge-partial"><i class="bi bi-circle-half"></i> {{ $enrolledCount }} Approved · {{ $pendingCount }} Pending</span>
            @elseif($enrolledCount > 0)
                <span class="tr-badge tr-badge-approved"><i class="bi bi-check-circle-fill"></i> Approved</span>
            @else
                <span class="tr-badge tr-badge-pending"><i class="bi bi-hourglass-split"></i> Pending</span>
            @endif
        </div>

        {{-- Summary counts --}}
        @if(count($courseIds) > 0)
        <div class="tr-summary">
            <div class="tr-sum-item">
                <div class="tr-sum-dot tr-sum-dot-ok"></div>
                <span class="tr-sum-label">Approved:&nbsp;</span>
                <span class="tr-sum-count">{{ $enrolledCount }} course{{ $enrolledCount != 1 ? 's' : '' }}</span>
            </div>
            <div class="tr-sum-item">
                <div class="tr-sum-dot tr-sum-dot-pnd"></div>
                <span class="tr-sum-label">Pending:&nbsp;</span>
                <span class="tr-sum-count">{{ $pendingCount }} course{{ $pendingCount != 1 ? 's' : '' }}</span>
            </div>
            <div class="tr-sum-item" style="margin-left:auto;">
                <span class="tr-sum-label">Total Registered:&nbsp;</span>
                <span class="tr-sum-count">PKR {{ number_format($reg->total_amount, 0) }}</span>
            </div>
        </div>
        @endif

        {{-- Courses --}}
        <div class="tr-section">
            <p class="tr-section-title"><i class="bi bi-book me-1"></i>Registered Courses</p>
            @foreach($courseIds as $cid)
            @php
                $course     = $allCourses[$cid] ?? null;
                $isEnrolled = in_array($cid, $enrolledIds);
            @endphp
            @if($course)
            <div class="tr-course-row {{ $isEnrolled ? 'tr-course-row-ok' : ($reg->status === 'approved' ? 'tr-course-row-pnd' : 'tr-course-row-wait') }}">
                <div class="tr-course-icon {{ $isEnrolled ? 'tr-icon-ok' : ($reg->status === 'approved' ? 'tr-icon-pnd' : 'tr-icon-wait') }}">
                    <i class="bi bi-{{ $isEnrolled ? 'check-lg' : ($reg->status === 'approved' ? 'clock' : 'hourglass-split') }}"></i>
                </div>
                <span class="tr-course-name">{{ $course->title }}</span>
                @if($course->price)
                    <span style="font-size:.73rem;color:#94A3B8;margin-right:.5rem;">PKR {{ number_format($course->price, 0) }}</span>
                @endif
                <span class="tr-course-status-tag {{ $isEnrolled ? 'tr-tag-ok' : ($reg->status === 'approved' ? 'tr-tag-pnd' : 'tr-tag-wait') }}">
                    {{ $isEnrolled ? 'Enrolled' : ($reg->status === 'approved' ? 'Awaiting Payment' : 'Under Review') }}
                </span>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Payment slips --}}
        <div class="tr-section">
            <p class="tr-section-title"><i class="bi bi-receipt me-1"></i>Payment Slips ({{ $reg->slips->count() }})</p>
            @forelse($reg->slips->sortBy('created_at') as $i => $slip)
            <div class="tr-slip-row">
                <span class="tr-slip-num">Slip {{ $loop->iteration }}</span>
                <span class="tr-slip-date"><i class="bi bi-calendar3 me-1"></i>{{ $slip->created_at->format('d M Y, h:i A') }}</span>
                <span class="tr-slip-badge {{ $slip->status === 'approved' ? 'tr-slip-approved' : 'tr-slip-pending' }}">
                    {{ ucfirst($slip->status) }}
                </span>
                <a href="{{ asset('storage/' . $slip->file_path) }}" target="_blank" class="tr-slip-view">
                    <i class="bi bi-eye"></i> View
                </a>
            </div>
            @empty
            <p style="font-size:.8rem;color:#CBD5E1;margin:0;">No payment slip uploaded yet.</p>
            @endforelse
        </div>

        {{-- Action prompt --}}
        @if($pendingCount > 0)
        <div class="tr-alert-upload">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <span>
                {{ $pendingCount }} course{{ $pendingCount != 1 ? 's' : '' }} still pending.
                @if(!$hasSlip)
                    Please <a href="{{ route('payment.upload') }}">upload your payment slip</a> to begin verification.
                @else
                    Once your payment is verified, you will be enrolled. Need to pay for more?
                    <a href="{{ route('payment.upload') }}">Upload another slip</a>.
                @endif
            </span>
        </div>
        @endif

    </div>
    @endforeach

    <div class="tr-search-again">
        <a href="{{ route('home') }}">← Back to Home</a>
        &nbsp;·&nbsp;
        <a href="{{ route('payment.upload') }}">Upload a Payment Slip</a>
    </div>

</div>
@endsection
