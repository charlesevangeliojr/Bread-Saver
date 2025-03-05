<?php
include 'config.php';
// SQL query to fetch user data
$sql = "SELECT id, first_name, last_name, gender, dob, phone, email, address FROM users";
$result = $conn->query($sql);
?>

<script>
function confirmDelete(event) {
    if (!confirm("Are you sure you want to delete this user?")) {
        event.preventDefault();
    }
}
</script>

<?php
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['first_name']}</td>
                            <td>{$row['last_name']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['dob']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['address']}</td>
                            <td>
                                <form action='delete_user.php' method='post' onsubmit='confirmDelete(event)'>
                                    <input type='hidden' name='user_id' value='{$row['id']}'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                          </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No results found</td></tr>";
}
?>

<?php
// Close the connection
$conn->close();
?>
