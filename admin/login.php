<?php
// login.php

// Database credentials
$hostname = "localhost";
$username = "root";
$password = "";
$database = "breadsavers";

// Create a connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username and password from the form
$inputUsername = $_POST['username'];
$inputPassword = $_POST['password'];

// Prepare and execute the SQL query to fetch the user
$sql = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
if (!$sql) {
    die("SQL error: " . $conn->error);
}
$sql->bind_param("s", $inputUsername);
$sql->execute();
$sql->store_result();
$sql->bind_result($id, $storedPassword);

// Check if a user exists with the provided username
if ($sql->num_rows === 1) {
    $sql->fetch();
    
    // Direct password comparison (no hashing)
    if ($inputPassword === $storedPassword) {
        // Password is correct, start a session and redirect
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['admin_id'] = $id;
        header("Location: dashboard.php"); // Redirect to the admin dashboard
        exit();
    } else {
        // Password is incorrect
        echo "<script>
                alert('Invalid username or password.');
                window.location.href='index.php';
              </script>";
    }
} else {
    // Username not found
    echo "<script>
            alert('Invalid username or password.');
            window.location.href='index.php';
          </script>";
}

// Close the statement and connection
$sql->close();
$conn->close();
?>
