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
    <title>Admin Dashboard - Voting System</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        /* Global Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: white;
        }

        /* Navbar */
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

        /* Footer */
        .footer {
            background-color: #ffffff;
            padding: 40px 20px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: auto;
            background-color: rgb(32, 30, 30);
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-column {
            flex: 1;
            min-width: 250px;
            margin: 20px 10px;
        }

        .footer-title {
            color: #007b8e;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer p {
            color: white;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: #007b8e;
            text-decoration: none;
            font-size: 14px;
        }

        .footer ul li a:hover {
            text-decoration: underline;
        }

        .social-media {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-media img {
            width: 32px;
            height: 32px;
        }

        .footer-bottom {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }

        .footer-bottom p {
            color: #007b8e;
            font-size: 14px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Content */
        .content {
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 0 auto;
        }

        h1 {
            font-size: 28px;
            color: #007b8e;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-bottom: 40px;
        }

        /* Buttons */
        .button-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .button-item {
            background-color: #007b8e;
            border-radius: 10px;
            color: white;
            width: 250px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .button-item:hover {
            transform: translateY(-5px);
        }

        .button-item img {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }

        .button-item .text {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .button-item .button {
            background-color: #ff6b3c;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .button-item .button:hover {
            background-color: #ff4a1a;
        }

        /* Horizontal Rule */
        hr {
            border: 1px solid #ddd;
            margin: 40px 0;
        }

        /* Headings */
        h2 {
            color: #007b8e;
            margin-bottom: 20px;
            text-align: left;
            justify-content: space-between;
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
            width: 35%;
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
            background-color: #ff4a1a;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #ff2e00;
        }

        /* Getting Started Section */
        .getting-started {
            max-width: 100%;
            margin: auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .getting-started h2 {
            color: #007b8e;
            margin-bottom: 20px;
            text-align: center;
        }

        .getting-started .intro {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #007b8e;
        }

        .getting-started .description {
            color: #555;
            margin-bottom: 40px;
        }

        /* Steps Section */
        .steps {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .step {
            width: 30%;
            min-width: 250px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            background-color: #007b8e;
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            margin-right: 10px;
        }

        .step-details {
            display: inline-block;
            vertical-align: top;
        }

        .step-details h3 {
            margin: 0;
            color: #007b8e;
        }

        .step-details p {
            margin: 10px 0 0 0;
            color: #555;
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

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Choose from below to manage the voting system</p>
        <div class="button-group">
            <div class="button-item">
                <img src="img/magicstudio-art (2).jpg" alt="Create Vote">
                <div class="text">Click the button to <b>create the vote</b></div>
                <a href="votingPoll.php" class="button">Create Vote</a>
            </div>
            <div class="button-item">
                <img src="img/managing.jpeg" alt="Manage Candidates">
                <div class="text">Click the button to <b>manage candidates</b></div>
                <a href="manage_candidates.php" class="button">Manage Candidates</a>
            </div>
            <div class="button-item">
                <img src="img/polling.jpeg" alt="View Vote's Polls">
                <div class="text">Click the button to <b>view your created voting polls</b></div>
                <a href="view_votePolls.php" class="button">View Vote's Polls</a>
            </div>
            <div class="button-item">
                <img src="img/superview.jpg" alt="View Vote">
                <div class="text">Click the button to <b>view the vote</b></div>
                <a href="view_votes.php" class="button">View Vote</a>
            </div>
            <div class="button-item">
                <img src="img/approval_vt.jpg" alt="Voter Approval">
                <div class="text">Click the button to <b>approve the voters</b></div>
                <a href="voter_approval.php" class="button">Approve Voter</a>
            </div>
            <div class="button-item">
                <img src="img/1hour.jpg" alt="Set Timer">
                <div class="text">Click the button to <b>set the timer</b></div>
                <a href="set_timer.php" class="button">Set Timer</a>
            </div>
            <div class="button-item">
                <img src="img/supertime.jpg" alt="End Voting">
                <div class="text">Click the button to <b>end the voting</b></div>
                <a href="end_voting.php" class="button">End Voting</a>
            </div>
        </div>
    </div>
    <hr>
    <h2>Current Votes</h2>
    <ul class="current-votes">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "voting_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, title FROM votes WHERE id NOT IN (1, 2)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li>
                        <a href='manage_candidates.php?vote_id=" . $row["id"] . "'>" . $row["title"] . "</a>
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
    <hr>
</div>
<div class="getting-started">
        <h2>Getting Started</h2>
        <p class="intro">How to use Online Voting System in three simple steps</p>
        <p class="description">We have crafted our system to ensure it is both user-friendly and intuitive. Additionally, we continually enhance the process to create straightforward and aesthetically pleasing polls.</p>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-details">
                    <h3>Create Poll</h3>
                    <p>Choose a title, add a set of answer options and choose your preferred settings.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-details">
                    <h3>Add Candidates</h3>
                    <p>Choose the candidates name, photo, age, position and description and collect responses by sharing your poll link or embedding the poll on your site.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-details">
                    <h3>Get instant results</h3>
                    <p>As soon as a vote is cast, the results are updated in real-time and can be exported at any time.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3 class="footer-title">ONLINE VOTING SYSTEM</h3>
                <p>Creating instant, real-time polls and surveys has never been easier, and it's completely free.</p>
                <div class="social-media">
                    <img src="img/insta.png" alt="Instagram">
                    <img src="img/facebook.png" alt="Facebook">
                </div>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Support</h3>
                <ul>
                    <li><a href="help_center.php">Help Center</a></li>
                    <li><a href="guide.php">Guides</a></li>
                    <li><a href="faq.php">F.A.Q.</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Site</h3>
                <ul>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3 class="footer-title">Legal</h3>
                <ul>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2024 VoteMal. All rights reserved.</p>
        </div>
    </footer>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="js/script.js"></script>
<script>function toggleProfileMenu() {
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
