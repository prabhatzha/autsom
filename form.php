<?php
include 'db.php';

// Initialize variables to store form data
$id = $name = $email = $phone = $state_id = $city_id = $dob = $marks_range_id = $course_id = $stream_id = $course_year = $course_fee = $discount_percent = '';

// Check if ID parameter is present in URL for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve application details from database
    $stmt = $conn->prepare("SELECT * FROM course_applications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $state_id = $row['state_id'];
        $city_id = $row['city_id'];
        $dob = $row['dob'];
          $marks_range_id = $row['marks_range_id'];
        $course_id = $row['course_id'];
        $stream_id = $row['stream_id'];
        $course_year = $row['course_year'];
        $course_fee = $row['course_fee'];
        $discount_percent = $row['discount_percent'];
    } else {
        // Application not found with provided ID
        echo '<div class="alert alert-danger" role="alert">Application not found!</div>';
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $state_id = $_POST['state'];
    $city_id = $_POST['city'];
    //$dob = $_POST['dob'];
    $date = date('Y-m-d',strtotime($_POST['dob']));
    print_r($dob);//die;
    $marks_range_id = $_POST['marks_range'];
    $course_id = $_POST['course'];
    $stream_id = $_POST['stream'];
    $course_year = $_POST['course_year'];
    $course_fee = $_POST['course_fee'];
    $discount_percent = $_POST['discount'];

    // Prepare and execute SQL statement to insert or update data into table
    if (empty($id)) {
        // Insert new application
        $sql = "INSERT INTO course_applications (name, email, phone, state_id, city_id, dob, marks_range_id, course_id, stream_id, course_year, course_fee, discount_percent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                echo $sql;//die;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiisiiiss", $name, $email, $phone, $state_id, $city_id, $dob, $marks_range_id, $course_id, $stream_id, $course_year, $course_fee, $discount_percent);
    } else {
        // Update existing application
        $sql = "UPDATE course_applications SET name = ?, email = ?, phone = ?, state_id = ?, city_id = ?, dob = ?, marks_range_id = ?, course_id = ?, stream_id = ?, course_year = ?, course_fee = ?, discount_percent = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiiisiiissi", $name, $email, $phone, $state_id, $city_id, $dob, $marks_range_id, $course_id, $stream_id, $course_year, $course_fee, $discount_percent, $id);
    }

    if ($stmt->execute()) {
        // Insert or update successful
        $message = empty($id) ? 'Application submitted successfully!' : 'Application updated successfully!';
        echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
    } else {
        // Insert or update failed
        $error_message = empty($id) ? 'Error submitting application. Please try again.' : 'Error updating application. Please try again.';
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Selection Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .required-sign::after {
            content: '*';
            color: red;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Course Selection Form</h2>
    <form id="courseSelectionForm" method="post" action="form.php<?php echo empty($id) ? '' : '?id=' . $id; ?>">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name" class="required-sign">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo $name; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="email" class="required-sign">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="phone" class="required-sign">Phone:</label>
                <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo $phone; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="dob" class="required-sign">Date of Birth:</label>
                <input type="date" id="dob" name="dob" class="form-control" value="<?php echo $dob; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="state" class="required-sign">State:</label>
                <select id="state" name="state" class="form-control">
                    <option value="">Select State</option>
                    <?php
                    $states = $conn->query("SELECT id, name FROM states");
                    while ($row = $states->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" <?php echo $state_id == $row['id'] ? 'selected' : ''; ?>><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="city" class="required-sign">City:</label>
                <select id="city" name="city" class="form-control">
                    <option value="">Select City</option>
                    <?php
                    if (!empty($state_id)) {
                        $cities = $conn->query("SELECT id, city FROM cities WHERE state_id = $state_id");
                        while ($row = $cities->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?php echo $city_id == $row['id'] ? 'selected' : ''; ?>><?= $row['city'] ?></option>
                        <?php endwhile;
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="marks_range" class="required-sign">12th Marks Range (in %):</label>
                <select id="marks_range" name="marks_range" class="form-control">
                    <option value="">Select Marks Range</option>
                    <?php
                    $marks_ranges = $conn->query("SELECT id, CONCAT(marks_range_from, '-', marks_range_to, '%') AS range_label FROM marks_discount");
                    while ($row = $marks_ranges->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" <?php echo $marks_range_id == $row['id'] ? 'selected' : ''; ?>><?= $row['range_label'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="course" class="required-sign">Course Applied For:</label>
                <select id="course" name="course" class="form-control">
                    <option value="">Select Course</option>
                    <?php
                    $courses = $conn->query("SELECT id, name FROM courses");
                    while ($row = $courses->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" <?php echo $course_id == $row['id'] ? 'selected' : ''; ?>><?= $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
     
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="stream">Stream:</label>
                <select id="stream" name="stream" class="form-control">
                    <option value="">Select Stream</option>
                    <?php
                    if (!empty($course_id)) {
                        $streams = $conn->query("SELECT id, name FROM streams WHERE course_id = $course_id");
                        while ($row = $streams->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?php echo $stream_id == $row['id'] ? 'selected' : ''; ?>><?= $row['name'] ?></option>
                        <?php endwhile;
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="course_year">Course Year:</label>
                <input type="text" id="course_year" name="course_year" class="form-control" value="<?php echo $course_year; ?>" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="course_fee">Course Fee:</label>
                <input type="text" id="course_fee" name="course_fee" class="form-control" value="<?php echo $course_fee; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="discount">Discount (%):</label>
                <input type="text" id="discount" name="discount" class="form-control" value="<?php echo $discount_percent; ?>" readonly>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo empty($id) ? 'Submit' : 'Update'; ?></button>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Your existing JavaScript for form interactions goes here
        $('#state').change(function() {
            var state_id = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_cities.php',
                data: {state_id: state_id},
                dataType: 'json',
                success: function(response) {
                    $('#city').empty().append('<option value="">Select City</option>');
                    $.each(response, function(index, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                    <?php if (!empty($city_id)) echo "$('#city').val($city_id);"; ?>
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $('#course').change(function() {
            var course_id = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_streams.php',
                data: {course_id: course_id},
                dataType: 'json',
                success: function(response) {
                    $('#stream').empty().append('<option value="">Select Stream</option>');
                    $.each(response.streams, function(index, stream) {
                        $('#stream').append('<option value="' + stream.id + '">' + stream.name + '</option>');
                    });
                    $('#course_year').val(response.course_details.years + ' years');
                    $('#course_fee').val('₹ ' + response.course_details.fee);
                    <?php if (!empty($stream_id)) echo "$('#stream').val($stream_id);"; ?>
                    <?php if (!empty($course_year)) echo "$('#course_year').val('$course_year years');"; ?>
                    <?php if (!empty($course_fee)) echo "$('#course_fee').val('₹ $course_fee');"; ?>
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $('#marks_range').change(function() {
            var marks_range_id = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_discount.php',
                data: {marks_range_id: marks_range_id},
                dataType: 'json',
                success: function(response) {
                    $('#discount').val(response.discount_percent + '%');
                    <?php if (!empty($discount_percent)) echo "$('#discount').val('$discount_percent%');"; ?>
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>

</body>
</html>
