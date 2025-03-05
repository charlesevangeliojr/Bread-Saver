<?php
// Database configuration
include('db.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and store input data
    $user_id = intval($_POST['user_id']);
    
    // Validate if the user_id exists in the users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // If user_id exists, proceed with the rest of the application
        $business_name = $conn->real_escape_string($_POST['business_name']);
        $bakeshop_address = $conn->real_escape_string($_POST['bakeshop_address']);
        $bakeshop_city = $conn->real_escape_string($_POST['bakeshop_city']);
        $bakeshop_province = $conn->real_escape_string($_POST['bakeshop_province']);
        $bakeshop_postal_code = $conn->real_escape_string($_POST['bakeshop_postal_code']);

        // Check for duplicate applications
        $stmt = $conn->prepare("SELECT id FROM bakeshop_applications WHERE business_name = ? AND bakeshop_address = ? AND bakeshop_city = ? AND bakeshop_province = ? AND bakeshop_postal_code = ?");
        $stmt->bind_param("sssss", $business_name, $bakeshop_address, $bakeshop_city, $bakeshop_province, $bakeshop_postal_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('An application with the same details already exists. Please check your information.'); window.location.href='register_bakeshop.php';</script>";
        } else {
            // Handle file uploads
            $business_permit_photo = $_FILES['business_permit_photo']['name'];
            $health_permit_photo = $_FILES['health_permit_photo']['name'];
            $bakeshop_photo = $_FILES['bakeshop_photo']['name'];

            $target_dir = "../pic/papers/";
            $business_permit_photo_path = $target_dir . basename($business_permit_photo);
            $health_permit_photo_path = $target_dir . basename($health_permit_photo);
            $bakeshop_photo_path = $target_dir . basename($bakeshop_photo);

            // Move uploaded files to the target directory
            move_uploaded_file($_FILES['business_permit_photo']['tmp_name'], $business_permit_photo_path);
            move_uploaded_file($_FILES['health_permit_photo']['tmp_name'], $health_permit_photo_path);
            move_uploaded_file($_FILES['bakeshop_photo']['tmp_name'], $bakeshop_photo_path);

            // SQL to insert data into bakeshop_applications table
            $stmt = $conn->prepare("INSERT INTO bakeshop_applications (user_id, business_name, bakeshop_address, bakeshop_city, bakeshop_province, bakeshop_postal_code, business_permit_photo, health_permit_photo, bakeshop_photo) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssssss", $user_id, $business_name, $bakeshop_address, $bakeshop_city, $bakeshop_province, $bakeshop_postal_code, $business_permit_photo_path, $health_permit_photo_path, $bakeshop_photo_path);
            
            if ($stmt->execute()) {
                echo "<script>alert('Application submitted successfully!'); window.location.href='../homepage.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        }
        
        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('Invalid User ID. Please ensure you\'re using a valid User ID.');</script>";
    }
}

// Close the connection
$conn->close();
?>

