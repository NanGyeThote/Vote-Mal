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
    <title>F.A.Q. - Admin Dashboard</title>
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
            font-size: 25px;
        }

        h2 {
            margin-bottom: 15px;
            color: #007b8e;
            font-size: 20px;
        }

        .faq {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Enable vertical scrolling if necessary */
            max-height: 600px; /* Limit the height to fit the page */
        }

        .faq-item {
            margin-bottom: 20px;
        }

        .faq-question {
            font-weight: bold;
            margin-bottom: 5px;
            color: #007b8e;
            cursor: pointer;
            transition: color 0.3s;
        }

        .faq-question:hover {
            color: #0056b3;
        }

        .faq-answer {
            margin-left: auto;
            display: block;
            padding: 10px 0;
            border-top: 1px solid #ddd;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
        }

        .faq-answer.show {
            max-height: 200px; /* Adjust based on the content */
            padding: 10px 0;
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

        /* Button Styles */
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
    <div class="menu-toggle" onclick="toggleMenu()">
        <span>Home</span>
        <span>About</span>
        <span>Content</span>
    </div>
    <div class="faq">
        <h1>Frequently Asked Questions</h1>
        
        <div id="faqList">
            <div class="faq-item">
                <h2 class="faq-question">How do I create a new vote?</h2>
                <p class="faq-answer">To create a new vote, go to the "Create Vote" section of the Admin Dashboard. Fill out the required fields and submit the form to set up a new vote.</p>
            </div>
            <div class="faq-item">
                <h2 class="faq-question">How can I manage candidates?</h2>
                <p class="faq-answer">You can manage candidates by navigating to the "Manage Candidates" section. From there, you can add new candidates, edit existing ones, or remove candidates as needed.</p>
            </div>
            <div class="faq-item">
                <h2 class="faq-question">Where can I view the votes?</h2>
                <p class="faq-answer">To view the votes, go to the "View Votes" section. You will be able to see the results of all ongoing and completed votes here.</p>
            </div>
            <div class="faq-item">
                <h2 class="faq-question">How do I set a timer for voting?</h2>
                <p class="faq-answer">To set a timer, visit the "Set Timer" section. Enter the desired start and end times for the voting period and submit the form.</p>
            </div>
            <div class="faq-item">
                <h2 class="faq-question">How do I end the voting process?</h2>
                <p class="faq-answer">To end the voting process, go to the "End Voting" section. Select the meeting ID for which you want to end voting, and the system will close the voting and update the status accordingly.</p>
            </div>
        </div>

        <button onclick="showAddFaqForm()">Add New FAQ</button>

        <div id="addFaqForm" style="display: none;">
            <h2>Add New FAQ</h2>
            <form onsubmit="addFaq(event)">
                <label for="question">Question:</label>
                <input type="text" id="question" required>
                <label for="answer">Answer:</label>
                <textarea id="answer" required></textarea>
                <button type="submit">Add FAQ</button>
            </form>
        </div>
    </div>
    <a href="dashboard.php" class="button secondary">Back to Dashboard</a>
</div>

<!-- Success Message -->
<div class="success-message" id="successMessage">Operation Successful!</div>

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

    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            answer.classList.toggle('show');
        });
    });

    function showAddFaqForm() {
        const form = document.getElementById('addFaqForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function addFaq(event) {
        event.preventDefault();
        const question = document.getElementById('question').value;
        const answer = document.getElementById('answer').value;

        const faqList = document.getElementById('faqList');
        const faqItem = document.createElement('div');
        faqItem.classList.add('faq-item');
        faqItem.innerHTML = `
            <h2 class="faq-question">${question}</h2>
            <p class="faq-answer">${answer}</p>
        `;
        faqList.appendChild(faqItem);

        // Clear and hide the form
        document.getElementById('question').value = '';
        document.getElementById('answer').value = '';
        showAddFaqForm();
    }

    function showSuccessMessage(message) {
        const successMessage = document.getElementById('successMessage');
        successMessage.textContent = message;
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000); // Hide after 3 seconds
    }
</script>
</body>
</html>
