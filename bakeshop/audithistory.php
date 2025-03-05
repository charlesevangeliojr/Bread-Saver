<?php
// Start the session to access the user data
session_start();

// Assuming you have a login system that stores the user ID in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: ../backend/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Include database connection
include '../backend/db.php';

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

// Check if the user entered a search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";

// Base query
$auditQuery = "SELECT a.*, i.name AS ingredient_name 
               FROM ingredient_audits a 
               JOIN ingredients_inventory i ON a.ingredient_id = i.id 
               WHERE a.user_id = ?";

$params = [$user_id];
$types = "i";

if (!empty($searchTerm)) {
    $auditQuery .= " AND (
        i.name LIKE ? OR 
        a.stock_on_hand LIKE ? OR 
        a.unit LIKE ? OR 
        a.expiration_date LIKE ? OR 
        a.checked_datetime LIKE ? OR 
        a.checked_by LIKE ?
    )";
    
    for ($i = 0; $i < 6; $i++) {
        $params[] = "%" . $searchTerm . "%";
        $types .= "s";
    }
}

// Order results by checked date
$auditQuery .= " ORDER BY a.checked_datetime DESC";

$stmt = $conn->prepare($auditQuery);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$auditLogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch ingredients for the modal list from ingredients_inventory table
$ingredientQuery = "SELECT * FROM ingredients_inventory WHERE user_id = ?";  // Fetching ingredients specific to the logged-in user
$stmt = $conn->prepare($ingredientQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ingredients = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Audit History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1 class="text-center">Audit History</h1>

    <!-- Single Search Bar -->
    <form method="GET" action="log.php" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" id="search" placeholder="Search..." 
                   value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="log.php" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <!-- Button to Open Modal -->
    <!-- <div class="text-end mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ingredientModal" onclick="openIngredientModal()">
        Audit Ingredient
      </button>
    </div> -->

    <!-- Modal for Ingredient List -->
    <div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ingredientModalLabel">Ingredient List</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Ingredient</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="ingredientListTableBody">
                <?php foreach ($ingredients as $ingredient): ?>
                  <tr>
                    <td><?= $ingredient['id'] ?></td>
                    <td><?= htmlspecialchars($ingredient['name']) ?></td>
                    <td><button class="btn btn-primary" onclick="selectIngredient(<?= $ingredient['id'] ?>, '<?= htmlspecialchars($ingredient['name']) ?>')">Select</button></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for Audit -->
    <!-- <div class="modal fade" id="ingredientAuditModal" tabindex="-1" aria-labelledby="ingredientAuditModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ingredientAuditModalLabel">Audit Ingredient</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="auditForm" method="POST" action="save.php">
              <div class="mb-3">
                <label for="ingredientName" class="form-label">Ingredient Name</label>
                <input type="text" class="form-control" id="ingredientName" name="ingredientName" required disabled>
              </div>
              <div class="mb-3">
                <label for="ingredientId" class="form-label">Ingredient ID</label>
                <input type="hidden" id="ingredientId" name="ingredientId" required>
              </div>
              <div class="mb-3">
                <label for="stockOnHand" class="form-label">Stock On Hand</label>
                <input type="number" class="form-control" id="stockOnHand" name="stockOnHand" required>
              </div>
              <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <input type="text" class="form-control" id="unit" name="unit" required>
              </div>
              <div class="mb-3">
                <label for="expirationDate" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expirationDate" name="expirationDate" required>
              </div>
              <div class="mb-3">
                <label for="checkedDate" class="form-label">Checked Date</label>
                <input type="date" class="form-control" id="checkedDate" name="checkedDate" required>
              </div>
              <div class="mb-3">
                <label for="checkedTime" class="form-label">Checked Time</label>
                <input type="time" class="form-control" id="checkedTime" name="checkedTime" required>
              </div>
              <div class="mb-3">
                <label for="checkedBy" class="form-label">Checked By</label>
                <input type="text" class="form-control" id="checkedBy" name="checkedBy" required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Audit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> -->

 <!-- Audit Table -->
   <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ingredient Name</th>
                <th>Stock on Hand</th>
                <th>Unit</th>
                <th>Expiration Date</th>
                <th>Checked Date | Time</th>
                <th>Checked By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="ingredientsTable">
            <?php if (!empty($auditLogs)): ?>
                <?php foreach ($auditLogs as $index => $log): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($log['ingredient_name']) ?></td>
                        <td><?= htmlspecialchars($log['stock_on_hand']) ?></td>
                        <td><?= htmlspecialchars($log['unit']) ?></td>
                        <td><?= htmlspecialchars($log['expiration_date']) ?></td>
                        <td><?= htmlspecialchars($log['checked_datetime']) ?></td>
                        <td><?= htmlspecialchars($log['checked_by']) ?></td>
                        <td>
                            
                            <button class="btn btn-danger btn-sm" onclick="deleteAudit(<?= $log['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No matching records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

  <!-- Success Modal -->
  <div class="modal fade" id="auditSuccessModal" tabindex="-1" aria-labelledby="auditSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 1%;">
      <div class="modal-content" style="background-color: #28a745; color: white;">
        <div class="modal-header justify-content-center" style="border-bottom: none;">
          <h5 class="modal-title" id="auditSuccessModalLabel">Audit Saved Successfully</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteAuditModal" tabindex="-1" aria-labelledby="deleteAuditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-center">
          <h5 class="modal-title text-center" id="deleteAuditModalLabel">Delete Audit Record</h5>
        </div>
        <div class="modal-body text-center">
          Are you sure you want to delete this audit record?
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

  <script>
    function applyFilter() {
      // Logic for applying the filter
      alert('Filter applied');
    }

    function clearFilter() {
      document.getElementById('filterForm').reset();
    }

    function deleteAudit(id) {
      // Show confirmation modal before deleting the audit record
      $('#deleteAuditModal').modal('show');
      document.getElementById('confirmDeleteBtn').onclick = function () {
        window.location.href = 'delete.php?id=' + id;
      };
    }

    function editAudit(id) {
      // Logic to edit audit can be implemented here (showing data in the audit modal)
      alert('Edit audit for ID ' + id);
    }

    function openIngredientModal() {
      // Logic to open the ingredient modal
    }

    function selectIngredient(id, name) {
      document.getElementById('ingredientName').value = name;
      document.getElementById('ingredientId').value = id;
      $('#ingredientModal').modal('hide');
      $('#ingredientAuditModal').modal('show');
    }
  </script>
</body>
</html>
