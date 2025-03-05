<?php
// Start the session to check login status
session_start();

// Include the database connection file
include('../backend/db.php');


if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'bakeshop') {
    $user_id = $_SESSION['user_id'];

    // Query to fetch bakeshop details for the logged-in user
    $sql = "SELECT id, business_name FROM bakeshop_applications WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($bakeshop_id, $business_name);

        // Check if the query returns results
        if (!$stmt->fetch()) {
            $business_name = 'No bakeshop found for this user.';
            $bakeshop_id = 0;
        }
        $stmt->close();
    } else {
        // Log SQL preparation error
        error_log("SQL prepare error (bakeshop details): " . $conn->error);
        echo "Error retrieving bakeshop details.";
    }
} // <-- This is the missing closing brace

if (!isset($_SESSION['user_id'])) {
    echo '<p>You must be logged in to view this page.</p>';
    exit;
}

// Initialize search and category filter variables
$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
$categoryFilter = isset($_POST['categoryFilter']) ? $_POST['categoryFilter'] : '';

// SQL query to fetch ingredient data with supplier info and optional filtering
$sql = "SELECT s.SupplyID, s.IngredientName, s.Picture, s.Description, s.Stock, s.Category, s.PricePerUnit, s.Unit, s.SupplyDate, 
               m.first_name, m.last_name, m.company_name
        FROM Supplies s
        JOIN market_suppliers m ON s.SupplierID = m.supplier_id
        WHERE s.IngredientName LIKE '%$searchTerm%'";

// Add category filter if a category is selected
if ($categoryFilter != '') {
    $sql .= " AND s.Category = '$categoryFilter'";
}

$result = $conn->query($sql);

// Fetch all categories for the filter dropdown
$categoryQuery = "SELECT DISTINCT Category FROM Supplies";
$categoryResult = $conn->query($categoryQuery);

// Close the connection if needed
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for Cart Icon -->
</head>
<body>

<!-- Navbar -->
    <div class="container-fluid">
        <h1 class="text-center">Ingredients Marketplace</h1>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="cart.php">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container my-5">
    <!-- Search and Filter Form -->
    <form method="POST" action="marketplace.php" class="mb-4">
        <div class="row justify-content-center">
            <div class="col-auto">
                <input type="text" name="searchTerm" class="form-control" placeholder="Search for ingredients..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            </div>
            <div class="col-auto">
                <select name="categoryFilter" class="form-select">
                    <option value="">Select Category</option>
                    <?php
                    while ($row = $categoryResult->fetch_assoc()) {
                        $selected = ($row['Category'] == $categoryFilter) ? 'selected' : '';
                        echo "<option value='" . $row['Category'] . "' $selected>" . $row['Category'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <!-- Ingredient Cards -->
    <div class="row justify-content-center">
        <?php
        if ($result->num_rows > 0) {
            // Output data for each ingredient
            while($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 col-sm-6 mb-4">';
                echo '<div class="card">';
                echo '<img src="' . $row['Picture'] . '" alt="' . $row['IngredientName'] . '" class="card-img-top" style="height: 200px; object-fit: cover;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['IngredientName'] . '</h5>';
                echo '<p class="card-text">₱' . number_format($row['PricePerUnit'], 2) . ' per ' . $row['Unit'] . '</p>';
                echo '<p class="card-text">' . $row['Description'] . '</p>';
                echo '<p class="card-text"><small class="text-muted">Supplier: ' . $row['first_name'] . ' ' . $row['last_name'] . ' (' . $row['company_name'] . ')</small></p>';
                
                // Add to Cart Form
                echo '<form action="add_to_cart.php" method="POST">';
                echo '<input type="hidden" name="ingredient_id" value="' . $row['SupplyID'] . '">';
                echo '<input type="number" name="quantity" min="1" value="1" class="form-control mb-2" required>';
                echo '<button type="submit" class="btn btn-success">Add to Cart</button>';
                echo '</form>';

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p class='col-12 text-center'>No ingredients found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-B4gt1jrGC7Jh4AgT5D1tbjGJjwpjNW9E3QbSWeeYh6X+zFOvC3zA3rMMwAqOq8X4" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0c9L5K6TYkR2Y5mJ8g+pdZ4r0nxZON4zyBFSgF+G2mRUWJp1" crossorigin="anonymous"></script>
</body>
</html>
