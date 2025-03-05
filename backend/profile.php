<?php
// Start the session
session_start();

// Include database configuration
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../index.html"); // Redirect to login page if not logged in
    exit();
}

// Retrieve user details from the database
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

$sql = "SELECT first_name, middle_name, last_name, suffix, gender, dob, phone, email, address, city, province, postal_code, username, profile_photo 
        FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $middle_name, $last_name, $suffix, $gender, $dob, $phone, $email, $address, $city, $province, $postal_code, $username, $profile_photo);
$stmt->fetch();
$stmt->close();
$conn->close();
?>