<?php
session_start(); // Start the session

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

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT admin_id, username, password, is_approved FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($admin_id, $username, $hashed_password, $is_approved);
    
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            if ($is_approved == 1) {
                // Set session variables
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['username'] = $username;

                // Redirect to user dashboard
                header("Location: index.php");
                exit();
            } else {
                echo "Your account is not approved yet. Please wait for approval.";
            }
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>
