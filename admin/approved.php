<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bread Saver</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    <!-- sidebar -->
    <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-white shadow-sm sidebar" id="sidebar">
        <h1 class="bi text-primary d-flex justify-content-center">
            <img src="../pic/Breads Saver’s.png" alt="Logo" class="logo">
        </h1>
        <div class="list-group rounded-0">
            <a href="dashboard.php" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-border-all"></span>
                <span class="ml-2">Dashboard</span>
            </a>
            <a href="bakeries.php" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-shop"></span>
                <span class="ml-2">Bakeries</span>
            </a>

            <a href="users.php" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-person"></span>
                <span class="ml-2">Users</span>
            </a>

            <button
                class="list-group-item list-group-item-action active border-0 d-flex justify-content-between align-items-center"
                data-toggle="collapse" data-target="#sale-collapse">
                <div>
                    <span class="bi bi-shop-window"></span>
                    <span class="ml-2">Apply to Bakeshop</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="manage.php" class="list-group-item-action border-0 pl-5">Manage</a>
                    <a href="approved.php" class="list-group-item-action border-0 pl-5">Approved</a>
                    <a href="disapproved.php" class="list-group-item-action border-0 pl-5">Disapprove</a>
                </div>
            </div>

            <a href="products.php" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-box"></span>
                <span class="ml-2">Products</span>
            </a>

            <!-- <button
                class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center"
                data-toggle="collapse" data-target="#purchase-collapse">
                <div>
                    <span class="bi bi-cart-plus"></span>
                    <span class="ml-2">Purchase</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button> -->
            <div class="collapse" id="purchase-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Sellers</a>
                    <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Purchases</a>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->
        <nav class="w-100 d-flex px-4 py-2 mb-4 shadow-sm">
            <!-- close sidebar -->
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>
            <div class="dropdown ml-auto">
                <button class="btn py-0 d-flex align-items-center" id="logout-dropdown" data-toggle="dropdown"
                    aria-expanded="false">
                    <span class="bi bi-person h4"></span>
                    <span class="bi bi-chevron-down ml-1 mb-2 small"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="logout-dropdown">
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </div>
        </nav>


        <!-- main content -->
        <div class="container">
            <h2>Approved Bakeries</h2>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Bakeshop Name</th>
                        <th>Street/Building</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Business Permit</th>
                        <th>Health Permit</th>
                        <th>Bakeshop Picture</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="approved-bakeshops">
                    <!-- Rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">View Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Bakeshop Photo" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'fetch_bakeries.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        let rows = '';
                        data.forEach(function (bakeshop) {
                            rows += `<tr>
                                        <td>${bakeshop.id}</td>
                                        <td>${bakeshop.business_name}</td>
                                        <td>${bakeshop.bakeshop_address}</td>
                                        <td>${bakeshop.bakeshop_city}</td>
                                        <td>${bakeshop.bakeshop_province}</td>
                                        <td>${bakeshop.bakeshop_postal_code}</td>
                                        <td><a href="#" class="view-photo" data-photo="${bakeshop.business_permit_photo}">View</a></td>
                                        <td><a href="#" class="view-photo" data-photo="${bakeshop.health_permit_photo}">View</a></td>
                                        <td><a href="#" class="view-photo" data-photo="${bakeshop.bakeshop_photo}">View</a></td>
                                        <td>${bakeshop.status}</td>
                                    </tr>`;
                        });
                        $('#approved-bakeshops').html(rows);
                    } else {
                        $('#approved-bakeshops').html('<tr><td colspan="10">No results found.</td></tr>');
                    }

                    // Event listener for viewing photos in the modal
                    $('.view-photo').on('click', function (e) {
                        e.preventDefault();
                        const photoSrc = $(this).data('photo');
                        $('#modalImage').attr('src', photoSrc);
                        $('#photoModal').modal('show');
                    });
                },
                error: function () {
                    $('#approved-bakeshops').html('<tr><td colspan="10">Failed to load data.</td></tr>');
                }
            });
        });
    </script>

</body>

</html>