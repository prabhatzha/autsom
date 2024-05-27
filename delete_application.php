<!-- delete_application.php -->
<?php
include 'db.php';

if (isset($_POST['id'])) {
    $applicationId = $_POST['id'];
    
    // Perform deletion
    $sql = "DELETE FROM course_applications WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $applicationId);
    
    if ($stmt->execute()) {
        echo "Application deleted successfully";
    } else {
        echo "Error deleting application";
    }
} else {
    echo "Invalid request";
}
?>
