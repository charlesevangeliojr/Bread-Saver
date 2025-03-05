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
            <a href="dashboard.php" class="list-group-item list-group-item-action active border-0">
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
                class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center"
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
        </div>
        
        <a href="products.php" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-box"></span>
                <span class="ml-2">Products</span>
            </a>

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
        <main class="p-4 min-vh-100">
            <section class="row">
                <div class="col-md-6 col-lg-4">
                    <!-- card -->
                    <article class="p-4 rounded shadow-sm border-left mb-4">
                        <a href="bakeries.php" class="d-flex align-items-center">
                            <span class="bi bi-shop h5"></span>
                            <h5 class="ml-2">Bakeries</h5>
                        </a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="p-4 rounded shadow-sm border-left mb-4">
                        <a href="users.php" class="d-flex align-items-center">
                            <span class="bi bi-person h5"></span>
                            <h5 class="ml-2">Users</h5>
                        </a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="p-4 rounded shadow-sm border-left mb-4">
                        <a href="#" class="d-flex align-items-center">
                            <span class="bi bi-box h5"></span>
                            <h5 class="ml-2">Products</h5>
                        </a>
                    </article>
                </div>
            </section>

            <div class="jumbotron jumbotron-fluid rounded border-0 shadow-sm ">
                <div class="container">
                    <h1 class="display-4 mb-2">Welcome Master</h1>
                    <p class="lead">Save Bread, Save Money</p>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="../js/admin_dashboard.js"></script>

</body>

</html>