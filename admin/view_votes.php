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
// Fetch all candidates for votes without filtering by voter_id
$sql = "
    SELECT v.meeting_id, v.title, c.name AS candidate_name, 
    COUNT(vcc.candidate_id) AS vote_count,
    (SELECT COUNT(*) FROM votes_cast_candidate WHERE vote_id = vcc.vote_id) AS total_votes
    FROM votes v
    INNER JOIN candidates c ON v.meeting_id = c.meeting_id
    LEFT JOIN votes_cast_candidate vcc ON vcc.candidate_id = c.id
    AND vcc.vote_id = v.id
    GROUP BY v.meeting_id, c.id
    ORDER BY v.meeting_id
";

$result = $conn->query($sql);

$vote_results = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meeting_id = $row['meeting_id'];
        $vote_results[$meeting_id]['title'] = $row['title'];
        $vote_results[$meeting_id]['candidates'][] = [
            'name' => $row['candidate_name'],
            'vote_count' => $row['vote_count'],
            'total_votes' => $row['total_votes'],
            'percentage' => $row['total_votes'] > 0 ? ($row['vote_count'] / $row['total_votes']) * 100 : 0
        ];
    }
} else {
    echo "<p>No results found.</p>";
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
        /* General styles */
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
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
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
            background-color: #3498db;
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
        .vote-result {
            margin-bottom: 30px;
        }

        h2 {
            background-color: #007b8e;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
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
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            background-color: #fafafa;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
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

        .button:hover {
            background-color: #0056b3;
        }

        /* Profile menu */
        .profile-menu-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .profile-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .profile-menu.open {
            display: block;
        }

        .profile-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .profile-menu ul li {
            margin-bottom: 10px;
        }

        .profile-menu ul li a {
            color: #007b8e;
            text-decoration: none;
        }

        .profile-menu ul li a:hover {
            text-decoration: underline;
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
            <img src="img/superview.jpg" alt="User Image">
            <h1>Vote Results</h1>
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

<?php if (!empty($vote_results)): ?>
            <?php foreach ($vote_results as $meeting_id => $vote): ?>
                <div class="vote-result">
                    <h2><?php echo htmlspecialchars($vote['title']); ?></h2>
                    <table>
                        <tr>
                            <th>Candidate</th>
                            <th>Votes</th>
                            <th>Percentage</th>
                        </tr>
                        <?php foreach ($vote['candidates'] as $candidate): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidate['name']); ?></td>
                                <td><?php echo htmlspecialchars($candidate['vote_count']); ?></td>
                                <td><?php echo number_format($candidate['percentage'], 2); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results available.</p>
        <?php endif; ?>
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
