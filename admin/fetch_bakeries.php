<?php
// Database configuration
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch bakeshop applications
$sql = "SELECT id, business_name, bakeshop_address, bakeshop_city, bakeshop_province, bakeshop_postal_code, business_permit_photo, health_permit_photo, bakeshop_photo, status FROM bakeshop_applications";
$result = $conn->query($sql);

// Check if query was successful
if ($result === FALSE) {
    die("Query failed: " . $conn->error);
}

// Fetch data and encode it as JSON
$bakeries = array();
while ($row = $result->fetch_assoc()) {
    // Modify picture paths
    $row['business_permit_photo'] = '' . $row['business_permit_photo'];
    $row['health_permit_photo'] = '' . $row['health_permit_photo'];
    $row['bakeshop_photo'] = '' . $row['bakeshop_photo'];

    $bakeries[] = $row;
}

// Return JSON-encoded data
header('Content-Type: application/json');
echo json_encode($bakeries);

// Close the connection
$conn->close();
?>
