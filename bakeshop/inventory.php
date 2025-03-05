<?php 
session_start(); // Ensure session is started

include "../backend/db.php"; // Include the database connection


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingredients Inventory</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .inventory-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            padding: 10px;
            cursor: pointer;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .card-content { 
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <h1 class="text-center">Ingredients Inventory</h1>

        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addIngredientModal">Add New Ingredient</button>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            $result = $conn->query("SELECT * FROM ingredients_inventory ORDER BY expiration_date ASC");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col mb-3">
                            <div class="card" 
                                data-bs-toggle="modal" 
                                data-bs-target="#ingredientAuditModal" 
                                data-id="' . htmlspecialchars($row["id"]) . '" 
                                data-name="' . htmlspecialchars($row["name"]) . '" 
                                data-stock="' . htmlspecialchars($row["stock_on_hand"]) . '" 
                                data-unit="' . htmlspecialchars($row["unit"]) . '"
                                data-expiration="' . htmlspecialchars($row["expiration_date"]) . '">
                                
                                <h5 class="card-title">' . htmlspecialchars($row["name"]) . '</h5>
                                <div class="card-content">
                                    <p class="card-text"><strong>Min Stock:</strong> ' . htmlspecialchars($row["min_stock"]) . ' ' . htmlspecialchars($row["unit"]) . '</p>
                                    <p class="card-text"><strong>Stock on Hand:</strong> ' . htmlspecialchars($row["stock_on_hand"]) . ' ' . htmlspecialchars($row["unit"]) . '</p>
                                    <p class="card-text"><strong>Expiration Date:</strong> ' . htmlspecialchars($row["expiration_date"]) . '</p>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo "<p class='text-center text-muted'>No ingredients available.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Add Ingredient Modal -->
    <div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="addIngredientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIngredientModalLabel">Add New Ingredient</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form id="addIngredientForm">
                        <div class="mb-3">
                            <label for="ingredientName" class="form-label">Ingredient Name</label>
                            <input type="text" class="form-control" id="ingredientName" required>
                        </div>
                        <div class="mb-3">
                            <label for="minStock" class="form-label">Min Stock</label>
                            <input type="number" class="form-control" id="minStock" required>
                        </div>
                        <div class="mb-3">
                            <label for="stockOnHand" class="form-label">Stock on Hand</label>
                            <input type="number" class="form-control" id="stockOnHand" required>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="unit" required>
                        </div>
                        <div class="mb-3">
                            <label for="expirationDate" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control" id="expirationDate" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveIngredientBtn">Save Ingredient</button>
                </div>
            </div>
        </div>
    </div>

<!-- Audit Modal -->
<div class="modal fade" id="ingredientAuditModal" tabindex="-1" aria-labelledby="ingredientAuditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ingredientAuditModalLabel">Audit Ingredient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="auditForm">
                    <!-- Ingredient ID (Hidden) -->
                    <input type="hidden" id="ingredientId" name="ingredientId">

                    <!-- Ingredient Name -->
                    <div class="mb-3">
                        <label for="ingredientName" class="form-label">Ingredient Name</label>
                        <input type="text" class="form-control" id="ingredientName" name="ingredientName" readonly>
                    </div>

                    <!-- Stock on Hand -->
                    <div class="mb-3">
                        <label for="stockOnHand" class="form-label">Stock On Hand</label>
                        <input type="number" class="form-control" id="stockOnHand" name="stockOnHand" required>
                    </div>

                    <!-- Unit -->
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>

                    <!-- Expiration Date -->
                    <div class="mb-3">
                        <label for="expirationDate" class="form-label">Expiration Date</label>
                        <input type="date" class="form-control" id="expirationDate" name="expirationDate" required>
                    </div>

                    <!-- Auto-filled Checked Date (Hidden) -->
                    <input type="hidden" id="checkedDate" name="checkedDate">
                    
                    <!-- Auto-filled Checked Time (Hidden) -->
                    <input type="hidden" id="checkedTime" name="checkedTime">

                    <!-- Checked By (User Manually Inputs) -->
                    <div class="mb-3">
                        <label for="checkedBy" class="form-label">Checked By</label>
                        <input type="text" class="form-control" id="checkedBy" name="checkedBy" placeholder="Enter your name" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Audit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#saveIngredientBtn").click(function () {
        let ingredientName = $("#ingredientName").val().trim();
        let minStock = $("#minStock").val().trim();
        let stockOnHand = $("#stockOnHand").val().trim();
        let unit = $("#unit").val().trim();
        let expirationDate = $("#expirationDate").val().trim();
        let userId = "<?php echo $_SESSION['user_id'] ?? ''; ?>"; // Fetch user ID from session

        // ✅ Validate Input
        if (!ingredientName || !minStock || !stockOnHand || !unit || !expirationDate) {
            alert("Please fill all fields correctly.");
            return;
        }

        let formData = {
            ingredientName: ingredientName,
            minStock: minStock,
            stockOnHand: stockOnHand,
            unit: unit,
            expirationDate: expirationDate,
            user_id: userId // Ensure user_id is passed
        };

        $.ajax({
            url: "save.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("An unexpected error occurred. Check the console for details.");
            }
        });
    });
});

</script>
<script>
$(document).ready(function () {
    // Open Audit modal and populate fields
    $(document).on("click", ".card", function () {
        const id = $(this).data("id");
        const name = $(this).data("name");
        const stock = $(this).data("stock");
        const unit = $(this).data("unit");
        const expiration = $(this).data("expiration");

        // Fill the modal fields
        $("#ingredientId").val(id);
        $("#ingredientName").val(name);
        $("#stockOnHand").val(stock);
        $("#unit").val(unit);
        $("#expirationDate").val(expiration);
        $("#checkedBy").val(""); // Ensure it's empty for new input

        // Auto-fill checked date and time
        const now = new Date();
        $("#checkedDate").val(now.toISOString().split('T')[0]); // YYYY-MM-DD
        $("#checkedTime").val(now.toTimeString().split(' ')[0]); // HH:MM:SS

        $("#ingredientAuditModal").modal("show");
    });

    // Handle Audit Form submission
    $("#auditForm").submit(function (event) {
        event.preventDefault();

        let checkedBy = $("#checkedBy").val().trim();
        if (checkedBy === "") {
            alert("Please enter your name in the 'Checked By' field.");
            return;
        }

        $.ajax({
            url: "audit_save.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    $("#ingredientAuditModal").modal("hide");
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("An unexpected error occurred. Check the console for details.");
            }
        });
    });
});

</script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
