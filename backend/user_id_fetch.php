<?php
include ('profile.php');
include ('db.php'); // Make sure you have your database connection file included

// Ensure the session is started

// Fetch user application status from the database
$user_id = $_SESSION['user_id']; // Or however you store the user ID
$query = "SELECT status FROM bakeshop_applications WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();
?>