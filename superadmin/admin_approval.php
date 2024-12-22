<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Approval Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $approve = $_POST['approve'];

    if ($approve == 'approve') {
        $stmt = $conn->prepare("UPDATE admin SET is_approved = 1 WHERE admin_id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
    }

    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all pending admin approvals
$voters = $conn->query("SELECT * FROM admin WHERE is_approved = 0");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
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

<div class="container">
<div class="admin-profile">
        <img src="img/approval_adm.jpg" alt="Admin Image">
        <h1>Pending Admin Approvals</h1>
    </div>
    <div class="menu-toggle" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
    </div>

    <?php if ($voters->num_rows > 0) { ?>
        <table>
            <tr>
                <th>Admin ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $voters->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['admin_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <div class="action-buttons">
                        <form method="POST" action="admin_approval.php">
                            <input type="hidden" name="admin_id" value="<?php echo $row['admin_id']; ?>">
                            <button type="submit" name="approve" value="approve">Approve</button>
                            <button type="submit" name="approve" value="reject">Reject</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No pending admin approvals at the moment.</p>
    <?php } ?>

    <a href="dashboard.php" class="button secondary">Back to Dashboard</a>
</div>
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
