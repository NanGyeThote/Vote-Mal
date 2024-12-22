<?php
session_start(); // Start the session to access $_SESSION variables


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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve voter_id from session
    if (isset($_SESSION['voter_id'])) {
        $voter_id = $_SESSION['voter_id'];
        

        // Retrieve candidate_id and vote_id from form submission
        $candidate_id = $_POST['candidate_id'];
        $vote_id = $_SESSION['vote_id']; // Assuming vote_id is passed through hidden input in form

        // Check if the voter has already voted for this vote_id
        $check_sql = "SELECT * FROM votes_cast WHERE voter_id = ? AND vote_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $voter_id, $vote_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Voter has already voted for this vote_id
            echo "<p>You have already voted for this vote.</p>";
        } else {
            // Insert the vote into votes_cast table
            $insert_sql = "INSERT INTO votes_cast (vote_id, candidate_id, voter_id) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iis", $vote_id, $candidate_id, $voter_id);

            if ($insert_stmt->execute()) {
                // Vote successfully submitted
                echo "<p>Vote submitted successfully.</p>";

                // Redirect back to voter_dashboard.php after a short delay
                header("refresh:2; url=vote.php");
                echo "<p>Redirecting you back to dashboard...</p>";
            } else {
                echo "<p>Error submitting vote: " . $conn->error . "</p>";
            }
        }

        $check_stmt->close();
        $insert_stmt->close();
    } else {
        echo "<p>Session voter_id not set. Please log in again.</p>";
    }
}

$conn->close();
?>
