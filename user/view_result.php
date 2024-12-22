<?php
// Start the session and enable error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username and voter_id from the session
$username = $_SESSION['username'];
$voter_id = $_SESSION['voter_id']; // Fetch the voter_id from the session

// Database connection
$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
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

        h1 {
        color: #444;
        font-size: 2em;
        font-weight: bold;
    }

        .vote-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .vote-form label {
            margin-right: 10px;
            font-weight: bold;
        }

        .vote-form input[type="text"] {
            padding: 8px;
            width: 150px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .vote-form button {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .vote-form button:hover {
            background-color: #0056b3;
        }

        .vote-result {
            margin-bottom: 30px;
        }

        h2 {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #fafafa;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        .message-box {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: none; /* Hidden by default */
        }
        .results-links {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 20px;
        }

        .results-box {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .results-box:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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
            <img src="img/candidatechoice.jpg" alt="vote">
            <h1>View Results</h1>
        </div>
        <div class="results-links">
            <a href="candidate_result.php" class="results-box">Candidates' Result</a>
            <a href="decision_result.php" class="results-box">Decision Polls' Result</a>
        </div>
        <a href="index.php" class="button secondary">Back to Dashboard</a>

    </div>

    <script>
        // Show the no results message if applicable
        document.addEventListener('DOMContentLoaded', function() {
            var noResultsMessage = "<?php echo $no_results_message; ?>";
            if (noResultsMessage) {
                var messageBox = document.getElementById('message-box');
                var messageText = document.getElementById('message-text');

                messageText.textContent = noResultsMessage;
                messageBox.style.display = 'block';

                setTimeout(function() {
                    messageBox.style.display = 'none';
                }, 3000); // Hide after 3 seconds
            }
        });

        // Toggle profile menu
        document.querySelector('.profile-toggle-btn').addEventListener('click', function() {
            var profileMenu = document.querySelector('.profile-menu');
            profileMenu.classList.toggle('open');
        });

        // Toggle mobile menu
        document.querySelector('.hamburger-btn').addEventListener('click', function() {
            var navbarCenter = document.querySelector('.navbar-center');
            navbarCenter.classList.toggle('open');
        });
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
                }, 3000);
            }

            // Show error message and hide it after 3 seconds
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        });
        function toggleProfileMenu() {
            var profileMenu = document.getElementById('profileMenu');
            profileMenu.classList.toggle('open');
        }

        function toggleHamburgerMenu() {
            var hamburgerMenu = document.getElementById('hamburgerMenu');
            hamburgerMenu.classList.toggle('open');
        }
    </script>
</body>
</html>
