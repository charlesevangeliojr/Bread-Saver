<?php
include('db.php');

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT id, password, user_type FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($id, $hashedPassword, $userType);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_type'] = $userType;

        // Redirect with success message
        echo "<script>
            alert('Login successful!');
            window.location.href='../homepage.php';
        </script>";
    } else {
        echo "<script>
            alert('Invalid username or password.');
            window.location.href='../index.html';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid username or password.');
        window.location.href='../index.html';
    </script>";
}

$stmt->close();
$conn->close();
?>
