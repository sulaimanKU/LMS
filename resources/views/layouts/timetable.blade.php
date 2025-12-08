@extends('index')

@section('contents')
<div class="container mt-4">

    <h3 class="mb-3">Weekly Timetable</h3>

    <div class="table-responsive">

        <table class="table table-bordered text-center align-middle" id="timetable">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Time</th>
                    <th scope="col">Monday</th>
                    <th scope="col">Tuesday</th>
                    <th scope="col">Wednesday</th>
                    <th scope="col">Thursday</th>
                    <th scope="col">Friday</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td scope="row"><strong>09:00 – 10:00</strong></td>

                    <td>
                        <div class="p-2 text-white rounded" style="background:#007bff;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             title="Subject: Mathematics&#10;Instructor: Mr. Ali&#10;Room: A-101">
                            Math
                        </div>
                    </td>

                    <td>
                        <div class="p-2 text-white rounded" style="background:#28a745;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             title="Subject: Biology&#10;Instructor: Dr. Sana&#10;Room: B-204">
                            Biology
                        </div>
                    </td>

                    <td></td>

                    <td>
                        <div class="p-2 text-white rounded" style="background:#dc3545;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             title="Subject: Chemistry&#10;Instructor: Sir Ahmed&#10;Room: Lab-1">
                            Chemistry
                        </div>
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td scope="row"><strong>10:00 – 11:00</strong></td>

                    <td></td>

                    <td>
                        <div class="p-2 rounded" style="background:#ffc107; color:#000;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             title="Subject: English&#10;Instructor: Ms. Fatima&#10;Room: C-310">
                            English
                        </div>
                    </td>

                    <td></td>

                    <td>
                        <div class="p-2 text-white rounded" style="background:#6610f2;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             title="Subject: Computer Science&#10;Instructor: Sir Bilal&#10;Room: Lab-3">
                            CS
                        </div>
                    </td>

                    <td></td>
                </tr>

            </tbody>
        </table>

    </div>
</div>
<script>
    // Tooltip Initialization Script (Correct)
    document.addEventListener("DOMContentLoaded", function () {
        // Line break fix for tooltip content, if needed (using &#10; in title attribute)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

@endsection
