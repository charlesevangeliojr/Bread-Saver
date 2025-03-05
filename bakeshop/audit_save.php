<?php
// Include database connection
include('../backend/db.php');

// Start session to get the logged-in user ID
session_start();
$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the POST request
    $ingredient_id = $_POST['ingredientId'];
    $stock_on_hand = $_POST['stockOnHand'];
    $unit = $_POST['unit'];
    $expiration_date = $_POST['expirationDate'];
    $checked_date = $_POST['checkedDate'];
    $checked_time = $_POST['checkedTime'];
    $checked_by = $_POST['checkedBy'];
    $checked_datetime = $checked_date . ' ' . $checked_time; // Combine date and time for the checked_datetime

    // Begin the transaction
    mysqli_begin_transaction($conn);

    try {
        // Step 1: Get the ingredient name from ingredients_inventory
        $ingredientNameQuery = "SELECT name FROM ingredients_inventory WHERE id = ?";
        $ingredientNameStmt = mysqli_prepare($conn, $ingredientNameQuery);
        mysqli_stmt_bind_param($ingredientNameStmt, "i", $ingredient_id);
        mysqli_stmt_execute($ingredientNameStmt);
        mysqli_stmt_bind_result($ingredientNameStmt, $ingredient_name);
        mysqli_stmt_fetch($ingredientNameStmt);
        mysqli_stmt_close($ingredientNameStmt);

        if (!$ingredient_name) {
            throw new Exception("Ingredient not found.");
        }

        // Step 2: Insert the audit record into ingredient_audits table
        $insertAuditQuery = "INSERT INTO ingredient_audits (ingredient_id, stock_on_hand, unit, expiration_date, checked_datetime, checked_by, user_id, ingredient_name)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertAuditQuery);
        mysqli_stmt_bind_param($stmt, "iisssssi", $ingredient_id, $stock_on_hand, $unit, $expiration_date, $checked_datetime, $checked_by, $user_id, $ingredient_name);
        mysqli_stmt_execute($stmt);

        // Step 3: Update the ingredients_inventory table with the new stock_on_hand and expiration_date
        $updateInventoryQuery = "UPDATE ingredients_inventory 
                                 SET stock_on_hand = ?, expiration_date = ? 
                                 WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateInventoryQuery);
        mysqli_stmt_bind_param($updateStmt, "isi", $stock_on_hand, $expiration_date, $ingredient_id);
        mysqli_stmt_execute($updateStmt);

        // Commit the transaction if everything is successful
        mysqli_commit($conn);

        // Redirect back to the audit log page
        header("Location: nav.php?page=inventory.php");
        exit; // Ensure no further code is executed after the redirect
    } catch (Exception $e) {
        // If there is any error, rollback the transaction
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the prepared statements
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
        if (isset($updateStmt)) {
            mysqli_stmt_close($updateStmt);
        }
    }
} else {
    // If the form is not submitted
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
?>
