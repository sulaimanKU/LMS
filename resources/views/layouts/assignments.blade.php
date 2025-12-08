@extends('index')

@section('contents')
<div class="container mt-4">
    <h3 class="mb-3">Assignments</h3>

    <table id="assignmentsTable" class="table table-striped table-hover table-bordered">

        <thead class="table-primary text-center">
            <tr>
                <th>Subject</th>
                <th>Title</th>
                <th>Due Date</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Math</td>
                <td>Algebra Worksheet</td>
                <td>2025-02-10</td>
                <td class="text-center">45 / 50</td> <td class="text-center"> <span class="badge bg-success">Graded</span>
                </td>
            </tr>

            <tr>
                <td>English</td>
                <td>Essay Writing</td>
                <td>2025-02-12</td>
                <td class="text-center">Not Graded</td> <td class="text-center"> <span class="badge bg-warning">Pending</span>
                </td>
            </tr>

            <tr>
                <td>Science</td>
                <td>Lab Report</td>
                <td>2025-02-15</td>
                <td class="text-center">30 / 40</td> <td class="text-center"> <span class="badge bg-info text-dark">Submitted</span>
                </td>
            </tr>

        </tbody>

        <tfoot>
            </tfoot>

    </table>
</div>

@endsection
