<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingredients Inventory</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link rel="stylesheet" href="css/bakeshop_listing.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .inventory-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
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
            margin-bottom: 15px;
            text-align: center;
        }
        .card-content { 
            display: table; 
            grid-template-columns: 1fr 1fr;  
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
    <h1 class="text-center">Ingredients Inventory</h1>

        <div class="text-left mb-3">
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addIngredientModal">Add New Ingredient</button>
        <table class="table table-bordered">
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <!-- Ingredient Card Template -->
            <div class="col mb-3">
                <div class="card" data-bs-toggle="modal" data-bs-target="#ingredientAuditModal" data-name="Flour">
                    <h5 class="card-title">Flour</h5>
                    <div class="card-content">
                        <p class="card-text"><strong>Min Stock:</strong> 5 kg</p>
                        <p class="card-text"><strong>Stock on Hand:</strong> 12 kg</p>
                        <p class="card-text"><strong>Unit:</strong> Kilogram</p>
                        <p class="card-text"><strong>Expiration Date:</strong> 2025-06-10</p>
                    </div>
                </div>
            </div>

            <div class="col mb-3">
                <div class="card" data-bs-toggle="modal" data-bs-target="#ingredientAuditModal" data-name="Sugar">
                    <h5 class="card-title">Sugar</h5>
                    <div class="card-content">
                        <p class="card-text"><strong>Min Stock:</strong> 3 kg</p>
                        <p class="card-text"><strong>Stock on Hand:</strong> 8 kg</p>
                        <p class="card-text"><strong>Unit:</strong> Kilogram</p>
                        <p class="card-text"><strong>Expiration Date:</strong> 2026-02-20</p>
                    </div>
                </div>
            </div>

            <div class="col mb-3">
                <div class="card" data-bs-toggle="modal" data-bs-target="#ingredientAuditModal" data-name="Milk">
                    <h5 class="card-title">Milk</h5>
                    <div class="card-content">
                        <p class="card-text"><strong>Min Stock:</strong> 2 L</p>
                        <p class="card-text"><strong>Stock on Hand:</strong> 5 L</p>
                        <p class="card-text"><strong>Unit:</strong> Liter</p>
                        <p class="card-text"><strong>Expiration Date:</strong> 2025-03-15</p>
                    </div>
                </div>
            </div>

            <div class="col mb-3">
                <div class="card" data-bs-toggle="modal" data-bs-target="#ingredientAuditModal" data-name="Eggs">
                    <h5 class="card-title">Eggs</h5>
                    <div class="card-content">
                        <p class="card-text"><strong>Min Stock:</strong> 10 pcs</p>
                        <p class="card-text"><strong>Stock on Hand:</strong> 30 pcs</p>
                        <p class="card-text"><strong>Unit:</strong> Pieces</p>
                        <p class="card-text"><strong>Expiration Date:</strong> 2025-04-01</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit modal -->
    <div class="modal fade" id="ingredientAuditModal" tabindex="-1" aria-labelledby="ingredientAuditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ingredientAuditModalLabel">Audit Ingredient</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="auditForm" method="POST">
                    <div class="mb-3">
                        <label for="auditingredientName" class="form-label">Ingredient Name</label>
                        <input type="text" class="form-control" id="auditingredientName" name="auditingredientName" required readonly>
                    </div>
                    <input type="hidden" id="ingredientId" name="ingredientId">

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
                        <input type="hidden" id="checkedDate" name="checkedDate">
                    </div>
                    <div class="mb-3">
                        <input type="hidden" id="checkedTime" name="checkedTime">
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

    <script>
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function() {
                document.getElementById('auditingredientName').value = this.getAttribute('data-name');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
