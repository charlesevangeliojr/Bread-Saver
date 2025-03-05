<?php
include ('backend/user_id_fetch.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bread Saver</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<body>
    <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
        <a class="navbar-brand" href="#">
            <img src="pic/Breads Saver’s.png" alt="Logo" class="logo">
            <span class="breadsaver">Bread Saver</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <?php echo htmlspecialchars($first_name); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i
                                    class="fas fa-user"></i> Profile</a></li>
                        <?php if ($status !== 'approved'): ?>
                            <li><a class="dropdown-item" href="register_bakeshop.php" id="apply-as-bakery"><i
                                        class="fas fa-shop"></i> Apply as bakery</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="bakeshop/nav.php" id="switch-to-bakeshop"><i
                                        class="bi bi-arrow-repeat"></i> Switch to Bakeshop</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i
                                    class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>

                <!-- Profile Details Modal -->
                <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="profileModalLabel">Profile Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="profile-photo-container">
                                    <img id="profile-photo-display"
                                        src="<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo"
                                        class="profile-photo">
                                    <input type="file" id="modal-profile-photo" name="profile_photo" accept="image/*"
                                        style="display: none;">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#updatePhotoModal">
                                        Change Photo
                                    </button>
                                </div>
                                <form id="profileDetailsForm" action="updateProfile.php" method="post"
                                    enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="modal-first-name">First Name</label>
                                        <input type="text" id="modal-first-name" name="first_name"
                                            value="<?php echo htmlspecialchars($first_name); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-middle-name">Middle Name</label>
                                        <input type="text" id="modal-middle-name" name="middle_name"
                                            value="<?php echo htmlspecialchars($middle_name); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-last-name">Last Name</label>
                                        <input type="text" id="modal-last-name" name="last_name"
                                            value="<?php echo htmlspecialchars($last_name); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-suffix">Suffix</label>
                                        <input type="text" id="modal-suffix" name="suffix"
                                            value="<?php echo htmlspecialchars($suffix); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-gender">Gender</label>
                                        <select id="modal-gender" name="gender" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="male" <?php if ($gender == 'male')
                                                echo 'selected'; ?>>Male
                                            </option>
                                            <option value="female" <?php if ($gender == 'female')
                                                echo 'selected'; ?>>
                                                Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-dob">Date of Birth</label>
                                        <input type="date" id="modal-dob" name="dob"
                                            value="<?php echo htmlspecialchars($dob); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-phone">Phone Number</label>
                                        <input type="tel" id="modal-phone" name="phone"
                                            value="<?php echo htmlspecialchars($phone); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-email">Email</label>
                                        <input type="email" id="modal-email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-address">Street Address</label>
                                        <input type="text" id="modal-address" name="address"
                                            value="<?php echo htmlspecialchars($address); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-city">City</label>
                                        <input type="text" id="modal-city" name="city"
                                            value="<?php echo htmlspecialchars($city); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-province">Province</label>
                                        <input type="text" id="modal-province" name="province"
                                            value="<?php echo htmlspecialchars($province); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-postal-code">Postal/Zip Code</label>
                                        <input type="text" id="modal-postal-code" name="postal_code"
                                            value="<?php echo htmlspecialchars($postal_code); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-username">Username</label>
                                        <input type="text" id="modal-username" name="username"
                                            value="<?php echo htmlspecialchars($username); ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="modal-password">Password</label>
                                        <input type="password" id="modal-password" name="password"
                                            value="<?php echo htmlspecialchars($password); ?>" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Photo Modal -->
                <div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-labelledby="updatePhotoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updatePhotoModalLabel">Update Profile Photo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="updateProfilePhoto.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <img id="preview" src="#" alt="Preview">
                                        <div>
                                            <label for="photo" class="form-label">Choose Photo</label>
                                            <input type="file" class="form-control" id="photo" name="photo"
                                                accept="image/*" required onchange="previewImage(event)">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Scripts -->
                <script>
                    function previewImage(event) {
                        var preview = document.getElementById('preview');
                        var file = event.target.files[0];
                        var reader = new FileReader();

                        reader.onload = function () {
                            preview.src = reader.result;
                            preview.style.display = 'block';
                        }

                        if (file) {
                            reader.readAsDataURL(file);
                        }
                    }
                </script>


                <!-- Logout Confirmation Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="logoutModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to log out?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <form action="backend/logout.php" method="post">
                                    <button type="submit" class="btn btn-primary">Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe"></i> EN
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="#">English</a></li>
                        <!-- Add more language options here -->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <!-- <a class="nav-link" href="#">
                        <i class="fas fa-shopping-basket"></i>
                    </a> -->
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i> Notification
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="#">English</a></li>
                        <!-- Add more language options here -->
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- main content -->
    <section id="hero">
        <img src="pic/bread-background-tljc4qelcpgo1ijv.jpg" alt="Background Image" class="background-img">
        <div class="searchPart">
            <div class="template searchContent">
                <h1 class="blackColor">Save bread, Save money</h1>
                <div class="search">
                    <input type="search" placeholder="Enter your street and house number">
                    <a href="homepage_listing.php" class="findPart">Find Breads</a>

                </div>
            </div>
        </div>
    </section>

    <script src="js/homepage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            // Ensure the "Apply as bakery" link is removed if the application is approved
            var status = "<?php echo $status; ?>"; // Pass PHP variable to JavaScript

            if (status === "approved") {
                $('#apply-as-bakery').remove();
            } else {
                $('#switch-to-bakeshop').remove(); // Ensure the "Switch to Bakeshop" link is not visible if not approved
            }

            // Retrieve last action from localStorage and navigate to it
            var lastAction = localStorage.getItem('lastAction');
            if (lastAction) {
                window.location.href = lastAction;
                localStorage.removeItem('lastAction'); // Remove the item after redirecting
            }

            // Handle link clicks to store the last action in localStorage
            $('#apply-as-bakery').click(function () {
                localStorage.setItem('lastAction', 'register_bakeshop.php');
            });

            $('#switch-to-bakeshop').click(function () {
                localStorage.setItem('lastAction', 'bakeshop/nav.php');
            });
        });
    </script>

</body>

</html>