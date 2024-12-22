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

// Initialize an empty array to store vote results
$vote_results = [];

// Check if a meeting_id is submitted via the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['meeting_id'])) {
    // Sanitize the user input for meeting_id
    $meeting_id_input = $conn->real_escape_string($_POST['meeting_id']);

    // Fetch results for the specific voter_id and entered meeting_id
    $sql = "
        SELECT v.meeting_id, v.title, c.name AS candidate_name, 
               COUNT(vcc.candidate_id) AS vote_count,
               (SELECT COUNT(*) FROM votes_cast_candidate WHERE vote_id = v.id) AS total_votes
        FROM votes v
        INNER JOIN candidates c ON v.meeting_id = c.meeting_id
        LEFT JOIN votes_cast_candidate vcc ON vcc.candidate_id = c.id
                                         AND vcc.vote_id = v.id
                                         AND vcc.voter_id = ?
        WHERE v.meeting_id = ?
        GROUP BY v.meeting_id, c.id
        ORDER BY v.meeting_id, c.name
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $voter_id, $meeting_id_input); // Bind the voter_id and meeting_id parameters
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $meeting_id = $row['meeting_id'];
                if (!isset($vote_results[$meeting_id])) {
                    $vote_results[$meeting_id]['title'] = $row['title'];
                    $vote_results[$meeting_id]['candidates'] = [];
                }
                $vote_results[$meeting_id]['candidates'][] = [
                    'name' => htmlspecialchars($row['candidate_name']),
                    'vote_count' => (int)$row['vote_count'],
                    'total_votes' => (int)$row['total_votes'],
                    'percentage' => $row['total_votes'] > 0 ? ($row['vote_count'] / $row['total_votes']) * 100 : 0
                ];
            }
        } else {
            $_SESSION['error_message'] = "No results found for this meeting_id.";
        }
    } else {
        echo "<p>Error preparing the SQL statement.</p>";
    }

    $stmt->close();
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

        .vote-form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .vote-form input, .vote-form button {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .vote-form button {
            background-color: #2c3e50;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .vote-form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007b8e;
            color: white;
        }

        td {
            background-color: #fafafa;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #e0e0e0;
        }

        @media (max-width: 768px) {
            .vote-form {
                flex-direction: column;
            }

            .vote-form input, .vote-form button {
                width: 100%;
                margin-bottom: 10px;
            }

            table {
                display: block;
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
        .error-message {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            border: 1px solid #f5c6cb;
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
        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="error-message" id="error-message">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); // Clear the error message ?>
        <?php endif; ?>

        <div class="vote-form">
            <form method="post" action="">
                <label for="meeting_id">Enter Meeting ID:</label>
                <input type="text" id="meeting_id" name="meeting_id" required>
                <button type="submit">View Results</button>
            </form>
        </div>

        <br><br>
        <?php if (!empty($vote_results)) : ?>
            <?php foreach ($vote_results as $meeting_id => $data) : ?>
                <div class="vote-result">
                    <h2><?php echo htmlspecialchars($data['title']); ?></h2>
                    <br>
                    <table>
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Vote Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['candidates'] as $candidate) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($candidate['name']); ?></td>
                                    <td><?php echo $candidate['vote_count']; ?></td>
                                    <td><?php echo number_format($candidate['percentage'], 2); ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="view_result.php" class="button secondary">Back to View Results</a>

        <a href="index.php" class="button secondary">Back to Dashboard</a>

    </div>

    <script>
        // JavaScript to handle profile menu toggle
        document.querySelector('.profile-toggle-btn').addEventListener('click', function() {
            document.querySelector('.profile-menu').classList.toggle('show');
        });

        document.querySelector('.hamburger-btn').addEventListener('click', function() {
            const navbarCenter = document.querySelector('.navbar-center');
            navbarCenter.style.display = navbarCenter.style.display === 'flex' ? 'none' : 'flex';
        });
    </script>
    <script>
        function toggleProfileMenu() {
            var profileMenu = document.getElementById('profileMenu');
            profileMenu.classList.toggle('open');
        }

        function toggleHamburgerMenu() {
            var hamburgerMenu = document.getElementById('hamburgerMenu');
            hamburgerMenu.classList.toggle('open');
        }

        // Show success message and hide it after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 3000); // Hide the message after 3 seconds
            }
        });
        </script>
</body>
</html>
