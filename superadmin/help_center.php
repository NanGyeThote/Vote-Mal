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
    <title>Help Center - Admin Dashboard</title>
    <link rel="stylesheet" href="css/help_center.css">
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

        .faq {
            margin-bottom: 20px;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-question {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .faq-answer {
            margin-left: 20px;
        }

        .search-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .contact-info {
            margin-top: 30px;
        }

        .contact-info p {
            margin-bottom: 10px;
        }

        .contact-form {
            margin-top: 20px;
        }

        .contact-form label {
            display: block;
            margin-bottom: 5px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .contact-form button {
            background-color: #007b8e;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #005f6a;
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
        .live-chat {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007b8e;
            color: white;
            padding: 10px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 1000;
        }

        .live-chat i {
            font-size: 24px;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">Admin Dashboard</div>
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
            <span class="profile-name"><?php echo htmlspecialchars($username); ?></span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="profile-menu" id="profileMenu">
            <ul>
                <li><a href="#">Profile Settings</a></li>
                <li><a href="#">Change Password</a></li>
                <li><a href="#">Logout</a></li>
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
    <h1>Help Center</h1>

    <input type="text" class="search-box" placeholder="Search FAQs..." id="searchBox">

    <div class="faq">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-item">
            <p class="faq-question">How do I create a new vote?</p>
            <p class="faq-answer">To create a new vote, navigate to the "Create Vote" section from the admin dashboard and fill out the necessary details in the form provided.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">How can I manage candidates?</p>
            <p class="faq-answer">You can manage candidates by going to the "Manage Candidates" section. Here, you can add, edit, or remove candidates as needed.</p>
        </div>
        <div class="faq-item">
            <p class="faq-question">What should I do if there is a problem with voting?</p>
            <p class="faq-answer">If there are any issues with voting, please contact support through the contact form below, and we'll assist you as soon as possible.</p>
        </div>
    </div>

    <div class="contact-info">
        <h2>Contact Support</h2>
        <p>If you have any questions or need assistance, please fill out the form below.</p>
    </div>

    <div class="contact-form">
        <form id="contactForm">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <a href="dashboard.php" class="button secondary">Back to Dashboard</a>
</div>

<div class="success-message" id="successMessage">Message sent successfully!</div>
<div class="live-chat" onclick="openLiveChat()">
    <i class="fas fa-comments"></i>
</div>

<script>
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function toggleHamburgerMenu() {
        const menu = document.getElementById('hamburgerMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function showMessage(message, isSuccess) {
        const successMessageElement = document.getElementById('successMessage');
        successMessageElement.innerText = message;
        successMessageElement.style.backgroundColor = isSuccess ? '#28a745' : '#dc3545'; // Green for success, red for error
        successMessageElement.style.display = 'block';

        setTimeout(() => {
            successMessageElement.style.display = 'none';
        }, 3000); // Hide message after 3 seconds
    }

    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, true);
                document.getElementById('contactForm').reset(); // Reset the form fields
            } else {
                showMessage(data.message, false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An unexpected error occurred. Please try again.', false);
        });
    });

    function openLiveChat() {
        alert('Live chat feature is under development.');
    }
</script>

<!-- Include Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
