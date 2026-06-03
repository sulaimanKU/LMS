@extends('applayouts.app')

@section('contents')
<style>
    /* 1. THE EMERGENCY RESET - Forces the page to be mobile-friendly */
    .dashboard-container {
        width: 100% !important;
        max-width: 100vw !important;
        overflow-x: hidden !important; /* Stops the right side from bleeding out */
        padding: 10px;
        background-color: #f8f9fc;
    }

    /* 2. THE HEADER FIX - No more hidden buttons */
    .header-box {
        display: block; /* Forces vertical stack on mobile */
        width: 100%;
        margin-bottom: 20px;
        padding: 10px;
    }

    .header-box h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #273044;
        margin-bottom: 5px;
    }

    /* Buttons row that adapts */
    .header-actions {
        display: flex;
        flex-wrap: wrap; /* Critical: Wraps buttons to new line if no space */
        gap: 8px;
        width: 100%;
        margin-top: 10px;
    }

    .btn-custom {
        flex: 1; /* Makes buttons equal width on mobile */
        min-width: 140px; /* Ensures buttons don't get too small */
        padding: 12px;
        font-weight: 700;
        border-radius: 8px;
        text-align: center;
        border: none;
    }

    /* Desktop View adjustments */
    @media (min-width: 768px) {
        .header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-actions {
            width: auto;
            margin-top: 0;
        }
    }

    /* 3. RESPONSIVE STAT CARDS (2 per row on mobile) */
    .stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* 2 columns on mobile */
        gap: 10px;
        margin-bottom: 20px;
    }

    @media (min-width: 992px) {
        .stat-grid {
            grid-template-columns: 1fr 1fr 1fr 1fr; /* 4 columns on desktop */
        }
    }

    .s-card {
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
    }

    .s-card small { color: #718096; font-weight: 700; font-size: 10px; text-transform: uppercase; }
    .s-card h4 { margin: 0; font-weight: 800; color: #2d3748; }

    /* 4. TABLE SAFETY */
    .scroll-table {
        width: 100%;
        overflow-x: auto; /* Allows swiping the table without breaking the page */
        background: white;
        border-radius: 12px;
        border: 1px solid #edf2f7;
    }
</style>

<div class="dashboard-container">

    <div class="header-box">
        <div>
            <h3>Faculty Pro</h3>
            <p class="text-muted small mb-0">Logged in as: <strong>{{ Auth::user()->name }}</strong></p>
        </div>
        <div class="header-actions">
            <button class="btn-custom bg-white border text-dark shadow-sm">
                <i class="fa-solid fa-file-export me-1"></i> Report
            </button>
            <button class="btn-custom bg-primary text-white shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Create
            </button>
        </div>
    </div>

    <div class="stat-grid">
        <div class="s-card">
            <small>Courses</small>
            <h4>04</h4>
        </div>
        <div class="s-card">
            <small>Students</small>
            <h4>128</h4>
        </div>
        <div class="s-card">
            <small>Pending</small>
            <h4 class="text-danger">12</h4>
        </div>
        <div class="s-card">
            <small>Attendance</small>
            <h4 class="text-success">94%</h4>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="scroll-table">
                <div class="p-3 border-bottom fw-bold">Active Sessions</div>
                <table class="table mb-0">
                    <thead class="bg-light">
                        <tr class="small">
                            <th>Class</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="small fw-bold">CS101 - Batch A</td>
                            <td><span class="badge bg-success">Live</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-dark">Join</button></td>
                        </tr>
                        <tr>
                            <td class="small fw-bold">Web Dev - Batch B</td>
                            <td><span class="badge bg-warning">11:30</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-outline-dark">Wait</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="s-card bg-primary text-white">
                <h6 class="fw-bold mb-2">Faculty Note</h6>
                <p class="small mb-0 opacity-75">Staff meeting Friday at 4:00 PM in the Hall.</p>
            </div>
        </div>
    </div>

</div>

@endsection
