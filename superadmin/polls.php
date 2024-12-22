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

// SQL Query to join candidates with votes, excluding candidates with IDs 1 and 2
$sql = "
SELECT 
    candidates.id AS candidate_id,
    candidates.name AS candidate_name,
    candidates.photo,
    candidates.age,
    candidates.position,
    candidates.description AS candidate_description,
    votes.title AS vote_title,
    votes.meeting_id,
    votes.description AS vote_description
FROM 
    candidates
INNER JOIN 
    votes 
ON 
    candidates.meeting_id = votes.meeting_id
WHERE 
    candidates.id NOT IN (1, 2)
ORDER BY 
    votes.id, candidates.id
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <link rel="stylesheet" href="css/polls.css">
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
        <img src="img/superpoll.jpg" alt="Admin Image">
        <h1>All Voting Polls' Informations</h1>
    </div>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span>Home</span>
        <span>About</span>
        <span>Content</span>
    </div>
    <?php if ($result->num_rows > 0) { ?>
    <table>
        <tr>
            <th>Candidate ID</th>
            <th>Name</th>
            <th>Photo</th>
            <th>Age</th>
            <th>Position</th>
            <th>Description</th>
            <th>Vote Title</th>
            <th>Meeting ID</th>
            <th>Vote Description</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['candidate_id']); ?></td>
                <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                <td>
                    <?php if (!empty($row['photo'])) { ?>
                        <img src="img/<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo of <?php echo htmlspecialchars($row['candidate_name']); ?>" class="candidate-photo">
                    <?php } else { ?>
                        N/A
                    <?php } ?>
                </td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['position']); ?></td>
                <td><?php echo htmlspecialchars($row['candidate_description']); ?></td>
                <td><?php echo htmlspecialchars($row['vote_title']); ?></td>
                <td><?php echo htmlspecialchars($row['meeting_id']); ?></td>
                <td><?php echo htmlspecialchars($row['vote_description']); ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
        <div class="no-data">
            <p>No candidates available at the moment.</p>
        </div>
    <?php } ?>
    <br><hr><br>
    <h1>Current Votes</h1>
<ul class="current-votes">
    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "voting_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to select votes, excluding votes with IDs 1 and 2
    $sql = "SELECT id, title, meeting_id FROM votes WHERE id NOT IN (1, 2)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>
                    <a href='manage_candidates.php?vote_id=" . $row["id"] . "'>" . htmlspecialchars($row["title"]) . "</a>
                    <span>Meeting ID: " . htmlspecialchars($row["meeting_id"]) . "</span>
                    <div class='actions'>
                        <form method='POST' action='delete_vote.php' style='display:inline;'>
                            <input type='hidden' name='vote_id' value='" . htmlspecialchars($row["id"]) . "'>
                            <button type='submit' class='delete-btn'>Delete</button>
                        </form>
                    </div>
                </li>";
        }
    } else {
        echo "<li>No votes available</li>";
    }

    $conn->close();
    ?>
</ul>

<br><hr><br>
<h1>Candidates List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Meeting ID</th>
            <th>Photo</th>
            <th>Age</th>
            <th>Position</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // Replace with your DB username
        $password = ""; // Replace with your DB password
        $dbname = "voting_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch data from candidates table, excluding candidates with IDs 1 and 2
        $sql = "SELECT * FROM candidates WHERE id NOT IN (1, 2)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["id"]) . "</td>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["meeting_id"]) . "</td>
                    <td><img src='img/" . htmlspecialchars($row["photo"]) . "' alt='Photo of " . htmlspecialchars($row["name"]) . "' class='candidate-photo'></td>
                    <td>" . htmlspecialchars($row["age"]) . "</td>
                    <td>" . htmlspecialchars($row["position"]) . "</td>
                    <td>" . htmlspecialchars($row["description"]) . "</td>
                    <td><form action='delete_candidate.php' method='post'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                        <input type='submit' value='Delete' class='delete-btn'>
                    </form></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='8' class='no-data'>No candidates found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <a href="dashboard.php" class="button secondary">Back to Dashboard</a>
    
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
<?php
$conn->close();
?>
