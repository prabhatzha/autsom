<?php
include 'db.php';

if (isset($_POST['marks_range_id'])) {
    $marks_range_id = $_POST['marks_range_id'];
    
    // Fetch discount percentage from database based on marks range ID
    $sql = "SELECT discount_percent FROM marks_discount WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $marks_range_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $discount_percent = $row['discount_percent'];
    } else {
        $discount_percent = 0; // Default to 0% if no matching range is found
    }

    echo json_encode(array('discount_percent' => $discount_percent));
} else {
    echo json_encode(array('discount_percent' => 0)); // Default to 0% if marks_range_id is not set
}
?>
