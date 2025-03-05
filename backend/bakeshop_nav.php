<?php
session_start();
include 'db.php';

// Initialize variables
$business_name = '';

// Check if user is logged in and is of type 'bakeshop'
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'bakeshop') {
    $user_id = $_SESSION['user_id'];

    // Query to fetch bakeshop details for the logged-in user
    $sql = "SELECT business_name FROM bakeshop_applications WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($business_name);
        $stmt->fetch();
        $stmt->close();
    } else {
        error_log("SQL prepare error (bakeshop details): " . $conn->error);
        echo "Error retrieving bakeshop details.";
    }
}

$conn->close();
?>