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
if (isset($_POST['voter_id'])) {
    $voter_id = $_POST['voter_id'];

    // Prepare and execute the delete statement
    $sql = "DELETE FROM voters WHERE voter_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $voter_id);
    
    if ($stmt->execute()) {
        // Redirect back to the voter logs page with a success message
        header("Location: voterlog.php?message=Voter+deleted+successfully");
    } else {
        // Redirect back with an error message
        header("Location: voterlog.php?message=Error+deleting+voter");
    }
    
    $stmt->close();
} else {
    // Redirect back with an error message if no voter_id is provided
    header("Location: voterlog.php?message=No+voter+selected");
}

$conn->close();
?>
