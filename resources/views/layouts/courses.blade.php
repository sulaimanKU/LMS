@extends('index')

@section('contents')
<!-- KPI Cards with Proper Spacing -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm text-center py-3">
            <i class="fa-solid fa-book fa-2x text-primary mb-2"></i>
            <h6 class="mb-1">Total Courses</h6>
            <h4>5</h4>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm text-center py-3">
            <i class="fa-solid fa-play-circle fa-2x text-success mb-2"></i>
            <h6 class="mb-1">Active Courses</h6>
            <h4>3</h4>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm text-center py-3">
            <i class="fa-solid fa-check-circle fa-2x text-info mb-2"></i>
            <h6 class="mb-1">Completed</h6>
            <h4>2</h4>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm text-center py-3">
            <i class="fa-solid fa-clock fa-2x text-warning mb-2"></i>
            <h6 class="mb-1">Pending Assignments</h6>
            <h4>4</h4>
        </div>
    </div>
</div>

<!-- Tabs for Course Filters + Sort -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <!-- Tabs -->
    <ul class="nav nav-tabs" id="courseTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-courses" type="button" role="tab">All</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-courses" type="button" role="tab">Active</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-courses" type="button" role="tab">Completed</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-courses" type="button" role="tab">Pending</button>
        </li>
    </ul>

    <!-- Sort Dropdown -->
    <div class="d-flex align-items-center gap-2">
        <label for="sortCourses" class="mb-0">Sort By:</label>
        <select class="form-select form-select-sm" id="sortCourses">
            <option selected>Newest</option>
            <option>Oldest</option>
            <option>Alphabetical</option>
            <option>Progress</option>
        </select>
    </div>
</div>

<!-- Courses Grid inside Tab Content -->
<!-- Courses Grid inside Tab Content -->
<div class="tab-content" id="courseTabsContent">
    <!-- All Courses Tab -->
    <div class="tab-pane fade show active" id="all-courses" role="tabpanel">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">HTML & CSS Basics</h5>
                        <p class="card-text text-muted mb-2">Instructor: John Doe</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: 50%;"></div>
                            </div>
                            <small>50% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">JavaScript Essentials</h5>
                        <p class="card-text text-muted mb-2">Instructor: Jane Smith</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 20%;"></div>
                            </div>
                            <small>20% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Python for Beginners</h5>
                        <p class="card-text text-muted mb-2">Instructor: Mark Lee</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 70%;"></div>
                            </div>
                            <small>70% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Data Structures</h5>
                        <p class="card-text text-muted mb-2">Instructor: Alice Kim</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 90%;"></div>
                            </div>
                            <small>90% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Courses Tab -->
    <div class="tab-pane fade" id="active-courses" role="tabpanel">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">JavaScript Essentials</h5>
                        <p class="card-text text-muted mb-2">Instructor: Jane Smith</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 20%;"></div>
                            </div>
                            <small>20% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Python for Beginners</h5>
                        <p class="card-text text-muted mb-2">Instructor: Mark Lee</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 70%;"></div>
                            </div>
                            <small>70% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Continue Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Courses Tab -->
    <div class="tab-pane fade" id="completed-courses" role="tabpanel">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="{{asset('images/4144413.jpg ')}}" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Data Structures</h5>
                        <p class="card-text text-muted mb-2">Instructor: Alice Kim</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;"></div>
                            </div>
                            <small>100% Completed</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">View Certificate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Courses Tab -->
    <div class="tab-pane fade" id="pending-courses" role="tabpanel">
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card shadow-sm h-100">
                    <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Course Thumbnail">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">HTML & CSS Basics</h5>
                        <p class="card-text text-muted mb-2">Instructor: John Doe</p>
                        <div class="mb-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <small>Not Started</small>
                        </div>
                        <a href="#" class="btn btn-primary mt-auto">Start Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
