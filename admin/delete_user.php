<?php
// Database configuration
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete related records from other tables
        $delete_related_query = "DELETE FROM bakeshop_applications WHERE user_id = ?";
        $stmt = $conn->prepare($delete_related_query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->close();

        // Delete the user
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            // Commit transaction
            $conn->commit();
            // Redirect back to the users page with a success message
            header('Location: users.php?status=deleted');
        } else {
            // Rollback transaction
            $conn->rollback();
            // Redirect back to the users page with an error message
            header('Location: users.php?status=error');
        }

        $stmt->close();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        // Redirect back to the users page with an error message
        header('Location: users.php?status=error');
    }
}

$conn->close();
?>
