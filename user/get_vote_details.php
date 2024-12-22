<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve meeting_id from POST request
$meeting_id = $_POST['meeting_id'];

// Fetch vote details
$vote_sql = "SELECT title, description FROM votes WHERE meeting_id = ?";
$vote_stmt = $conn->prepare($vote_sql);
$vote_stmt->bind_param("i", $meeting_id);
$vote_stmt->execute();
$vote_result = $vote_stmt->get_result();

$response = [];
if ($vote_result->num_rows > 0) {
    $vote_data = $vote_result->fetch_assoc();
    $response['title'] = $vote_data['title'];
    $response['description'] = $vote_data['description'];

    // Fetch candidates associated with the meeting_id
    $candidates_sql = "SELECT * FROM candidates WHERE meeting_id = ?";
    $candidates_stmt = $conn->prepare($candidates_sql);
    $candidates_stmt->bind_param("i", $meeting_id);
    $candidates_stmt->execute();
    $candidates_result = $candidates_stmt->get_result();

    $candidates = [];
    while ($candidate = $candidates_result->fetch_assoc()) {
        $candidates[] = $candidate;
    }
    $response['candidates'] = $candidates;
} else {
    $response['error'] = 'Meeting ID not found';
}

echo json_encode($response);

$conn->close();
?>
