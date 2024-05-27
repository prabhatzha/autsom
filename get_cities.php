<?php
include 'db.php';

if (isset($_POST['state_id'])) {
    $state_id = $_POST['state_id'];
    
    // Prepare the SQL statement with a placeholder
    $sql = "SELECT id, city FROM cities WHERE state_id = ?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    // Bind the state_id parameter to the placeholder
    $stmt->bind_param("i", $state_id);

    // Execute the query
    if ($stmt->execute()) {
        // Get result set
        $result = $stmt->get_result();

        // Fetch data into an associative array
        $cities = array();
        while ($row = $result->fetch_assoc()) {
            $cities[] = $row;
        }

        // Return cities data as JSON
        echo json_encode($cities);
    } else {
        // Handle execute() error
        die('Execute failed: ' . $stmt->error);
    }

    // Close statement
    $stmt->close();
} else {
    // Return empty array if state_id is not provided
    echo json_encode(array());
}
?>
