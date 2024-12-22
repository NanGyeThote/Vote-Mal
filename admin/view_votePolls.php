<?php
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
    <title>View Vote Polls - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
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
            display: none;
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
        h1, h2 {
            color: #2c3e50;
            text-align: center;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #007b8e;
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
        form {
            margin-top: 20px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #34495e;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007b8e;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        table tbody tr:nth-child(even) {
            background-color: #f4f4f4;
        }
        table td img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
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
                <li><a href="#">Profile Settings</a></li>
                <li><a href="#">Change Password</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="admin-profile">
        <img src="img/polling.jpeg" alt="Admin Image">
        <h1>View Voting Polls' Details</h1>
    </div>

    <?php
    // Database configuration
    $conn = new mysqli("localhost", "root", "", "voting_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['add_candidate'])) {
            $name = $_POST['name'];
            $meeting_id = $_POST['meeting_id'];
            $photo = $_FILES['photo']['name'];
            $target_file = "img/" . basename($photo);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

            $stmt = $conn->prepare("INSERT INTO candidates (name, meeting_id, photo, age, position, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $name, $meeting_id, $photo, $_POST['age'], $_POST['position'], $_POST['description']);

            if ($stmt->execute() === TRUE) {
                $title_stmt = $conn->prepare("SELECT title FROM votes WHERE meeting_id = ?");
                $title_stmt->bind_param("s", $meeting_id);
                $title_stmt->execute();
                $title_result = $title_stmt->get_result();
                $meeting_title = $title_result->fetch_assoc()['title'];

                $_SESSION['success_message'] = "New candidate added successfully to \"$meeting_title\".";
                header("Location: view_votePolls.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } elseif (isset($_POST['delete_candidate'])) {
            $candidate_id = $_POST['candidate_id'];

            $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
            $stmt->bind_param("i", $candidate_id);

            if ($stmt->execute() === TRUE) {
                $_SESSION['success_message'] = "Candidate deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error: " . $stmt->error;
            }
            header("Location: view_votePolls.php");
            exit();
        }
    }

    if (isset($_SESSION['success_message'])) {
        echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    }

    $votes_result = $conn->query("SELECT * FROM votes");

    if ($votes_result->num_rows > 0) {
        while ($vote = $votes_result->fetch_assoc()) {
            $meeting_id = $vote['meeting_id'];
            $vote_title = $vote['title'];
            echo "<br>";
            echo "<h2>Candidates for Vote: $vote_title</h2>";
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Photo</th><th>Age</th><th>Position</th><th>Description</th><th>Action</th></tr></thead>";
            echo "<tbody>";

            $candidates_stmt = $conn->prepare("SELECT * FROM candidates WHERE meeting_id = ?");
            $candidates_stmt->bind_param("s", $meeting_id);
            $candidates_stmt->execute();
            $candidates_result = $candidates_stmt->get_result();

            if ($candidates_result->num_rows > 0) {
                while ($row = $candidates_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td><img src='img/" . $row["photo"] . "' alt='" . $row["name"] . "'></td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["position"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>
                        <form action='manage_candidates.php' method='post' style='display:inline;'>
                            <input type='hidden' name='candidate_id' value='" . $row["id"] . "'>
                            <button type='submit' name='delete_candidate'>Delete</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No candidates available</td></tr>";
            }

            echo "</tbody></table>";
        }
    } else {
        echo "<p>No votes available.</p>";
    }

    $conn->close();
    ?>

    <a href="index.php" class="button secondary">Back to Dashboard</a>
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
    <script>
        function toggleMenu() {
            var menu = document.getElementById('menu');
            menu.classList.toggle('open');
        }
    </script>
</body>
</html>
