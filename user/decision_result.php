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
$results = [];
$show_error_message = false;

// Handle form submission for viewing results
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_results'])) {
    $meeting_id = $_POST['meeting_id'];
    $meeting_id = $conn->real_escape_string($meeting_id); // Escape special characters

    // Query to get results based on meeting_id
    $sql = "SELECT v.title, v.description, 
                   SUM(CASE WHEN vc.candidate_id = 1 THEN 1 ELSE 0 END) AS yes_votes,
                   SUM(CASE WHEN vc.candidate_id = 2 THEN 1 ELSE 0 END) AS no_votes
            FROM votes v
            LEFT JOIN votes_cast vc ON v.id = vc.vote_id
            WHERE v.meeting_id = '$meeting_id'
            GROUP BY v.title, v.description";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_votes = $row['yes_votes'] + $row['no_votes'];
            $yes_percentage = $total_votes > 0 ? ($row['yes_votes'] / $total_votes) * 100 : 0;
            $no_percentage = $total_votes > 0 ? ($row['no_votes'] / $total_votes) * 100 : 0;

            $results[] = [
                'title' => $row['title'],
                'description' => $row['description'],
                'yes_votes' => $row['yes_votes'],
                'no_votes' => $row['no_votes'],
                'yes_percentage' => round($yes_percentage, 2),
                'no_percentage' => round($no_percentage, 2)
            ];
        }
    } else {
        $show_error_message = true; // Set flag to show error message
    }
}

$conn->close();
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

.error-message {
    color: red;
    text-align: center;
    margin-bottom: 20px;
    font-size: 16px;
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
    font-size: 16px;
}

.vote-form input[type="text"] {
    padding: 10px;
    width: 200px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-right: 10px;
    font-size: 14px;
}

.vote-form input[type="submit"] {
    padding: 10px 20px;
    background-color: #2c3e50;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.vote-form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Table styles */
.results-list {
    margin-top: 20px;
}

.results-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.results-list th, .results-list td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
    vertical-align: middle; /* Ensures better vertical alignment */
}

.results-list th {
    background-color: #007b8e;
    color: white;
    font-weight: bold;
}

.results-list tr:nth-child(even) {
    background-color: #f2f2f2;
}

.results-list tr:hover {
    background-color: #e0e0e0;
}

.results-list td {
    color: #333;
}

/* Buttons */
.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 20px;
}

.button:hover {
    background-color: #0056b3;
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
            <img src="img/yesorno.jpg" alt="vote">
            <h1>View Results</h1>
        </div>
        
    <?php if ($show_error_message): ?>
    <div class="error-message">
        No results found for the provided Meeting ID.
    </div>
    <?php endif; ?>

    <form method="post" class="vote-form">
        <label for="meeting_id">Meeting ID:</label>
        <input type="text" id="meeting_id" name="meeting_id" placeholder="Enter Meeting ID" required>
        <input type="submit" name="view_results" value="View Results">
    </form>

    <?php if (!empty($results)): ?>
    <div class="results-list">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Yes Votes</th>
                    <th>Yes Percentage</th>
                    <th>No Votes</th>
                    <th>No Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['title']); ?></td>
                    <td><?php echo htmlspecialchars($result['description']); ?></td>
                    <td><?php echo $result['yes_votes']; ?></td>
                    <td><?php echo $result['yes_percentage']; ?>%</td>
                    <td><?php echo $result['no_votes']; ?></td>
                    <td><?php echo $result['no_percentage']; ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
        <a href="view_result.php" class="button secondary">Back to View Results</a>
        <a href="index.php" class="button secondary">Back to Dashboard</a>
</div>

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
    </script>
</body>
</html>
