<?php
include '../backend/bakeshop_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bread Saver</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
    <link rel="stylesheet" href="../css/bakeshop_listing.css">
    <style>
        .list-group-item-action {
    text-decoration: none !important; /* Remove underline */
    color: #000; /* Set default text color */
    display: block; /* Ensure full width clickable */

}

.list-group-item-action:hover {
    background-color: #f8f9fa; /* Light hover effect */
    color: #007bff; /* Change color on hover */
    text-decoration: none !important; /* Ensure underline never appears */
}

    </style>
</head>
<body>
    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-white shadow-sm sidebar" id="sidebar">
        <h1 class="bi text-primary d-flex justify-content-center">
            <img src="../pic/Breads Saver’s.png" alt="Logo" class="logo">
        </h1>
        <div class="list-group rounded-0">
            <a href="#" class="list-group-item list-group-item-action border-0 load-page" data-page="bakeshop_dashboard.php">
                <span class="bi bi-plus-square"></span>
                <span class="ml-2">Manage Bread</span>
            </a>
        </div>
        <div class="list-group rounded-0">
            <a href="#sale-collapse" class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse">
                <span>
                    <span class="bi bi-plus-square"></span>
                    <span class="ml-2">Ingredients</span>
                </span>
                <span class="bi bi-chevron-down small"></span>
            </a>          
            <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                <div class="list-group">
                <a href="#" class="list-group-item-action border-0 pl-5 load-page" data-page="status.php">Status</a>
                    <a href="#" class="list-group-item-action border-0 pl-5 load-page" data-page="inventory.php">Inventory</a>
                    <a href="#" class="list-group-item-action border-0 pl-5 load-page" data-page="marketplace.php">Marketplace</a>
                    <a href="#" class="list-group-item-action border-0 pl-5 load-page" data-page="audithistory.php">Audit History</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- Top nav -->
        <nav class="w-100 d-flex px-4 py-2 mb-4 shadow-sm">
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>
            <div class="dropdown ml-auto">
                <button class="btn py-0 d-flex align-items-center" id="logout-dropdown" data-toggle="dropdown" aria-expanded="false">
                    <span class="bi bi-person h4"></span>
                    <a><?php echo htmlspecialchars($business_name); ?></a>
                    <span class="bi bi-chevron-down ml-1 mb-2 small"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="logout-dropdown">
                    <a class="dropdown-item" href="homepage.php" id="switch-to-bakeshop">
                        <i class="bi bi-arrow-repeat"></i> Switch to Bakeshop
                    </a>
                    <a class="dropdown-item" href="../backend/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div id="main-content" class="p-4">
            <h2>Loading...</h2>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="../js/admin_dashboard.js"></script>

    <script>
        $(document).ready(function () {
            function loadPage(page) {
                $("#main-content").html("<h2>Loading...</h2>"); // Show loading text
                $("#main-content").load(page);
                history.pushState(null, '', '?page=' + page);
            }

            // Load default page or check URL for stored page
            let urlParams = new URLSearchParams(window.location.search);
            let page = urlParams.get('page') || 'inventory.php';
            loadPage(page);

            // Handle sidebar clicks
            $(".load-page").click(function (e) {
                e.preventDefault();
                let page = $(this).data("page");
                loadPage(page);
            });

            // Handle back/forward browser buttons
            window.onpopstate = function () {
                let urlParams = new URLSearchParams(window.location.search);
                let page = urlParams.get('page') || 'inventory.php';
                loadPage(page);
            };
        });
    </script>
</body>
</html>
