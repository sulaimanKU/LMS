@extends('applayouts.app')

@section('contents')
<div class="tc-page">

    {{-- ── Notification Area ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i><strong>Validation Error:</strong>
            <ul class="mb-0 ps-3 mt-1 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="tc-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="tc-header-badge"><i class="fa-solid fa-video me-1.5"></i> Virtual Classroom Manager</div>
                <h4 class="tc-title"><i class="fa-solid fa-chalkboard-user text-indigo me-2"></i>Online Class Schedule</h4>
                <p class="tc-subtitle">Schedule video lectures, manage live rooms, and notify enrolled participants</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn tc-btn-outline shadow-sm" data-bs-toggle="modal" data-bs-target="#notifyStudentsModal">
                    <i class="fa-solid fa-paper-plane me-1.5 text-indigo"></i>Send Student Notice
                </button>
                <button class="btn tc-btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                    <i class="fa-solid fa-plus me-1.5"></i>Schedule New Class
                </button>
            </div>
        </div>
    </div>

    {{-- ── KPI Stat Cards ── --}}
    @php
        $liveCount      = $scheduled_classes->where('status', 'live')->count();
        $upcomingCount  = $scheduled_classes->where('status', 'upcoming')->count();
        $finishedCount  = $scheduled_classes->where('status', 'finished')->count();
        $totalCount     = $scheduled_classes->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="tc-stat-card {{ $liveCount > 0 ? 'tc-stat-red-active' : 'tc-stat-gray' }}">
                <div class="tc-stat-icon">
                    <i class="fa-solid fa-circle-dot {{ $liveCount > 0 ? 'tc-pulse-anim' : '' }}"></i>
                </div>
                <div>
                    <h3 class="tc-stat-num">{{ $liveCount }}</h3>
                    <span class="tc-stat-lbl">Active Live Rooms</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tc-stat-card tc-stat-indigo">
                <div class="tc-stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div>
                    <h3 class="tc-stat-num">{{ $upcomingCount }}</h3>
                    <span class="tc-stat-lbl">Upcoming Scheduled</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tc-stat-card tc-stat-emerald">
                <div class="tc-stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <h3 class="tc-stat-num">{{ $finishedCount }}</h3>
                    <span class="tc-stat-lbl">Completed Sessions</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="tc-stat-card tc-stat-amber">
                <div class="tc-stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                <div>
                    <h3 class="tc-stat-num">{{ $totalCount }}</h3>
                    <span class="tc-stat-lbl">Total Classes</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Desktop Table View ── --}}
    <div class="tc-card desktop-table mb-4">
        <div class="tc-card-head d-flex align-items-center justify-content-between">
            <span class="tc-card-title"><i class="fa-solid fa-list-check me-2 text-indigo"></i>Class Directory & Controls</span>
            <span class="badge bg-light text-dark border px-2.5 py-1.5 font-monospace" style="font-size:0.75rem;">
                Total: {{ $scheduled_classes->count() }} Sessions
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Course / Module</th>
                        <th class="py-3">Class Title & Topic</th>
                        <th class="py-3">Scheduled Date & Time</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-end pe-4 py-3">Management Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scheduled_classes as $class)
                    <tr>
                        <td class="ps-4 py-3">
                            <span class="badge bg-indigo-light text-indigo border border-indigo-subtle px-2.5 py-1.5" style="font-size:0.75rem; font-weight:600;">
                                <i class="fa-solid fa-book-bookmark me-1 text-indigo"></i>{{ $class->module->title ?? 'General' }}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="fw-bold text-dark d-block mb-0.5" style="font-size:0.9rem;">{{ $class->title }}</span>
                            @if($class->meeting_id)
                                <span class="text-muted" style="font-size:0.72rem;"><i class="fa-solid fa-key me-1"></i>ID: {{ $class->meeting_id }}</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="tc-date-chip">
                                    <span class="tc-date-day">{{ \Carbon\Carbon::parse($class->class_date)->format('d') }}</span>
                                    <span class="tc-date-month">{{ \Carbon\Carbon::parse($class->class_date)->format('M') }}</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size:0.82rem;"><i class="fa-regular fa-clock me-1 text-indigo"></i>{{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}</span>
                                    <span class="text-muted" style="font-size:0.72rem;">{{ \Carbon\Carbon::parse($class->class_date)->format('Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center py-3">
                            @if($class->status == 'live')
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold" style="font-size:0.72rem; letter-spacing:0.5px;">
                                    <span class="tc-pulse-dot"></span> LIVE NOW
                                </span>
                            @elseif($class->status == 'upcoming')
                                <span class="badge bg-indigo-light text-indigo border border-indigo-subtle px-2.5 py-1.5 rounded-pill" style="font-size:0.72rem; font-weight:700;">
                                    <i class="fa-regular fa-clock me-1"></i>Scheduled
                                </span>
                            @elseif($class->status == 'finished')
                                <span class="badge bg-emerald-light text-emerald px-2.5 py-1.5 rounded-pill" style="font-size:0.72rem; font-weight:700;">
                                    <i class="fa-solid fa-circle-check me-1"></i>Completed
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-2.5 py-1.5 rounded-pill" style="font-size:0.72rem; font-weight:700;">
                                    {{ ucfirst($class->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST" class="d-inline-flex gap-1.5">
                                @csrf
                                @method('PATCH')

                                @if($class->status == 'upcoming')
                                    <button type="submit" name="start" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="font-size:0.75rem;">
                                        <i class="fa-solid fa-play me-1"></i>Start Class
                                    </button>
                                    <button type="submit" name="cancel" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" style="font-size:0.75rem;"
                                            onclick="return confirm('Are you sure you want to cancel this scheduled class?')">
                                        <i class="fa-solid fa-xmark me-1"></i>Cancel
                                    </button>
                                @elseif($class->status == 'live')
                                    <a href="{{ $class->meeting_link }}" target="_blank" class="btn btn-sm btn-danger text-white rounded-pill px-3 fw-bold me-1" style="font-size:0.75rem;">
                                        <i class="fa-solid fa-video me-1"></i>Join Room
                                    </a>
                                    <button type="submit" name="end" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold" style="font-size:0.75rem;">
                                        <i class="fa-solid fa-stop me-1"></i>End Class
                                    </button>
                                @else
                                    <span class="text-muted small italic">Session Concluded</span>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fa-solid fa-video-slash text-muted opacity-25 mb-2" style="font-size:2.5rem;"></i>
                            <h6 class="text-dark fw-bold mb-1">No Classes Scheduled</h6>
                            <p class="text-muted small mb-3">You haven't scheduled any online classes for your assigned modules yet.</p>
                            <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                                <i class="fa-solid fa-plus me-1"></i>Schedule First Class
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Mobile Card View ── --}}
    <div class="mobile-card">
        @foreach($scheduled_classes as $class)
        <div class="card border-0 shadow-sm mb-3 rounded-4" style="background:#fff; border:1.5px solid #F1F5F9;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-indigo-light text-indigo border border-indigo-subtle" style="font-size:0.7rem;">
                        {{ $class->module->title ?? 'General' }}
                    </span>
                    @if($class->status == 'live')
                        <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill" style="font-size:0.68rem;">● LIVE NOW</span>
                    @else
                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size:0.68rem;">{{ strtoupper($class->status) }}</span>
                    @endif
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $class->title }}</h6>
                <p class="text-muted small mb-3">
                    <i class="fa-regular fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($class->class_date)->format('M d, Y') }} 
                    <span class="mx-1">•</span> 
                    <i class="fa-regular fa-clock me-1 text-indigo"></i>{{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                </p>

                <form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST" class="w-100">
                    @csrf
                    @method('PATCH')
                    <div class="d-flex gap-2">
                        @if($class->status == 'upcoming')
                            <button type="submit" name="start" class="btn btn-primary btn-sm flex-grow-1 fw-bold rounded-3">
                                <i class="fa-solid fa-play me-1"></i> Start Class
                            </button>
                            <button type="submit" name="cancel" class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Cancel class?')">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @elseif($class->status == 'live')
                            <a href="{{ $class->meeting_link }}" target="_blank" class="btn btn-danger btn-sm flex-grow-1 fw-bold rounded-3 text-center">
                                <i class="fa-solid fa-video me-1"></i> Join Room
                            </a>
                            <button type="submit" name="end" class="btn btn-dark btn-sm fw-bold rounded-3">
                                End
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- ── Schedule Class Modal ── --}}
<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-video me-2 text-indigo"></i>Schedule New Virtual Class</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('teacher.online-classes.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE / COURSE *</label>
                            <select class="form-select ts-select" name="module_id" required>
                                <option value="" selected disabled>Select Course Module...</option>
                                @foreach($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS TOPIC / TITLE *</label>
                            <input type="text" name="title" class="ts-input" required placeholder="e.g. Chapter 4: Data Analytics Workshop">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS DATE *</label>
                            <input type="date" name="class_date" class="ts-input" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">START TIME *</label>
                            <input type="time" name="start_time" class="ts-input" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">MEETING ROOM LINK (ZOOM / TEAMS / MEET) *</label>
                            <input type="url" name="meeting_link" class="ts-input" required placeholder="https://zoom.us/j/123456789">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">DURATION (MINUTES)</label>
                            <input type="number" name="duration" class="ts-input" placeholder="60">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">MEETING ID (OPTIONAL)</label>
                            <input type="text" name="meeting_id" class="ts-input" placeholder="123 456 789">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">PASSCODE (OPTIONAL)</label>
                            <input type="text" name="meeting_password" class="ts-input" placeholder="Optional passcode">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">LESSON DESCRIPTION / AGENDA</label>
                            <textarea name="description" class="ts-input" rows="2" placeholder="Brief outline of topics to cover in this session..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa-solid fa-plus me-1.5"></i>Create & Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Send Notice Modal ── --}}
<div class="modal fade" id="notifyStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-paper-plane me-2"></i>Send Class Notice to Students</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('class.notification.send') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TARGET COURSE / MODULE *</label>
                            <select class="form-select ts-select" name="module_id" id="notify_module_id" required>
                                <option value="" selected disabled>Select Course Module...</option>
                                @foreach($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS DATE (OPTIONAL)</label>
                            <input type="date" name="class_date" class="ts-input">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">MEETING LINK (OPTIONAL)</label>
                            <input type="url" name="meeting_link" class="ts-input" placeholder="https://zoom.us/j/...">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">EMAIL SUBJECT / TOPIC *</label>
                            <input type="text" name="subject" class="ts-input" required placeholder="e.g. Virtual Class Update & Reading Preparation">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">ANNOUNCEMENT BODY *</label>
                            <textarea name="message" class="ts-input" rows="4" required placeholder="Write your announcement or instructions for enrolled students..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-muted mb-0">SELECT STUDENT RECIPIENTS</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllStudents">
                                    <label class="form-check-label small fw-bold" for="selectAllStudents">Select All Students</label>
                                </div>
                            </div>
                            <div id="students_list_container" class="border rounded-3 p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <p class="text-muted small text-center mb-0 py-3">Please select a course module above to load enrolled participants.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" id="sendNotificationBtn" disabled>
                        <i class="fa-solid fa-paper-plane me-2"></i>Send Email Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moduleSelect = document.getElementById('notify_module_id');
    const studentContainer = document.getElementById('students_list_container');
    const selectAllCheckbox = document.getElementById('selectAllStudents');
    const sendBtn = document.getElementById('sendNotificationBtn');

    if (moduleSelect) {
        moduleSelect.addEventListener('change', function() {
            const moduleId = this.value;
            studentContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
            
            fetch(`/teacher/module/${moduleId}/students`)
                .then(res => {
                    if(!res.ok) throw new Error('Server returned an error');
                    return res.json();
                })
                .then(students => {
                    if(students.length === 0) {
                        studentContainer.innerHTML = '<p class="text-muted small text-center mb-0 py-3">No students enrolled in this course yet.</p>';
                        sendBtn.disabled = true;
                    } else {
                        let html = '<div class="row g-2">';
                        students.forEach(s => {
                            html += `
                                <div class="col-md-6">
                                    <div class="form-check p-2 rounded bg-white border">
                                        <input class="form-check-input student-checkbox ms-1" type="checkbox" name="student_ids[]" value="${s.id}" id="std${s.id}">
                                        <label class="form-check-label small d-block ms-4" for="std${s.id}">
                                            <span class="fw-bold d-block text-dark">${s.name}</span>
                                            <span class="text-muted" style="font-size: 0.72rem;">${s.email}</span>
                                        </label>
                                    </div>
                                </div>`;
                        });
                        html += '</div>';
                        studentContainer.innerHTML = html;
                        sendBtn.disabled = false;
                        
                        selectAllCheckbox.checked = false;
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    studentContainer.innerHTML = '<p class="text-danger small text-center mb-0 py-3">Error loading student list. Please try again.</p>';
                    sendBtn.disabled = true;
                });
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>

<style>
/* ── Page Layout ── */
.tc-page { padding: 1.5rem; background: #F8FAFF; min-height: 100%; font-family: inherit; }

/* ── Header ── */
.tc-header-badge {
    display: inline-block;
    background: #EEF2FF;
    color: #4F46E5;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    margin-bottom: 0.35rem;
}
.tc-title    { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin: 0; }
.tc-subtitle { font-size: 0.82rem; color: #64748B; margin: 0.15rem 0 0; }

.tc-btn-primary {
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 0.55rem 1.2rem;
    font-size: 0.84rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    transition: all 0.2s;
}
.tc-btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,0.3); }

.tc-btn-outline {
    background: #fff;
    color: #334155;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 0.55rem 1.1rem;
    font-size: 0.84rem;
    font-weight: 700;
    transition: all 0.2s;
}
.tc-btn-outline:hover { border-color: #4F46E5; color: #4F46E5; }

/* ── Stat Cards ── */
.tc-stat-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
}
.tc-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
.tc-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.tc-stat-red-active { border-color: #FCA5A5; }
.tc-stat-red-active .tc-stat-icon { background: #FEF2F2; color: #EF4444; }
.tc-stat-gray .tc-stat-icon       { background: #F1F5F9; color: #94A3B8; }
.tc-stat-indigo .tc-stat-icon     { background: #EEF2FF; color: #4F46E5; }
.tc-stat-emerald .tc-stat-icon    { background: #ECFDF5; color: #10B981; }
.tc-stat-amber .tc-stat-icon      { background: #FFFBEB; color: #F59E0B; }

.tc-stat-num { font-size: 1.45rem; font-weight: 800; color: #0F172A; margin: 0; line-height: 1.1; }
.tc-stat-lbl { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748B; margin-top: 0.2rem; display: block; }

.tc-pulse-anim { animation: tc-pulse-dot 1.2s infinite; }
@keyframes tc-pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.4} }

.tc-pulse-dot {
    display: inline-block; width: 6px; height: 6px; border-radius: 50%;
    background: #fff; margin-right: 4px; animation: tc-blink 1s infinite;
}
@keyframes tc-blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

/* ── Table Card ── */
.tc-card {
    background: #fff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
}
.tc-card-head {
    padding: 1.1rem 1.25rem 0.85rem;
    border-bottom: 1.5px solid #F1F5F9;
}
.tc-card-title { font-size: 0.9rem; font-weight: 800; color: #0F172A; }

.table thead th {
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748B;
    background-color: #F8FAFF;
    border-bottom: 1.5px solid #EDF2F7;
}

/* Date Chip */
.tc-date-chip {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: #EEF2FF;
    color: #4F46E5;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tc-date-day   { font-size: 0.95rem; font-weight: 800; line-height: 1; }
.tc-date-month { font-size: 0.6rem; font-weight: 700; text-transform: uppercase; margin-top: 0.05rem; }

/* Input Styling in Modals */
.ts-input, .ts-select {
    width: 100%;
    padding: 0.5rem 0.9rem;
    font-size: 0.84rem;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFF;
    outline: none;
    transition: all 0.2s;
}
.ts-input:focus, .ts-select:focus {
    background: #fff;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
}

/* Utility Colors */
.bg-indigo-light { background: #EEF2FF; }
.text-indigo     { color: #4F46E5; }
.border-indigo-subtle { border-color: #C7D2FE !important; }
.bg-emerald-light{ background: #ECFDF5; }
.text-emerald    { color: #047857; }

@media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-card   { display: block; margin-bottom: 1rem; }
}
@media (min-width: 769px) {
    .mobile-card   { display: none; }
    .desktop-table { display: block; }
}
</style>
@endsection

