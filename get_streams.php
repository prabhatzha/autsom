<?php
include 'db.php';

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    $sql = "SELECT id, name FROM streams WHERE course_id = $course_id";
    $result = $conn->query($sql);

    $streams = array();
    while ($row = $result->fetch_assoc()) {
        $streams[] = $row;
    }

    $sql = "SELECT years, fee FROM courses WHERE id = $course_id";
    $result = $conn->query($sql);
    $course_details = $result->fetch_assoc();

    $response = array('streams' => $streams, 'course_details' => $course_details);

    echo json_encode($response);
}
?>
