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
    <title>Manage Candidates - Admin Dashboard</title>
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
            font-size: 2em;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 1em;
            color: #007b8e;
            margin-bottom: 20px;
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
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea {
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
            transition: background-color 0.3s;
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
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #2c3e50;
            color: #ecf0f1;
        }
        table tbody tr:nth-child(odd) {
            background-color: #f4f4f4;
        }
        @media (max-width: 768px) {
            .navbar ul {
                flex-direction: column;
                align-items: center;
            }
            .container {
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
            <img src="img/managing.jpeg" alt="Admin Image">
            <h1>Manage Candidates</h1>
            <h2>Complete the fields below to manage candidates</h2>
        </div>

        <div class="candidate">
            <h2>Add a New Candidate</h2>
            <?php
            // Database configuration
            $conn = new mysqli("localhost", "root", "", "voting_db");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['add_candidate'])) {
                    $name = $_POST['name'];
                    $meeting_id = $_POST['meeting_id'];
                    $photo = $_FILES['photo']['name'];
                    $age = $_POST['age'];
                    $position = $_POST['position'];
                    $description = $_POST['description'];

                    // Upload the photo
                    $target_file = "img/" . basename($photo);
                    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

                    // Insert the new candidate into the database
                    $stmt = $conn->prepare("INSERT INTO candidates (name, meeting_id, photo, age, position, description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssiss", $name, $meeting_id, $photo, $age, $position, $description);

                    if ($stmt->execute()) {
                        $meeting_title = $conn->query("SELECT title FROM votes WHERE meeting_id = '$meeting_id'")->fetch_assoc()['title'];
                        $_SESSION['success_message'] = "New candidate added successfully to \"$meeting_title\".";
                        header("Location: manage_candidates.php");
                        exit();
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } elseif (isset($_POST['delete_candidate'])) {
                    $candidate_id = $_POST['candidate_id'];
                    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
                    $stmt->bind_param("i", $candidate_id);

                    if ($stmt->execute()) {
                        echo "Candidate deleted successfully.";
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                }
            }

            if (isset($_SESSION['success_message'])) {
                echo "<div id='successMessage' class='success-message'>" . $_SESSION['success_message'] . "</div>";
                unset($_SESSION['success_message']);
            }
            ?>
            <form action="manage_candidates.php" method="post" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="meeting_id">Meeting ID:</label>
                <select id="meeting_id" name="meeting_id" required>
                    <?php
                    $votes_result = $conn->query("SELECT meeting_id, title FROM votes");
                    if ($votes_result->num_rows > 0) {
                        while($vote = $votes_result->fetch_assoc()) {
                            echo "<option value='" . $vote['meeting_id'] . "'>" . $vote['title'] . " (" . $vote['meeting_id'] . ")</option>";
                        }
                    } else {
                        echo "<option value=''>No meetings available</option>";
                    }
                    ?>
                </select>

                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required><br><br>
                
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
                
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                
                <button type="submit" name="add_candidate">Add Candidate</button>
            </form>
        </div>
        <a href="index.php" class="button secondary">Back to Dashboard</a>
    </div>

    <script>
        function toggleProfileMenu() {
            var profileMenu = document.getElementById('profileMenu');
            profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
        }

        // Hide profile menu when clicking outside
        document.addEventListener('click', function(event) {
            var profileMenu = document.getElementById('profileMenu');
            var profileToggleBtn = document.querySelector('.profile-toggle-btn');
            if (!profileToggleBtn.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.style.display = 'none';
            }
        });

        // Hide success message after 3 seconds
        window.onload = function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 3000);
            }
        };
    </script>
</body>
</html>