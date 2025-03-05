<?php
// session.php

session_start();

// Check if the admin session is set
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}
?>
