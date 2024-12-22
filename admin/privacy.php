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
    <title>Privacy Policy - Admin Dashboard</title>
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
            margin-bottom: 20px;
            color: #007b8e;
            font-size: 1.5em;
        }

        p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            color: #0056b3;
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
    <h1>Privacy Policy</h1>
    <p>Welcome to our online voting system. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy outlines how we collect, use, and safeguard your data.</p>
    
    <h2>1. Information We Collect</h2>
    <p>We may collect personal information such as your name, email address, and any other details necessary for the voting process. We also collect information about your voting activity and usage of our system.</p>

    <h2>2. How We Use Your Information</h2>
    <p>We use your personal information to administer the voting process, manage user accounts, and communicate with you regarding your votes and system updates. Your information may also be used for improving our services.</p>

    <h2>3. Data Security</h2>
    <p>We implement appropriate technical and organizational measures to protect your personal information from unauthorized access, use, or disclosure. Despite our efforts, no security measure is completely infallible, and we cannot guarantee the absolute security of your data.</p>

    <h2>4. Cookies and Tracking Technologies</h2>
    <p>Our system uses cookies and similar technologies to enhance your user experience and analyze site usage. You can control cookie settings through your browser preferences.</p>

    <h2>5. Third-Party Services</h2>
    <p>We may use third-party services to assist with certain functions, such as payment processing and analytics. These third parties are obligated to protect your data and use it only for the intended purposes.</p>

    <h2>6. Your Rights</h2>
    <p>You have the right to access, correct, or delete your personal information. You may also object to or restrict the processing of your data. To exercise these rights, please contact us at [your contact email].</p>

    <h2>7. Changes to This Privacy Policy</h2>
    <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page, and your continued use of our system constitutes your acceptance of the revised policy.</p>

    <h2>8. Contact Us</h2>
    <p>If you have any questions or concerns about this Privacy Policy, please contact us at [your contact email].</p>

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
<script>
    function toggleMenu() {
        var menu = document.getElementById('menu');
        menu.classList.toggle('open');
    }
</script>
</body>
</html>
