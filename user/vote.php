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

$show_success_message = false;
$show_error_message = false;
$show_poll_not_found_message = false;

// Handle form submission for voting
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['vote_submit'])) {
        $meeting_id = $_POST['meeting_id'];
        $candidate_id = $_POST['vote']; // Get the selected candidate's ID
        $meeting_id = $conn->real_escape_string($meeting_id);
        $candidate_id = $conn->real_escape_string($candidate_id);

        // Check if the voter has already voted for this meeting
        $sql_check = "SELECT * FROM votes_cast_candidate WHERE vote_id IN (SELECT id FROM votes WHERE meeting_id='$meeting_id') AND voter_id='$voter_id'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            $show_error_message = true; // Set flag to show error message
        } else {
            // Fetch the vote_id and status for the selected meeting_id
            $sql_vote = "SELECT * FROM votes WHERE meeting_id='$meeting_id'";
            $result_vote = $conn->query($sql_vote);
            if ($result_vote->num_rows > 0) {
                $row = $result_vote->fetch_assoc();
                $vote_id = $row['id'];
                $status = $row['status']; // Assuming there is a status field

                if ($status === 'ended') {
                    $show_poll_not_found_message = true;
                } else {
                    // Using prepared statement to insert the vote
                    $stmt = $conn->prepare("INSERT INTO votes_cast_candidate (vote_id, candidate_id, voter_id) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $vote_id, $candidate_id, $voter_id);

                    if ($stmt->execute()) {
                        $show_success_message = true; // Set flag to show success message
                    } else {
                        echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                }
            } else {
                $show_poll_not_found_message = true;
            }
        }
    }
}

// Search poll
$meeting_id = '';
$candidates = [];

if (isset($_POST['search_poll'])) {
    $meeting_id = $_POST['meeting_id'];

    $sql = "SELECT * FROM candidates WHERE meeting_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter</title>
    <link rel="stylesheet" href="css/view_votes.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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

    table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #007b8e;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
    }

    table td {
        background-color: #fff;
    }

    table tr:nth-child(even) td {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    table img {
        width: 100px;
        height: auto;
        border-radius: 5px;
    }

    button {
        padding: 5px;
        background-color: #007b8e;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    button:hover {
        background-color: #005f6a;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }


    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        form, table {
            width: 100%;
            padding: 15px;
        }

        table td img {
            width: 80px;
        }
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
            <h1>Vote</h1>
        </div>
        <?php if ($show_success_message): ?>
            <div id="success-message" class="success-message">
                Vote cast successfully!
            </div>
        <?php endif; ?>

        <?php if ($show_error_message): ?>
            <div id="error-message" class="error-message">
                You have already voted for this meeting.
            </div>
        <?php endif; ?>

        <?php if ($show_poll_not_found_message): ?>
            <div id="poll-not-found-message" class="error-message">
                Poll not found for the provided Meeting ID or the poll has ended.
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="text" id="meeting_id" name="meeting_id" value="<?php echo htmlspecialchars($meeting_id); ?>" placeholder="Enter Meeting ID" required>
            <button type="submit" name="search_poll">Search Poll</button>
        </form>
        <br><br><br>
        
        <?php if ($meeting_id && !empty($candidates)): ?>
        <h2>Candidates</h2>
        <form method="post" action="">
            <input type="hidden" name="meeting_id" value="<?php echo htmlspecialchars($meeting_id); ?>">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Position</th>
                    <th>Description</th>
                </tr>
                <?php foreach ($candidates as $candidate): ?>
                <tr>
                    <td>
                        <input type="radio" id="candidate_<?php echo $candidate['id']; ?>" name="vote" value="<?php echo $candidate['id']; ?>" required>
                    </td>
                    <td>
                    <?php if (!empty($candidate['photo']) && file_exists('img/' . $candidate['photo'])): ?>
                        <img src="img/<?php echo htmlspecialchars($candidate['photo']); ?>" alt="<?php echo htmlspecialchars($candidate['name']); ?>">
                    <?php else: ?>
                        <p>Image not available</p>
                    <?php endif; ?>

                    </td>
                    <td><?php echo htmlspecialchars($candidate['name']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['age']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['position']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['description']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit" name="vote_submit">Submit Vote</button>
        </form>
    <?php endif; ?>
        <a href="user_poll.php" class="button secondary">Back to voting polls</a>
        <br><br>
        <a href="index.php" class="button secondary">Back to Dashboard</a>
                    
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
            
            // Show poll not found message and hide it after 3 seconds
            const pollNotFoundMessage = document.getElementById('poll-not-found-message');
            if (pollNotFoundMessage) {
                setTimeout(() => {
                    pollNotFoundMessage.style.display = 'none';
                }, 3000);
            }
        });
        </script>
    </div>
</body>
</html>