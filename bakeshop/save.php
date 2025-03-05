<?php
require "../backend/db.php"; // Ensure correct database connection

header('Content-Type: application/json'); // Set JSON response

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    // Collect and sanitize inputs
    $ingredientName = trim($_POST["ingredientName"] ?? '');
    $minStock = $_POST["minStock"] ?? null;
    $stockOnHand = $_POST["stockOnHand"] ?? null;
    $unit = trim($_POST["unit"] ?? '');
    $expirationDate = $_POST["expirationDate"] ?? null;
    $userId = $_POST["user_id"] ?? null; // Get user ID from session or form

    // Validate required fields
    if (empty($ingredientName)) {
        $errors[] = "Ingredient name is required.";
    }
    if (!is_numeric($minStock) || $minStock < 0) {
        $errors[] = "Minimum stock must be a positive number.";
    }
    if (!is_numeric($stockOnHand) || $stockOnHand < 0) {
        $errors[] = "Stock on hand must be a positive number.";
    }
    if (empty($unit)) {
        $errors[] = "Unit is required.";
    }
    if (!empty($expirationDate) && !strtotime($expirationDate)) {
        $errors[] = "Invalid expiration date.";
    }
    if (empty($userId) || !is_numeric($userId)) {
        $errors[] = "Invalid user ID.";
    }

    // Check if user ID exists in `bakeshop_applications`
    $userCheckQuery = "SELECT user_id FROM bakeshop_applications WHERE user_id = ?";
    $stmtUser = $conn->prepare($userCheckQuery);
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $result = $stmtUser->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid user ID. User does not exist."]);
        exit;
    }

    // Prepare SQL query with user_id
    $sql = "INSERT INTO ingredients_inventory (name, min_stock, stock_on_hand, unit, expiration_date, user_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
        exit;
    }

    // Bind parameters and execute
    $stmt->bind_param("siissi", $ingredientName, $minStock, $stockOnHand, $unit, $expirationDate, $userId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Ingredient added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    // Close resources
    $stmt->close();
    $stmtUser->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
