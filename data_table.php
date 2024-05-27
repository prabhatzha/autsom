<!-- data_table.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Submitted Applications</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <style>
        .required-sign::after {
            content: '*';
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Submitted Applications</h2>
    <button class="btn btn-primary add-button" onclick="location.href='form.php';" style=" position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;">
        <i class="fas fa-plus"></i> Add
    </button>
    <table id="applicationsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>State</th>
                <th>City</th>
                <th>Date of Birth</th>
                <th>Marks Range</th>
                <th>Course</th>
                <th>Stream</th>
                <th>Course Year</th>
                <th>Course Fee</th>
                <th>Discount (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded dynamically -->
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        var dataTable = $('#applicationsTable').DataTable({
            "ajax": {
                "url": "fetch_applications.php",
                "type": "POST",
                "dataType": "json",
                "dataSrc": ""
            },
            "columns": [
                {"data": "name"},
                {"data": "email"},
                {"data": "phone"},
                {"data": "state_name"}, // Display state_name instead of state_id
                {"data": "city_name"},  // Display city_name instead of city_id
                {"data": "dob"},
                {"data": "marks_range"}, // Display marks_range instead of marks_range_id
                {"data": "course_name"}, // Display course_name instead of course_id
                {"data": "stream_name"}, // Display stream_name instead of stream_id
                {"data": "course_year"},
                {"data": "course_fee"},
                {"data": "discount_percent"},
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return '<button class="btn btn-sm btn-info edit-btn" data-id="' + row.id + '">Edit</button>' +
                               '<button class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
                    }
                }
            ]
        });

        // Handle edit button click
        $('#applicationsTable tbody').on('click', '.edit-btn', function() {
            var applicationId = $(this).data('id');
            window.location.href = 'form.php?id=' + applicationId; // Redirect to form.php with application ID
        });

        // Handle delete button click
        $('#applicationsTable tbody').on('click', '.delete-btn', function() {
            var applicationId = $(this).data('id');
            if (confirm('Are you sure you want to delete this application?')) {
                $.ajax({
                    url: 'delete_application.php',
                    type: 'POST',
                    data: { id: applicationId },
                    success: function(response) {
                        dataTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting application');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
