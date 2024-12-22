<?php
session_start(); // Start the session

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
    <title>Voting Poll - Admin Dashboard</title>
    <link rel="stylesheet" href="css/create_vote.css">
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
        h2{
            margin-bottom: 20px;
            color: #007b8e;
            font-size: 20px;
            text-align: center;
        }
        h3{
            margin-left: 20px;
            color: #007b8e;
            font-size: 1.5em;
            text-align: left;
            text-decoration-style: solid;
        }
        /* Lists */
        .current-votes, .results {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .current-votes li, .results li {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .current-votes li a, .results li a {
            color: #007b8e;
            text-decoration: none;
            margin-right: 10px;
        }

        /* Buttons */
        .delete-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #ff2e00;
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

        input[type="text"], textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
        }

        textarea {
            resize: vertical;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
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
            background-color: #5a6268;
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

            .menu.open ul li a {
                padding: 10px;
            }
        }

        /* Success Message Styling */
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #28a745; /* Green background */
            color: white; /* White text */
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            display: none; /* Hidden by default */
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Polls List Styling */
        .polls ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .polls ul li {
            font-size: 1.1em;
        }

        .polls ul li a {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .polls ul li a:hover {
            background-color: #34495e;
            color: #fff;
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
            <img src="img/magicstudio-art (2).jpg" alt="Admin Image">
            <h1>Create Voting Poll</h1>
        </div>
        <div class="menu-toggle" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="polls">
            <br>
            <h2>Voting Poll's Types</h2>
            <br>
            <ul>
                <li><a href="create_vote.php">Candidate Voting Poll</a></li>
                <li><a href="create_poll.php">Decision Voting Poll</a></li>
            </ul>
        </div>
        <br><br><br>
        <hr>
        <br>
        <h3>Current Votes</h3>
        <ul class="current-votes">
            <br>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "voting_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, title, meeting_id FROM votes WHERE id NOT IN (1, 2)";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li>
                            <a href='manage_candidates.php?vote_id=" . $row["id"] . "'>" . $row["title"] . "</a>
                            <span>(" . $row["meeting_id"] . ")</span>
                            <form method='POST' action='delete_vote.php' style='display:inline;'>
                                <input type='hidden' name='vote_id' value='" . $row["id"] . "'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                        </li>";
                }
            } else {
                echo "<li>No votes available</li>";
            }

            $conn->close();
            ?>
        </ul>

        <a href="index.php" class="button secondary">Back to Dashboard</a>

        <?php if ($success_message): ?>
        <div id="success-message" class="success-message"><?= $success_message ?></div>
        <script>
            var successMessage = document.getElementById('success-message');
            successMessage.style.display = 'block';
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 3000);
        </script>
        <?php endif; ?>
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
