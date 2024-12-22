<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "voting_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin_id is set in POST request
if (isset($_POST['admin_id'])) {
    $admin_id = $_POST['admin_id'];

    // Prepare and execute the delete statement
    $sql = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    
    if ($stmt->execute()) {
        // Redirect back to the admin logs page with a success message
        header("Location: adminlog.php?message=Admin+deleted+successfully");
    } else {
        // Redirect back with an error message
        header("Location: adminlog.php?message=Error+deleting+admin");
    }
    
    $stmt->close();
} else {
    // Redirect back with an error message if no admin_id is provided
    header("Location: adminlog.php?message=No+admin+selected");
}

$conn->close();
?>
