<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard - Voting System</title>
    <link rel="stylesheet" href="css/index.css">
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

        .profile-toggle-btn .profile-logo {
            width: 30px; /* Adjust the size as needed */
            height: 30px; /* Adjust the size as needed */
            border-radius: 50%;
            object-fit: cover;
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
            width: 80%;
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
        p {
            color: #555;
            margin-bottom: 40px;
        }

        h2 {
            margin-bottom: 20px;
            color: #007b8e;
            font-size: 15px;
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
    <div class="navbar-brand">Superadmin Dashboard</div>
    <div class="navbar-center">
        <ul>
            <li><a href="adminlog.php">Admin Logs</a></li>
            <li><a href="voterlog.php">Voter Logs</a></li>
            <li><a href="admin_approval.php">Admin Approval</a></li>
            <li><a href="voter_approval.php">Voter Approval</a></li>
            <li><a href="polls.php">Voting Polls</a></li>
            <li><a href="vote_results.php">Voting Results</a></li>
            <li><a href="end_poll.php">End Polls</a></li>
        </ul>
    </div>
    <div class="profile-menu-wrapper">
        <button class="profile-toggle-btn" onclick="toggleProfileMenu()">
            <img src="img/superadmin.jpg" alt="Profile Logo" class="profile-logo"> <!-- Replace with the path to your mini logo image -->
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="profile-menu" id="profileMenu">
            <ul>
                <li><a href="login.php">Logout</a></li>
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

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h1>Welcome to the Superadmin Dashboard</h1>
        <div class="button-group">
            <div class="button-item">
                <img src="img/adminlog.jpg" alt="Admin Logs">
                <div class="text">Click the button to <b>manage and view admin logs</b></div>
                <a href="adminlog.php" class="button">Admin Logs</a>
            </div>
            <div class="button-item">
                <img src="img/voterlog.jpg" alt="Voter Logs">
                <div class="text">Click the button to <b>manage and view voter logs</b></div>
                <a href="voterlog.php" class="button">Voter Logs</a>
            </div>
            <div class="button-item">
                <img src="img/approval_adm.jpg" alt="Admin Approval">
                <div class="text">Click the button to <b>approve admins</b></div>
                <a href="admin_approval.php" class="button">Approve Admin</a>
            </div>
            <div class="button-item">
                <img src="img/approval_vt.jpg" alt="Voter Approval">
                <div class="text">Click the button to <b>approve voters</b></div>
                <a href="voter_approval.php" class="button">Approve Voter</a>
            </div>
            <div class="button-item">
                <img src="img/superpoll.jpg" alt="Vote Polls">
                <div class="text">Click the button to <b>manage and view voting polls</b></div>
                <a href="polls.php" class="button">Voting Polls</a>
            </div>
            <div class="button-item">
                <img src="img/superview.jpg" alt="View Results">
                <div class="text">Click the button to <b>view the voting results</b></div>
                <a href="vote_results.php" class="button">View Voting Results</a>
            </div>
            <div class="button-item">
                <img src="img/supertime.jpg" alt="End Voting">
                <div class="text">Click the button to <b>end the voting</b></div>
                <a href="end_poll.php" class="button">End Voting Polls</a>
            </div>
        </div>
    </div>
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
