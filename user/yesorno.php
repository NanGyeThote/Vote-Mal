<?php
// Database connection
session_start(); // Start the session

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username and voter_id from the session
$username = $_SESSION['username'];
$voter_id = $_SESSION['voter_id']; // Fetch the voter_id from the session

$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$poll_found = false;
$title = "";
$description = "";
$show_success_message = false;
$show_error_message = false; // Added for error message
$poll_ended_message = false; // Added for poll ended message
$show_poll_not_found_message = false; // Added for poll not found message

// Handle form submission for voting
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $meeting_id = $_POST['meeting_id'];
        $meeting_id = $conn->real_escape_string($meeting_id); // Escape special characters
        $sql = "SELECT * FROM votes WHERE meeting_id='$meeting_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title = $row['title'];
            $description = $row['description'];
            $status = $row['status']; // Assuming there's a status column

            if ($status == 'ended') {
                $poll_ended_message = true; // Set flag to show poll ended message
                $poll_found = false; // Ensure poll_found is false when ended
            } else {
                $poll_found = true;
                $show_poll_not_found_message = false; // Hide not found message if poll is found
            }
        } else {
            $show_poll_not_found_message = true; // Set flag to show poll not found message
        }
    } elseif (isset($_POST['vote_submit'])) {
        $meeting_id = $_POST['meeting_id'];
        $vote = $_POST['vote'];
        $meeting_id = $conn->real_escape_string($meeting_id); // Escape special characters
        $vote = $conn->real_escape_string($vote);

        // Check if the voter has already voted for this meeting
        $sql_check = "SELECT * FROM votes_cast WHERE vote_id IN (SELECT id FROM votes WHERE meeting_id='$meeting_id') AND voter_id='$voter_id'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            $show_error_message = true; // Set flag to show error message
        } else {
            $sql_vote = "SELECT * FROM votes WHERE meeting_id='$meeting_id'";
            $result_vote = $conn->query($sql_vote);
            if ($result_vote->num_rows > 0) {
                $row = $result_vote->fetch_assoc();
                $vote_id = $row['id'];
                $candidate_id = ($vote == 'yes') ? 1 : 2; // Assuming 1 for Yes and 2 for No in candidates table

                // Using prepared statement to insert vote
                $stmt = $conn->prepare("INSERT INTO votes_cast (vote_id, candidate_id, voter_id) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $vote_id, $candidate_id, $voter_id); // Use 's' for string parameter

                if ($stmt->execute()) {
                    $show_success_message = true; // Set flag to show success message
                } else {
                    echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p class='error-message'>Poll not found for the provided Meeting ID.</p>";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
    }

    .navbar {
        background-color: #007b8e;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .navbar-brand {
        font-size: 24px;
        font-weight: bold;
    }

    .navbar-center ul {
        list-style: none;
        display: flex;
    }

    .navbar-center ul li {
        margin: 0 15px;
    }

    .navbar-center ul li a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        padding: 8px 12px;
        transition: background-color 0.3s, color 0.3s;
    }

    .navbar-center ul li a:hover {
        background-color: #005f6a;
        border-radius: 5px;
    }

    .profile-menu-wrapper {
        position: relative;
    }

    .profile-toggle-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .profile-menu {
        display: none;
        position: absolute;
        top: 50px;
        right: 0;
        background-color: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    .profile-menu ul {
        list-style: none;
        padding: 10px 0;
    }

    .profile-menu ul li {
        padding: 10px 20px;
    }

    .profile-menu ul li a {
        color: #007b8e;
        text-decoration: none;
    }

    .profile-menu.open {
        display: block;
    }

    .hamburger-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 20px;
        display: none;
    }

    @media (max-width: 768px) {
        .navbar-center {
            display: none;
        }

        .hamburger-btn {
            display: block;
        }
    }

    .container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .admin-profile {
        text-align: center;
        margin-bottom: 20px;
    }

    .admin-profile img {
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    h1 {
        margin-bottom: 20px;
        color: #444;
        font-size: 2em;
        font-weight: bold;
    }
    
    h3 {
        display: block;
        font-size: 1.17em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        unicode-bidi: isolate;
    }

    .votes {
        margin-bottom: 20px;
    }

    a.button {
        background-color: #6c757d;
        padding: 10px;
        width: 100%;
        color: #fff;
        text-align: center;
        display: inline-block;
        text-decoration: none;
    }

    a.button:hover {
        background-color: #0056b3;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .description {
        font-size: 0.9em;
        color: #555;
    }
    
    .vote-button {
        padding: 10px;
        background-color: #007b8e;
        color: white;
        border: none;
        cursor: pointer;
    }

    .vote-form {
        display: flex;
        align-items: center;
        gap: 10px; /* Space between input and button */
        margin-bottom: 30px;
    }

    .vote-form label {
        margin-bottom: 0;
        color: #666;
        font-weight: bold;
        margin-right: 10px;
        font-size: 14px; /* Slightly smaller font size */
    }

    .vote-form input[type="text"] {
        width: 200px; /* Fixed width for alignment */
        padding: 8px;
        font-size: 14px; /* Slightly smaller font size */
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .vote-form input[type="submit"] {
        padding: 8px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px; /* Slightly smaller font size */
        transition: background-color 0.3s ease;
    }

    .vote-form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .radio-group {
        display: flex;
        justify-content: center; /* Center the items horizontally */
        align-items: center;
        gap: 15px;
    }

    .radio-group input[type="radio"] {
        margin: 0;
    }

    .radio-group label {
        margin: 0;
        font-size: 16px;
        color: #333;
    }


    .poll-info {
        margin-top: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">User Dashboard</div>
    <div class="navbar-center">
        <ul>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="view_result.php">View Vote Results</a></li>
        </ul>
    </div>
    <div class="profile-menu-wrapper">
        <button class="profile-toggle-btn" onclick="toggleProfileMenu()">
            <span class="profile-name"><?php echo htmlspecialchars($username); ?></span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="profile-menu" id="profileMenu">
            <ul>
                <li><a href="register.php">Logout</a></li>
            </ul>
        </div>
        <button class="hamburger-btn" onclick="toggleHamburgerMenu()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
<div class="container">
        <div class="admin-profile">
            <img src="img/yesorno.jpg" alt="vote">
            <h1>Vote</h1>

            <?php if ($show_success_message): ?>
            <div id="success-message" class="success-message">
                Vote cast successfully!
            </div>
        <?php endif; ?>

        <?php if ($show_error_message): ?>
            <div id="error-message" class="error-message">
                You have already voted for this meeting.
            </div>
        <?php endif; ?>

        <?php if ($poll_ended_message): ?>
            <div id="poll-ended-message" class="error-message">
                This poll has ended.
            </div>
        <?php endif; ?>

        <?php if ($show_poll_not_found_message): ?>
            <div id="poll-not-found-message" class="error-message">
                Poll not found for the provided Meeting ID.
            </div>
        <?php endif; ?>

        <form method="post" class="vote-form">
            <label for="meeting_id"></label>
            <input type="text" id="meeting_id" name="meeting_id" placeholder="Enter Meeting ID" required>
            <input type="submit" name="search" value="Search Poll">
        </form>

        <?php if ($poll_found && !$poll_ended_message): ?>
            <div class="poll-info">
                <h3><?php echo htmlspecialchars($title); ?></h3>
                <p><?php echo htmlspecialchars($description); ?></p>
            </div>
            <br>

            <form method="post" class="vote-form">
                <input type="hidden" name="meeting_id" value="<?php echo htmlspecialchars($meeting_id); ?>">
                <label for="vote">Your Vote:</label>
                <div class="radio-group">
                    <input type="radio" id="yes" name="vote" value="yes" required>
                    <label for="yes">Yes</label>
                    <input type="radio" id="no" name="vote" value="no" required>
                    <label for="no">No</label>
                </div>
                <input type="submit" name="vote_submit" value="Submit Vote">
            </form>
        <?php endif; ?>
    </div>
    <a href="user_poll.php" class="button secondary">Back to voting polls</a>
    <br><br><br>
    <a href="index.php" class="button secondary">Back to Dashboard</a>
</div>

<script>
    function toggleProfileMenu() {
        var profileMenu = document.getElementById('profileMenu');
        profileMenu.classList.toggle('open');
    }

    function toggleHamburgerMenu() {
        var hamburgerMenu = document.getElementById('hamburgerMenu');
        hamburgerMenu.classList.toggle('open');
    }

    // Show success message and hide it after 3 seconds
    window.addEventListener('DOMContentLoaded', (event) => {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });
</script>
</body>
</html>