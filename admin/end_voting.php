<?php
// Database connection
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username and admin_id from the session
$username = $_SESSION['username'];
$admin_id = $_SESSION['admin_id'];

$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$meeting_id = '';
$success_message = '';
$error_message = '';

// Fetch meeting IDs for the dropdown menu
$meeting_ids = [];
$fetchMeetingIdsSql = "SELECT DISTINCT meeting_id FROM votes";
$result = $conn->query($fetchMeetingIdsSql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meeting_ids[] = $row['meeting_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_id = $conn->real_escape_string($_POST['meeting_id']);

    // Start a transaction
    $conn->begin_transaction();
    try {
        // Step 1: Update status in the `votes` table
        $updateStatusSql = "UPDATE votes SET status='ended' WHERE meeting_id='$meeting_id'";
        if (!$conn->query($updateStatusSql)) {
            throw new Exception("Error updating status: " . $conn->error);
        }

        // Commit the transaction if everything is successful
        $conn->commit();
        $success_message = "Voting ended successfully for meeting ID: $meeting_id";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $error_message = "Error ending voting: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End Voting - Admin Dashboard</title>
    <link rel="stylesheet" href="css/end_voting.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar {
            background-color: #007b8e;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 24px;
            color: white;
        }

        .navbar-center ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .navbar-center ul li {
            margin: 0 15px;
        }

        .navbar-center ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
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

        .profile-toggle-btn .profile-name {
            margin-right: 5px;
        }

        .profile-menu, .hamburger-menu {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-menu ul, .hamburger-menu ul {
            list-style: none;
            margin: 0;
            padding: 10px 0;
        }

        .profile-menu ul li, .hamburger-menu ul li {
            padding: 10px 20px;
        }

        .profile-menu ul li a, .hamburger-menu ul li a {
            color: #007b8e;
            text-decoration: none;
        }

        .hamburger-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 20px;
            display: none; /* Hide by default, show on mobile view */
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: block;
            }

            .navbar-center ul {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 800px;
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
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        h1 {
            margin-bottom: 20px;
            color: #444;
            font-size: 2em;
        }

        .menu-toggle {
            display: none; /* Hide for larger screens */
        }

        .menu {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .menu ul {
            display: flex;
            gap: 20px;
            list-style-type: none;
        }

        .menu ul li {
            display: inline;
        }

        .menu ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            padding: 10px;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .menu ul li a:hover {
            color: #0056b3;
            border-bottom: 2px solid #0056b3;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        select, input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #2c3e50;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .button.secondary {
            background-color: #6c757d;
            padding: 10px;
            width: 100%;
            color: #fff;
            text-align: center;
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
        }

        .button.secondary:hover {
            background-color: #0056b3;
        }

        .success-message, .error-message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .menu {
                display: none; /* Hide the menu for small screens */
            }

            .menu-toggle {
                display: flex;
                justify-content: center;
                background-color: #007bff;
                color: #fff;
                padding: 10px;
                cursor: pointer;
            }

            .menu-toggle span {
                display: block;
                margin: 5px;
            }

            .menu.open {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .menu.open ul {
                flex-direction: column;
                gap: 10px;
            }

        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">Admin Dashboard</div>
    <div class="navbar-center">
    <ul>
            <li><a href="votingPoll.php">Create Vote</a></li>
            <li><a href="manage_candidates.php">Manage Candidates</a></li>
            <li><a href="view_votePolls.php">Vote Polls</a></li>
            <li><a href="view_votes.php">View Votes</a></li>
            <li><a href="voter_approval.php">Approve Voter</a></li>
            <li><a href="set_timer.php">Set Timer</a></li>
            <li><a href="end_voting.php">End Voting</a></li>
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
        <div class="hamburger-menu" id="hamburgerMenu">
            <ul>
                <li><a href="register.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="admin-profile">
        <img src="img/supertime.jpg" alt="Admin Image">
        <h1>End Voting</h1>
    </div>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <?php if ($success_message): ?>
    <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="meeting_id">Select Meeting ID</label>
        <select id="meeting_id" name="meeting_id" required>
            <option value="" disabled selected>Select Meeting ID</option>
            <?php foreach ($meeting_ids as $id): ?>
                <option value="<?php echo htmlspecialchars($id); ?>"><?php echo htmlspecialchars($id); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">End Voting</button>
    </form>
    <a href="index.php" class="button secondary">Back to Dashboard</a>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="js/script.js"></script>
<script>
    function toggleProfileMenu() {
        const profileMenu = document.getElementById("profileMenu");
        profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
    }

    function toggleHamburgerMenu() {
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        hamburgerMenu.style.display = hamburgerMenu.style.display === "block" ? "none" : "block";
    }

    function toggleMenu() {
        var menu = document.getElementById('menu');
        menu.classList.toggle('open');
    }
</script>
</body>
</html>
