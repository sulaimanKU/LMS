@extends('index')

@section('contents')

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#ttCollapseForm" aria-expanded="false" aria-controls="ttCollapseForm">
            <i class="bi bi-calendar-plus"></i> Add/Edit Class Slot
        </button>

        <button class="btn btn-danger d-none" type="button" data-bs-toggle="modal" data-bs-target="#ttConfirmDeleteModal" id="ttDeleteTriggerButton">
            <i class="bi bi-trash"></i> Delete Selected Slot
        </button>
    </div>

    <div class="collapse" id="ttCollapseForm">
        <div class="card-body border-start border-primary border-4 p-4">

            <h5 class="text-primary mb-4">
                <i class="bi bi-pencil-square"></i> Configure Slot Details
            </h5>

            <form id="ttTimetableForm" action="[YOUR_SUBMIT_URL]" method="POST">
                <div class="container-fluid p-0">
                    <input type="hidden" name="class_id" id="classIdInput">

                    <h6 class="text-secondary mb-3">Class Assignment</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sectionSelect" class="form-label">Section / Grade Level</label>
                            <select id="sectionSelect" name="section_id" class="form-select" required>
                                <option value="" disabled selected>Select Section</option>
                                <option value="1">Grade 9 - A</option>
                                <option value="2">Grade 10 - B</option>
                                <option value="3">Faculty Training</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="classTypeSelect" class="form-label">Class Type</label>
                            <select id="classTypeSelect" name="class_type" class="form-select" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="lecture">Lecture</option>
                                <option value="lab">Laboratory</option>
                                <option value="tutorial">Tutorial</option>
                                <option value="meeting">Meeting</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="subjectSelect" class="form-label">Subject</label>
                            <select id="subjectSelect" name="subject_id" class="form-select" required>
                                <option value="" disabled selected>Select Subject</option>
                                <option value="1">Mathematics</option>
                                <option value="2">English Literature</option>
                                <option value="3">Computer Science</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="instructorSelect" class="form-label">Instructor</label>
                            <select id="instructorSelect" name="instructor_id" class="form-select" required>
                                <option value="" disabled selected>Select Instructor</option>
                                <option value="101">Mr. Ali</option>
                                <option value="102">Ms. Fatima</option>
                            </select>
                        </div>
                    </div>

                    <hr class="mb-4"> <h6 class="text-secondary mb-3">Scheduling & Location</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="daySelect" class="form-label">Day of Week</label>
                            <select id="daySelect" name="day_of_week" class="form-select" required>
                                <option value="" disabled selected>Select Day</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="roomSelect" class="form-label">Room/Location</label>
                            <select id="roomSelect" name="room_id" class="form-select" required>
                                <option value="" disabled selected>Select Room</option>
                                <option value="A101">Room A-101</option>
                                <option value="Lab3">Lab 3</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="capacityInput" class="form-label">Max Capacity</label>
                            <input type="number" id="capacityInput" name="max_capacity" class="form-control" placeholder="e.g., 30" required>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-4">
                            <label for="startTime" class="form-label">Start Time</label>
                            <input type="time" id="startTime" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="endTime" class="form-label">End Time</label>
                            <input type="time" id="endTime" name="end_time" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="displayColor" class="form-label">Timetable Block Color</label>
                            <input type="color" id="displayColor" name="display_color" class="form-control form-control-color" value="#007bff">
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-toggle="collapse" data-bs-target="#ttCollapseForm" aria-expanded="true">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success" id="saveButton">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

---

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table"></i> Current Class Slots
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Class Day</th>
                        <th scope="col">Duration (Time)</th>
                        <th scope="col">Subject (Type)</th>
                        <th scope="col">Assigned Section</th>
                        <th scope="col">Lead Instructor</th>
                        <th scope="col">Location (Max Cap.)</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-class-id="101" data-color="#007bff">
                        <td>101</td>
                        <td>**Monday**</td>
                        <td>08:00 - 09:30</td>
                        <td>Mathematics (Lecture)</td>
                        <td>Grade 9 - A</td>
                        <td>Mr. Ali</td>
                        <td>A-101 (30)</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-slot-btn">Edit</button>
                        </td>
                    </tr>
                    <tr data-class-id="102" data-color="#28a745">
                        <td>102</td>
                        <td>**Tuesday**</td>
                        <td>10:00 - 11:30</td>
                        <td>English Lit (Tutorial)</td>
                        <td>Grade 10 - B</td>
                        <td>Ms. Fatima</td>
                        <td>Lab 3 (20)</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-slot-btn">Edit</button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ttConfirmDeleteModal" aria-hidden="true" aria-labelledby="confirmDeleteModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
      <div class="modal-body">
        Are you sure you want to permanently delete this class slot? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-danger" id="finalDeleteButton">Delete Permanently</button>
      </div>
    </div>
  </div>
</div>

@endsection
