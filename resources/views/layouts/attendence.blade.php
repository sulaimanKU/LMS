@extends('index')

@section('contents')
<!-- ===========================
      ACTION BUTTON (Teacher Tool)
      PURPOSE: Open student identity verification modal
      ROLE: Teacher / Admin
=========================== -->
<div class="m-4">

    <button class="btn btn-primary openStudentModal px-4 py-2" style="border-radius: 8px;">
        Verify Student Identity
    </button>

</div>


<!-- ======================================================
       HEADER: Student Verification Section
       PURPOSE: Confirm student identity before recording attendance
       ROLE: Teacher / Admin
====================================================== -->
<div class="p-3 bg-light border rounded mb-4" style="border-left: 5px solid #0d6efd;">
    <h4 class="fw-bold mb-1">Student Information Verification</h4>
    <p class="mb-1">
        <strong>Purpose:</strong> Ensures attendance is recorded for the correct student.
    </p>
    <p class="mb-0">
        <strong>Role:</strong> Teacher / Admin
    </p>
</div>



<!-- ===========================
        STUDENT INFO MODAL
=========================== -->
<div class="modal fade" id="studentInfoModal" tabindex="-1" aria-labelledby="studentInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Verify Student Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <div class="alert alert-info mb-4">
                    <strong>Why this step?</strong>
                    This ensures details belong to the correct student before attendance is submitted.
                </div>

                <form id="studentInfoForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Student Name</label>
                            <input type="text" class="form-control" id="studentNameInput" placeholder="Enter student name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Father Name</label>
                            <input type="text" class="form-control" id="fatherNameInput" placeholder="Enter father name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Class</label>
                            <input type="text" class="form-control" id="classInput" placeholder="Enter class">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Roll Number</label>
                            <input type="text" class="form-control" id="rollNumberInput" placeholder="Enter roll number">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2" style="border-radius: 6px;">
                            Confirm & Continue
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


    <!-- ======================================================
       HEADER: Record Attendance
       PURPOSE: Teachers mark present/absent/late/excused
       ROLE: Teacher / Admin
    ====================================================== -->
    <div class="p-3 bg-light border rounded mb-3 mt-5">
        <h4 class="fw-bold mb-1">Record Attendance</h4>
        <p class="mb-1"><strong>Purpose:</strong> Allows teachers to quickly mark student attendance.</p>
        <p class="mb-0"><strong>Role:</strong> Teacher / Admin</p>
    </div>

    <!-- ============================
          RECORD ATTENDANCE SECTION
    ============================= -->
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Record Attendance</h5>
        </div>

        <div class="card-body">

            <!-- INFO -->
            <div class="alert alert-secondary">
                <strong>Step 1: Select Session Details</strong><br>
                Choose the course, class date, and topic.
                This will attach attendance to the correct lecture/session.
            </div>

            <!-- Session Selector -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Course</label>
                    <select class="form-select">
                        <option>Select Course</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Topic / Session</label>
                    <input type="text" class="form-control" placeholder="Topic today">
                </div>
            </div>

            <!-- TIP -->
            <div class="alert alert-warning py-2">
                <strong>Tip:</strong> Use the “Mark All Present” button when most students are present.
            </div>

            <div class="mb-3 text-end">
                <button class="btn btn-success btn-sm">Mark All Present</button>
            </div>

            <!-- INFO -->
            <div class="alert alert-info">
                <strong>Step 2: Mark Each Student</strong><br>
                Select Present, Absent, Late, or Excused.
                Each option is a single click for fast recording.
            </div>

            <!-- Student Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Student Name</th>
                            <th>ID</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Late</th>
                            <th class="text-center">Excused</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="/images/user.png" width="40" class="rounded-circle"></td>
                            <td>John Doe</td>
                            <td>STD-001</td>
                            <td class="text-center"><input type="radio" name="s1"></td>
                            <td class="text-center"><input type="radio" name="s1"></td>
                            <td class="text-center"><input type="radio" name="s1"></td>
                            <td class="text-center"><input type="radio" name="s1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary">Submit Attendance</button>
            </div>

        </div>
    </div>




    <!-- ======================================================
       HEADER: Attendance Reports & Analytics
       PURPOSE: Analyze trends, export data, find low attendance students
       ROLE: Admin / Principal / Instructor
    ====================================================== -->
    <div class="p-3 bg-light border rounded mb-3 mt-5">
        <h4 class="fw-bold mb-1">Attendance Reports & Analytics</h4>
        <p class="mb-1"><strong>Purpose:</strong> View attendance trends, identify weak students, and export data.</p>
        <p class="mb-0"><strong>Role:</strong> Admin / Principal / Instructor</p>
    </div>

    <!-- ============================
          REPORTS & ANALYTICS
    ============================= -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Attendance Reports & Analytics</h5>
        </div>

        <div class="card-body">

            <div class="alert alert-secondary mb-4">
                <strong>Analyze Attendance</strong><br>
                Use filters to find low-performing students or attendance trends.
                Export data for meetings or official reports.
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Course</label>
                    <select class="form-select">
                        <option>All Courses</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Instructor</label>
                    <select class="form-select">
                        <option>All Instructors</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" class="form-control">
                </div>
            </div>

            <div class="mb-3 text-end">
                <button class="btn btn-success btn-sm">Export to Excel</button>
                <button class="btn btn-secondary btn-sm">Export CSV</button>
            </div>

            <!-- Report Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>ID</th>
                            <th>Course</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Maryam Khan</td>
                            <td>STD-022</td>
                            <td>Mathematics</td>
                            <td>22</td>
                            <td>8</td>
                            <td><span class="text-danger fw-bold">73%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>




    <!-- ======================================================
       HEADER: Student Self View
       PURPOSE: Helps students monitor their attendance
       ROLE: Student
    ====================================================== -->
    <div class="p-3 bg-light border rounded mb-3 mt-5">
        <h4 class="fw-bold mb-1">My Attendance Summary</h4>
        <p class="mb-1"><strong>Purpose:</strong> Students can track their own attendance performance.</p>
        <p class="mb-0"><strong>Role:</strong> Student</p>
    </div>

    <!-- ============================
          STUDENT SELF VIEW
    ============================= -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">My Attendance Summary</h5>
        </div>

        <div class="card-body">

            <div class="alert alert-secondary">
                <strong>Your Attendance Snapshot</strong><br>
                This section helps students monitor their progress and avoid warnings.
            </div>

            <!-- Summary Boxes -->
            <div class="row text-center mb-4">
                <div class="col-md-3">
                    <div class="p-3 bg-light rounded">
                        <h6>Total Sessions</h6>
                        <h4>30</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded">
                        <h6>Present</h6>
                        <h4>25</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded">
                        <h6>Absent</h6>
                        <h4>5</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="p-3 bg-light rounded">
                        <h6>Percentage</h6>
                        <h4 class="text-danger">83%</h4>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                Warning: Your attendance is below the required threshold.
            </div>

            <h6 class="fw-bold mb-2">Session History</h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Topic</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-01-15</td>
                            <td>Introduction to AI</td>
                            <td><span class="badge bg-success">Present</span></td>
                        </tr>
                        <tr>
                            <td>2025-01-16</td>
                            <td>Neural Networks</td>
                            <td><span class="badge bg-danger">Absent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


   <script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('studentInfoModal'));

    document.querySelectorAll('.openStudentModal').forEach(btn => {
        btn.addEventListener('click', function () {

            // Clear fields every time modal opens
            document.getElementById('studentNameInput').value = "";
            document.getElementById('fatherNameInput').value = "";
            document.getElementById('classInput').value = "";
            document.getElementById('rollNumberInput').value = "";

            modal.show();
        });
    });
});
</script>

@endsection
