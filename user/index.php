<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['voter_id'])) {
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
    <title>Voter</title>
    <style>
    body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f0f4f8; /* Light icy background */
}

/* Navbar */
.navbar {
    background-color: #007b8e; /* Dark icy blue */
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
    font-weight: bold;
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
    background-color: #ffffff; /* Light icy white */
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
    color: #003366; /* Dark icy blue */
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
    background-color: rgb(32, 30, 30); /* Dark icy blue */
    padding: 40px 20px;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-top: auto;
    max-width: 100%;
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
    color: #007b8e; /* Light icy text */
    font-size: 18px;
    margin-bottom: 10px;
}

.footer p {
    color: #ffffff; /* Light icy text */
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
    color: #007b8e; /* Icy blue */
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
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
    color: #007b8e; /* Icy blue */
    font-size: 16px;
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
    background-color: #ffffff; /* Light icy white */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 40px;
    max-width: 800px;
    margin: 0 auto;
}

h1 {
    font-size: 28px;
    color: #007b8e; /* Dark icy blue */
    margin-bottom: 20px;
}

p {
    color: #555; /* Slightly muted for readability */
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
    background-color: #007b8e; /* Dark icy blue */
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
    color: #003366; /* Dark icy blue */
    margin-bottom: 20px;
    text-align: center;
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
    background-color: #ffffff; /* Light icy white */
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
    color: #003366; /* Dark icy blue */
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
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff; /* Light icy white */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.getting-started h2 {
    color: #007b8e; /* Dark icy blue */
    margin-bottom: 20px;
    text-align: center;
}

.getting-started .intro {
    font-size: 18px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #007b8e; /* Dark icy blue */
}

.getting-started .description {
    color: #555; /* Slightly muted for readability */
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
    background-color: #ffffff; /* Light icy white */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 20px;
}

.step-number {
    width: 30px;
    height: 30px;
    background-color: #007b8e; /* Dark icy blue */
    color: #fff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
    margin-right: 10px;
}

.step-content {
    padding: 20px;
}

.step-content h3 {
    color: #007b8e; /* Dark icy blue */
    margin-bottom: 10px;
}

.step-content p {
    color: #555; /* Slightly muted for readability */
}


    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">User Dashboard</div>
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
                <li><a href="register.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h1>Welcome to the User Dashboard</h1>
        <p>Believe your choice</p>
        <div class="button-group">
            <div class="button-item">
                <img src="img/voting.jpeg" alt="Vote">
                <div class="text">Click the button to <b>vote</b></div>
                <a href="user_poll.php" class="button">Vote</a>
            </div>
            <div class="button-item">
                <img src="img/candidatechoice.jpg" alt="View Vote Results">
                <div class="text">Click the button to <b>view your vote results</b></div>
                <a href="view_result.php" class="button">View vote results</a>
            </div> 
        </div>
    </div>
    <hr>
    <!--box-->
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
                    <li><a href="privacy.php">Privacy</a></li>
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
