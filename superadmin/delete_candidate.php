<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "voting_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get candidate ID from POST request
$id = $_POST['id'];

// Delete candidate from database
$sql = "DELETE FROM candidates WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();

// Redirect back to the candidates list
header("Location: polls.php");
exit();
?>
