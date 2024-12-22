<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get vote ID from POST request
$vote_id = $_POST['vote_id'];

// Delete vote from the database
$sql = "DELETE FROM votes WHERE id = $vote_id";

if ($conn->query($sql) === TRUE) {
    echo "Vote deleted successfully";
} else {
    echo "Error deleting vote: " . $conn->error;
}

// Delete associated records
$sql = "DELETE FROM candidates WHERE meeting_id IN (SELECT meeting_id FROM votes WHERE id = $vote_id)";
$conn->query($sql);

$sql = "DELETE FROM vote_results WHERE vote_id = $vote_id";
$conn->query($sql);

$sql = "DELETE FROM timers WHERE vote_id = $vote_id";
$conn->query($sql);

$sql = "DELETE FROM votes_cast WHERE vote_id = $vote_id";
$conn->query($sql);

$conn->close();

// Redirect back to index.php
header("Location: polls.php");
exit();
?>
