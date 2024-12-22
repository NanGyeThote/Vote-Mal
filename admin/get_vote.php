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

// Initialize variables
$meeting_id = '';
$error_message = '';
$candidates = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the meeting ID from the form
    $meeting_id = $_POST['meeting_id'];

    // Retrieve candidates for the specified meeting ID
    $sql = "SELECT id, name, photo, age, position, description FROM candidates WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidates[] = $row;
        }
    } else {
        $error_message = "No candidates found for the meeting ID: $meeting_id";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Voting System</title>
    <link rel="stylesheet" href="css/vote_style.css">
</head>
<body>
    <div class="container">
        <h1>Vote</h1>

        <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <?php if (!empty($candidates)): ?>
        <form action="submit_vote.php" method="post">
            <input type="hidden" name="meeting_id" value="<?= htmlspecialchars($meeting_id) ?>">
            
            <h2>Select Candidate:</h2>
            <div class="candidates-list">
                <?php foreach ($candidates as $candidate): ?>
                <div class="candidate">
                    <label>
                        <input type="radio" name="candidate_id" value="<?= $candidate['id'] ?>" required>
                        <div class="candidate-info">
                            <img src="img/<?= $candidate['photo'] ?>" alt="<?= $candidate['name'] ?>">
                            <div>
                                <h3><?= $candidate['name'] ?></h3>
                                <p><?= $candidate['position'] ?></p>
                                <p><?= $candidate['age'] ?> years old</p>
                                <p><?= $candidate['description'] ?></p>
                            </div>
                        </div>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit">Submit Vote</button>
        </form>
        <?php endif; ?>

        <a href="voter_dashboard.php" class="button secondary">Back to Dashboard</a>
    </div>
</body>
</html>
