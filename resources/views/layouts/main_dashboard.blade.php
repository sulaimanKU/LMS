 @extends('index')

@section('contents')
 <div class="container-fluid p-0">

                <!-- Top stat cards -->
                <div class="row g-2 mb-3">
                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card stat-card shadow-sm text-center">
                            <h6>Total Students</h6>
                            <h4>120</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card stat-card shadow-sm text-center">
                            <h6>Courses</h6>
                            <h4>15</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card stat-card shadow-sm text-center">
                            <h6>Assignments</h6>
                            <h4>42</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 mb-3">
                        <div class="card stat-card shadow-sm text-center">
                            <h6>Messages</h6>
                            <h4>8</h4>
                        </div>
                    </div>
                </div>

                <!-- Charts grid -->
                <div class="row g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="chart-card card">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="chart-card card">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="chart-card card">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="chart-card card">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
@endsection
