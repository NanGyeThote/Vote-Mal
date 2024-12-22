<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voter_id = $conn->real_escape_string($_POST['voter_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO voters (voter_id, username, email, password, is_approved, status) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error); // Error handling for SQL preparation
    }
    
    $is_approved = 1; // Automatically set is_approved to 1
    $status = 'active'; // Default status to 'active'

    // Bind parameters
    $stmt->bind_param("ssssis", $voter_id, $username, $email, $password, $is_approved, $status);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Voter added successfully!'); window.location.href = 'voterlog.php';</script>";
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
