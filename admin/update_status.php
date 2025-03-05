<?php
// Database configuration
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the required POST data is present
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // SQL query to update the status
        $sql_update_status = "UPDATE bakeshop_applications SET status='$status' WHERE id=$id";
        if ($conn->query($sql_update_status) !== TRUE) {
            throw new Exception("Failed to update bakeshop application status.");
        }

        if ($status == 'Approved') {
            // Get the user ID associated with the bakeshop application
            $sql_get_user_id = "SELECT user_id FROM bakeshop_applications WHERE id=$id";
            $result = $conn->query($sql_get_user_id);
            if ($result && $row = $result->fetch_assoc()) {
                $user_id = intval($row['user_id']);

                // Update the user's type to 'bakeshop'
                $sql_update_user = "UPDATE users SET user_type='bakeshop' WHERE id=$user_id";
                if ($conn->query($sql_update_user) !== TRUE) {
                    throw new Exception("Failed to update user type.");
                }
            } else {
                throw new Exception("User ID not found for the given bakeshop application.");
            }
        }

        // Commit the transaction
        $conn->commit();
        $response = array('success' => true);

    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        $response = array('success' => false, 'error' => $e->getMessage());
    }

    // Return JSON-encoded response
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // If required POST data is missing
    $response = array('success' => false, 'error' => 'Invalid request data.');
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the connection
$conn->close();
?>
