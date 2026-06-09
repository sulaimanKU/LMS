    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>



<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "language": {
                "search": "Search User:",
                "emptyTable": "No users found in database"
            }
        });
    });
</script>
