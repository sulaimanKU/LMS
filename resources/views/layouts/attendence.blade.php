@extends('index')

@section('contents')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Record Attendance</h5>
    </div>

    <div class="card-body">

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

        <!-- Bulk Action -->
        <div class="mb-3 text-end">
            <button class="btn btn-success btn-sm">Mark All Present</button>
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
                    <!-- Single Row Example -->
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

        <!-- Submit -->
        <div class="text-end mt-3">
            <button class="btn btn-primary">Submit Attendance</button>
        </div>

    </div>
</div>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Attendance Reports & Analytics</h5>
    </div>

    <div class="card-body">

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

        <!-- Export -->
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
<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">My Attendance Summary</h5>
    </div>

    <div class="card-body">

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

        <!-- Warning -->
        <div class="alert alert-warning">
            Your attendance is below 85%. Please improve to avoid academic penalty.
        </div>

        <!-- History Table -->
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


@endsection
