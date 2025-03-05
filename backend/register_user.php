<?php
include('db.php'); // Include your DB 

// Function to handle profile photo upload
function uploadProfilePhoto($file) {
    $targetDir = "../pic/profiles/";
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!empty($file["tmp_name"])) {
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            file_put_contents('upload_errors.log', "File is not an image\n", FILE_APPEND);
            return "File is not an image";
        }

        if ($file["size"] > 5000000) {
            file_put_contents('upload_errors.log', "File is too large\n", FILE_APPEND);
            return "File is too large";
        }

        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            file_put_contents('upload_errors.log', "Invalid file format\n", FILE_APPEND);
            return "Invalid file format";
        }

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            file_put_contents('upload_errors.log', "Error moving uploaded file\n", FILE_APPEND);
            return "Error moving uploaded file";
        }
    } else {
        return null;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $suffix = $_POST['suffix'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postalCode = $_POST['postal_code'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $userType = $_POST['user_type']; // Retrieve user_type from the form
    $profilePhoto = uploadProfilePhoto($_FILES['profile_photo']);

    if (strpos($profilePhoto, "<script>alert") === 0) {
        die($profilePhoto); // If there's an error message from the uploadProfilePhoto function
    }

    // Check if username already exists
    $usernameCheckSql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($usernameCheckSql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        echo "<script>alert('Username is already in use. Please use a different username.'); window.location.href='../register_user.php';</script>";
        exit();
    }
    $stmt->close();

    // Insert new user into the database
    $sql = "INSERT INTO users (first_name, middle_name, last_name, suffix, gender, dob, phone, email, address, city, province, postal_code, profile_photo, username, password, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("<script>alert('Error preparing statement'); window.location.href='../index.html';</script>" . $conn->error);
    }

    $stmt->bind_param("ssssssssssssssss", $firstName, $middleName, $lastName, $suffix, $gender, $dob, $phone, $email, $address, $city, $province, $postalCode, $profilePhoto, $username, $password, $userType);

    if ($stmt->execute()) {
        echo "<script>alert('Sign up successful!'); window.location.href='../index.html';</script>";
    } else {
        echo "Error!!!" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>