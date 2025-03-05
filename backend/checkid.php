<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You must be logged in to apply.</p>";
        exit();
    }
    ?>