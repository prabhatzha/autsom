<?php
include 'db.php';

$query = "SELECT ca.id, ca.name, ca.email, ca.phone, s.name AS state_name, c.city AS city_name, ca.dob, CONCAT(marks_range_from, '-', marks_range_to, '%') AS marks_range, co.name AS course_name, st.name AS stream_name, ca.course_year, ca.course_fee, ca.discount_percent, ca.created_at 
          FROM course_applications ca
          INNER JOIN states s ON ca.state_id = s.id
          INNER JOIN cities c ON ca.city_id = c.id
          INNER JOIN marks_discount mr ON ca.marks_range_id = mr.id
          INNER JOIN courses co ON ca.course_id = co.id
          LEFT JOIN streams st ON ca.stream_id = st.id";

$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close(); // Close database connection after use
?>
