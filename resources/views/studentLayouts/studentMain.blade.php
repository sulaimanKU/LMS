@extends('applayouts.app')

@section('contents')
<style>
    /* Clean Industrial Typography */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    #student-dashboard {
        background-color: #ffffff;
        font-family: 'Inter', sans-serif;
        color: #111;
        min-height: 100vh;
    }

    .border-main { border: 1px solid #efefef !important; }

    /* Header Grid Sync */
    .course-list-header {
        display: grid;
        grid-template-columns: 2.5fr 1.5fr 1fr 100px;
        padding-bottom: 15px;
        border-bottom: 2px solid #111;
        margin-bottom: 10px;
    }

    /* Course Row Design */
    .course-entry {
        display: grid;
        grid-template-columns: 2.5fr 1.5fr 1fr 100px;
        padding: 24px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
        transition: background 0.2s ease;
    }

    .course-entry:hover { background-color: #fafafa; }

    .teacher-tag {
        background: #f4f4f5;
        color: #52525b;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
    }

    .progress-bar-container {
        height: 4px;
        background: #e4e4e7;
        width: 100%;
        margin-top: 8px;
        border-radius: 2px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #000;
        border-radius: 2px;
    }

    .stat-box {
        padding: 30px;
        border-right: 1px solid #efefef;
    }

    .stat-box:last-child { border-right: none; }
</style>

<div id="student-dashboard" class="p-4 p-md-5">

    {{-- 1. Top Navigation Bar --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-700 mb-1">Student Workspace</h2>
            <p class="text-muted mb-0 small">Welcome back, <span class="text-dark fw-600">{{ $user->name ?? 'Student' }}</span></p>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="text-muted small d-block text-uppercase fw-bold">Current Session</span>
            <span class="fw-600">{{ now()->format('D, M d, Y') }}</span>
        </div>
    </div>

    {{-- 2. Stats Summary --}}
    <div class="row g-0 border-main mb-5 rounded-3 shadow-sm">
        <div class="col-md-4 stat-box">
            <span class="text-muted small text-uppercase fw-bold opacity-75">Active Modules</span>
            <h2 class="fw-700 mb-0 mt-2">{{ $courseCount ?? 0 }}</h2>
        </div>
        <div class="col-md-4 stat-box">
            <span class="text-muted small text-uppercase fw-bold opacity-75">Avg. Attendance</span>
            <h2 class="fw-700 mb-0 mt-2">94.2%</h2>
        </div>
        <div class="col-md-4 stat-box">
            <span class="text-muted small text-uppercase fw-bold opacity-75">Completed</span>
            <h2 class="fw-700 mb-0 mt-2">12 <span class="fs-6 text-muted fw-normal">/ 15 Credits</span></h2>
        </div>
    </div>

    <div class="row g-5">
        {{-- 3. Main Curriculum List --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="text-uppercase fw-bold small text-muted mb-0">My Curriculum</h6>
                <span class="badge bg-light text-dark border-main rounded-1">Semester 1</span>
            </div>

            <div class="course-list-header d-none d-md-grid">
                <span class="small fw-bold text-muted">MODULE TITLE</span>
                <span class="small fw-bold text-muted">INSTRUCTOR</span>
                <span class="small fw-bold text-muted">COMPLETION</span>
                <span></span>
            </div>

           @forelse($enrollments as $enrollment)
<div class="course-entry">
    {{-- Module Info --}}
    <div class="pe-3">
        <span class="fw-700 d-block text-dark fs-5 mb-1">{{ $enrollment->modules->title ?? 'Untitled Module' }}</span>
        <span class="text-muted small">Code: MOD-{{ $enrollment->module_id }}</span>
    </div>

    {{-- Instructor (FIXED LOGIC) --}}
    <div class="d-flex flex-column gap-1 align-items-start">
        @forelse($enrollment->modules->teacher as $t)
            <span class="teacher-tag">
                <i class="fas fa-user-circle me-2" style="font-size: 10px;"></i>
                {{ $t->name }}
            </span>
        @empty
            <span class="text-muted small">Staff</span>
        @endforelse
    </div>

    <div class="pe-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small fw-700">{{ $enrollment->progress ?? 0 }}%</span>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
        </div>
    </div>

    {{-- Action --}}
    <div class="text-end">
        <a href="#" class="btn btn-outline-dark btn-sm rounded-0 fw-bold px-3">LAUNCH</a>
    </div>
</div>
            @empty
            <div class="py-5 text-center border-main rounded-3 bg-light">
                <i class="fas fa-folder-open mb-3 text-muted fs-2"></i>
                <p class="text-muted mb-0">No active module enrollments found.</p>
            </div>
            @endforelse
        </div>

        {{-- 4. Right Sidebar: Deadlines/Announcements --}}
        <div class="col-lg-3">
            <h6 class="text-uppercase fw-bold small text-muted mb-4">Upcoming Deadlines</h6>
            <div class="border-start border-3 border-dark ps-3 mb-4">
                <p class="fw-bold mb-1 small">Project Submission</p>
                <span class="text-muted small">Due: Jan 22, 2026</span>
            </div>
            <div class="border-start border-3 border-light ps-3 mb-4">
                <p class="fw-bold mb-1 small text-muted">Final Examination</p>
                <span class="text-muted small">Due: Feb 05, 2026</span>
            </div>

            <div class="bg-light p-4 rounded-3 mt-5">
                <p class="small fw-bold mb-2">Need Help?</p>
                <p class="text-muted small mb-0">Contact the academic registrar for support regarding your enrollments.</p>
            </div>
        </div>
    </div>
</div>
@endsection
