<?php
// Start the session and enable error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "voting_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Approval Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = $_POST['voter_id'];
    $approve = $_POST['approve'];

    if ($approve == 'approve') {
        $stmt = $conn->prepare("UPDATE voters SET is_approved = 1 WHERE voter_id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM voters WHERE voter_id = ?");
    }

    $stmt->bind_param("s", $voter_id);
    $stmt->execute();
    $stmt->close();
}

$voters = $conn->query("SELECT * FROM voters WHERE is_approved = 0");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        h2 {
            margin-bottom: 20px;
            color: #007b8e;
            font-size: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #007b8e;
            color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
            transition: background-color 0.3s ease;
        }

        button[type="submit"][name="approve"][value="approve"] {
            background-color: #28a745;
        }

        button[type="submit"][name="approve"][value="approve"]:hover {
            background-color: #218838;
        }

        button[type="submit"][name="approve"][value="reject"] {
            background-color: #dc3545;
        }

        button[type="submit"][name="approve"][value="reject"]:hover {
            background-color: #c82333;
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

            button {
                font-size: 12px;
                padding: 6px 12px;
            }

            .button.secondary {
                padding: 8px;
                font-size: 14px;
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
        <img src="img/approval_vt.jpg" alt="Admin Image">
        <h1>Pending Voter Approvals</h1>
    </div>
    <div class="menu-toggle" onclick="toggleMenu()">
            <span>Home</span>
            <span>About</span>
            <span>Content</span>
    </div>

    <?php if ($voters->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Voter ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $voters->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['voter_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <div class="action-buttons">
                        <form method="POST" action="voter_approval.php">
                            <input type="hidden" name="voter_id" value="<?php echo $row['voter_id']; ?>">
                            <button type="submit" name="approve" value="approve">Approve</button>
                            <button type="submit" name="approve" value="reject">Reject</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <h2>No recent voter approvals pending.</h2>
    <?php } ?>

    <a href="index.php" class="button secondary">Back to Dashboard</a>
</div>
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
