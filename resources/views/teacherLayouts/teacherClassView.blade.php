@extends('applayouts.app')

@section('contents')
<style>
    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .animate-pulse { animation: pulse-red 2s infinite; }

    .main-content-area {
        padding-top: 1rem;
        position: relative;
    }
    .modal-con{
        z-index: 1000 !important;
    }

    @media (max-width: 768px) {
        .desktop-table { display: none; }
        .mobile-card { display: block; margin-bottom: 1rem; }
    }
    @media (min-width: 769px) {
        .mobile-card { display: none; }
        .desktop-table { display: block; }
    }
</style>

{{-- Notification Area --}}
<div class="container-fluid mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px; background-color: #d1e7dd; color: #0f5132;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <strong>Validation Error!</strong>
                    <ul class="mb-0 ps-3 mt-1" style="font-size: 0.85rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    {{ session('warning') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
<div class="main-content-area container-fluid">
    {{-- Responsive Header --}}
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-sm-6 col-md-7">
            <h2 class="fw-bold text-dark mb-0">Online Class Manager</h2>
            <p class="text-muted small mb-0">Manage your virtual teaching sessions</p>
        </div>
        <div class="col-12 col-sm-6 col-md-5 text-sm-end d-flex gap-2 justify-content-sm-end">
            <button class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#notifyStudentsModal">
                <i class="fas fa-envelope me-1"></i> Send Notice
            </button>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <i class="fas fa-video me-1"></i> New Class
            </button>
        </div>
    </div>

    {{-- DESKTOP TABLE VIEW --}}
    <div class="card border-0 shadow-sm desktop-table">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="ps-4">Module</th>
                        <th>Class Title</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scheduled_classes as $class)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $class->module->title ?? 'N/A' }}
                            </span>
                        </td>
                        <td><span class="fw-bold">{{ $class->title }}</span></td>
                        <td>
                            {{ \Carbon\Carbon::parse($class->class_date)->format('M d, Y') }} | {{ $class->start_time }}
                        </td>
                        <td>
                            @if($class->status == 'live')
                                <span class="badge rounded-pill bg-danger animate-pulse px-3">● LIVE</span>
                            @else
                                <span class="badge rounded-pill bg-light text-dark border px-3">{{ ucfirst($class->status) }}</span>
                            @endif
                        </td>

                            <td>
<form action="{{ route('teacher.online-classes.updateStatus', $class->id) }}" method="POST">
    @csrf
    @method('PATCH')
    @if($class->status == 'upcoming')
        <button type="submit" name="start" class="btn btn-sm btn-primary">
            <i class="fas fa-play me-1"></i> Start
        </button>
        <button type="submit" name="cancel" class="btn btn-sm btn-outline-danger"
                onclick="return confirm('Are you sure you want to cancel?')">
            <i class="fas fa-times"></i>
            Cancel
        </button>

    @elseif($class->status == 'live')
        <button type="submit" name="end" class="btn btn-sm btn-danger px-3">
            <i class="fas fa-stop me-1"></i> End Class
        </button>
    @endif
</form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No classes scheduled yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARD VIEW --}}
    <div class="mobile-card">
        @foreach($scheduled_classes as $class)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $class->module->module_name ?? 'N/A' }}</span>
                    <span class="badge rounded-pill {{ $class->status == 'live' ? 'bg-danger animate-pulse' : 'bg-light text-dark border' }}">
                        {{ strtoupper($class->status) }}
                    </span>
                </div>
                <h6 class="fw-bold mb-1">{{ $class->title }}</h6>
                <p class="text-muted small mb-3">{{ $class->class_date }} | {{ $class->start_time }}</p>

<form action="{{ route('teacher.online-classes.updateStatus', $class->id) }} " method="POST" class="w-100">
    @csrf
    @method('PATCH')

    <div class="d-flex gap-2">
        @if($class->status == 'upcoming')

            <button type="submit" name="start" class="btn btn-primary btn-sm flex-grow-1 fw-bold">
                <i class="fas fa-play me-1"></i> Start Now
            </button>


            <button type="submit" name="cancel" class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel class?')">
                <i class="fas fa-times"></i>

            </button>

        @elseif($class->status == 'live')
            {{-- Mobile Join Link (This is still a link because it opens a new tab) --}}
            <a href="{{ $class->meeting_link }}" target="_blank" class="btn btn-primary btn-sm flex-grow-1 fw-bold text-center">
                Join Room
            </a>


            <button type="submit" name="end" class="btn btn-danger btn-sm fw-bold">
                End
            </button>
        @endif


        @if($class->status != 'cancelled' && $class->status != 'finished')
            <button type="button" class="btn btn-light border btn-sm"><i class="fas fa-edit"></i></button>
        @endif
    </div>
</form>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- MODAL --}}

<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Schedule Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('teacher.online-classes.store') }}" method="POST">
                    @csrf

                    <div class="row g-2">

                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Module</label>
                            <select class="form-select form-select-sm bg-light border-0" name="module_id" required>
                                <option value="" selected disabled>Select...</option>
                                @foreach($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Class Title</label>
                            <input type="text" name="title" class="form-control form-control-sm bg-light border-0" required placeholder="Topic name">
                        </div>

                        {{-- 2. Date & Time --}}
                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Date</label>
                            <input type="date" name="class_date" class="form-control form-control-sm bg-light border-0" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1">Start Time</label>
                            <input type="time" name="start_time" class="form-control form-control-sm bg-light border-0" required>
                        </div>


                        <div class="col-md-8 mb-2">
                            <label class="form-label small fw-bold mb-1">Meeting Link</label>
                            <input type="url" name="meeting_link" class="form-control form-control-sm bg-light border-0" required placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold mb-1">Duration</label>
                            <input type="number" name="duration" class="form-control form-control-sm bg-light border-0" placeholder="Mins">
                        </div>


                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1 text-muted">Meeting ID</label>
                            <input type="text" name="meeting_id" class="form-control form-control-sm bg-light border-0">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small fw-bold mb-1 text-muted">Password</label>
                            <input type="text" name="meeting_password" class="form-control form-control-sm bg-light border-0">
                        </div>


                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold mb-1 text-muted">Description (Optional)</label>
                            <textarea name="description" class="form-control form-control-sm bg-light border-0" rows="1" placeholder="Short summary..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Create Class</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- NOTIFY STUDENTS MODAL --}}
<div class="modal fade" id="notifyStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold"><i class="fas fa-paper-plane me-2"></i>Send Class Notification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('class.notification.send') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TARGET MODULE</label>
                            <select class="form-select bg-light border-0" name="module_id" id="notify_module_id" required>
                                <option value="" selected disabled>Select Course...</option>
                                @foreach($teacher_courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">CLASS DATE (OPTIONAL)</label>
                            <input type="date" name="class_date" class="form-control bg-light border-0">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">EMAIL SUBJECT / TOPIC</label>
                            <input type="text" name="subject" class="form-control bg-light border-0" required placeholder="e.g. Tomorrow's Class Link & Prep">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">MESSAGE BODY</label>
                            <textarea name="message" class="form-control bg-light border-0" rows="5" required placeholder="Write your announcement here..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-muted mb-0">SELECT RECIPIENTS</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllStudents">
                                    <label class="form-check-label small fw-bold" for="selectAllStudents">Select All</label>
                                </div>
                            </div>
                            <div id="students_list_container" class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <p class="text-muted small text-center mb-0 py-3">Please select a module first to see students.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" id="sendNotificationBtn" disabled>
                        <i class="fas fa-paper-plane me-2"></i>Send Email to Selected
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
                    studentContainer.innerHTML = '<p class="text-muted small text-center mb-0 py-3">No students enrolled in this module yet.</p>';
                    sendBtn.disabled = true;
                } else {
                    let html = '<div class="row g-2">';
                    students.forEach(s => {
                        html += `
                            <div class="col-md-6">
                                <div class="form-check p-2 rounded bg-white border">
                                    <input class="form-check-input student-checkbox ms-1" type="checkbox" name="student_ids[]" value="${s.id}" id="std${s.id}">
                                    <label class="form-check-label small d-block ms-4" for="std${s.id}">
                                        <span class="fw-bold d-block">${s.name}</span>
                                        <span class="text-muted" style="font-size: 0.7rem;">${s.email}</span>
                                    </label>
                                </div>
                            </div>`;
                    });
                    html += '</div>';
                    studentContainer.innerHTML = html;
                    sendBtn.disabled = false;
                    
                    // Reset select all
                    selectAllCheckbox.checked = false;
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                studentContainer.innerHTML = '<p class="text-danger small text-center mb-0 py-3">Error loading students. Please try again or check logs.</p>';
                sendBtn.disabled = true;
            });
    });

    selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
});
</script>
@endsection
