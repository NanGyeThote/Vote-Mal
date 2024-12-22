<?php
ob_start(); // Start output buffering
session_start();
// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide - Voting System</title>
    <link rel="stylesheet" href="css/create_vote.css">
    <style>
        /* Basic Reset */
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

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            color: #444;
            font-size: 2em;
        }

        h2 {
            margin-top: 20px;
            color: #007b8e;
            font-size: 1.5em;
        }

        p {
            margin-bottom: 15px;
            line-height: 1.6;
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
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">Voting System</div>
    <div class="navbar-center">
    <ul>
            <li><a href="user_poll.php">Vote</a></li>
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
        <div class="hamburger-menu" id="hamburgerMenu">
            <ul>
                <li><a href="#">Profile Settings</a></li>
                <li><a href="#">Change Password</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1>User Guide</h1>
    <p>Welcome to the Voting System User Guide. This guide will help you navigate through the various features of the system and provide instructions on how to use them effectively.</p>
    
    <h2>Create Vote</h2>
    <p>To create a new vote, navigate to the <strong>Create Vote</strong> section from the navigation bar. Fill out the required details including the vote title, description, and options. Once completed, click on the <strong>Create</strong> button to save your vote.</p>

    <h2>Manage Candidates</h2>
    <p>In the <strong>Manage Candidates</strong> section, you can add, edit, or remove candidates from the voting list. Use the provided forms to input candidate details and save your changes.</p>

    <h2>View Votes</h2>
    <p>To view the results of ongoing or completed votes, go to the <strong>View Votes</strong> section. Here you can see a list of votes and their results. You can filter and sort the votes based on various criteria to find the information you need quickly.</p>

    <h2>View Vote Polls</h2>
    <p>The <strong>View Polls</strong> feature allows you to view all active and completed voting polls. You can see detailed statistics, including the number of participants, current vote counts, and any additional comments or feedback. Navigate to the <strong>View Polls</strong> section from the navigation bar to access this information.</p>

    <h2>Set Timer</h2>
    <p>In the <strong>Set Timer</strong> section, you can set or modify the voting period. Ensure to set the timer according to your needs to avoid any discrepancies.</p>

    <h2>End Voting</h2>
    <p>To end a voting session, navigate to the <strong>End Voting</strong> section. You will be able to close the vote and finalize the results. Make sure all votes are accounted for before ending the session.</p>

    <a href="index.php" class="button secondary">Back to Dashboard</a>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    function toggleProfileMenu() {
        const profileMenu = document.getElementById("profileMenu");
        profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
    }

    function toggleHamburgerMenu() {
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        hamburgerMenu.style.display = hamburgerMenu.style.display === "block" ? "none" : "block";
    }
</script>
</body>
</html>
