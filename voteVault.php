<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Mal</title>
    <link rel="stylesheet" href="css/create_vote.css">
    <link rel="icon" href="image/img1.jpg" type="image/jpg">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Jost', sans-serif;
            background: linear-gradient(to bottom, #a7c7e7, #d9e7f5);
        }

        .navbar {
            background-color: #e3f2fd; /* Light icy blue */
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 24px;
            color: #01579b; /* Darker icy blue */
        }

        .navbar-center h2 {
            color: #01579b; /* Darker icy blue text */
            margin: 0 15px;
            font-size: 20px;
        }

        .profile-menu-wrapper {
            display: flex;
            align-items: center;
            position: relative;
        }

        .profile-toggle-btn, .admin-toggle-btn, .hamburger-btn {
            background: none;
            border: none;
            color: #01579b; /* Darker icy blue */
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            margin-left: 10px;
        }

        .profile-menu, .admin-menu, .hamburger-menu {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: #ffffff; /* White background */
            border: 1px solid #d1e0e0; /* Light icy border */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-menu ul, .admin-menu ul, .hamburger-menu ul {
            list-style: none;
            margin: 0;
            padding: 10px 0;
        }

        .profile-menu ul li, .admin-menu ul li, .hamburger-menu ul li {
            padding: 10px 20px;
        }

        .profile-menu ul li a, .admin-menu ul li a, .hamburger-menu ul li a {
            color: #0288d1; /* Icy blue links */
            text-decoration: none;
        }

        .hamburger-btn {
            font-size: 20px;
            display: none; /* Hide by default, show on mobile view */
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: block;
            }

            .navbar-center h2 {
                display: none;
            }
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            background-image: url('ice-background.jpg'); /* Icy background image */
            background-size: cover;
            background-position: center;
            margin-top: 70px; /* Adjust margin to ensure content is below navbar */
            padding-top: 20px; /* Add padding-top to give space from navbar */
        }

        .left-section {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white for frosty look */
            color: #333;
            padding: 40px;
            max-width: 60%;
            text-align: center; /* Center text inside the section */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .left-section h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #01579b; /* Darker icy blue text */
        }

        .left-section p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #333;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .organizer-btn, .participant-btn {
            background-color: #01579b; /* Darker icy blue buttons */
            color: white;
            padding: 15px 30px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px; /* Rounded corners */
        }

        .organizer-btn:hover, .participant-btn:hover {
            background-color: #0277bd; /* Slightly lighter blue on hover */
        }

        .quote-btn {
            background-color: #8ecae6; /* Light blue button */
            color: white;
            padding: 15px 30px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px; /* Rounded corners */
        }

        .quote-btn:hover {
            background-color: #7ac2e0; /* Darker blue on hover */
        }

        h1 {
            margin-bottom: 20px;
            color: #01579b; /* Darker icy blue for headers */
            font-size: 2em;
        }

        h2 {
            margin-bottom: 20px;
            color: #01579b; /* Darker icy blue for subheaders */
            font-size: 15px;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">Vote Mal</div>
    <div class="navbar-center">
        <h2>Online Voting System</h2>
    </div>
    <div class="profile-menu-wrapper">
        <button class="profile-toggle-btn" onclick="toggleProfileMenu('profileMenu')">
            <span class="profile-name">Voter</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <button class="admin-toggle-btn" onclick="toggleProfileMenu('adminMenu')">
            <span class="admin-name">Admin</span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="profile-menu" id="profileMenu">
            <ul>
                <li><a href="user/register.php">Register</a></li>
                <li><a href="user/login.php">Login</a></li>
            </ul>
        </div>
        <div class="admin-menu" id="adminMenu">
            <ul>
                <li><a href="admin/register.php">Register</a></li>
                <li><a href="admin/login.php">Login</a></li>
            </ul>
        </div>
        <button class="hamburger-btn" onclick="toggleHamburgerMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="hamburger-menu" id="hamburgerMenu">
            <ul>
                <li><a href="#">Register</a></li>
                <li><a href="#">Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="left-section">
        <h1>Online Voting System</h1>
        <p>VoteVault is a web-based online voting system that will help you manage your candidates and submit votes easily and securely.</p>
        <div class="buttons">
            <button class="organizer-btn" onclick="redirectTo('admin/register.php')">Try as Admin</button>
            <button class="participant-btn" onclick="redirectTo('user/register.php')">Try as Voter</button>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    function toggleProfileMenu(menuId) {
        const profileMenu = document.getElementById("profileMenu");
        const adminMenu = document.getElementById("adminMenu");
        if (menuId === 'profileMenu') {
            profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
            adminMenu.style.display = "none";
        } else {
            adminMenu.style.display = adminMenu.style.display === "block" ? "none" : "block";
            profileMenu.style.display = "none";
        }
    }

    function toggleHamburgerMenu() {
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        hamburgerMenu.style.display = hamburgerMenu.style.display === "block" ? "none" : "block";
    }

    function redirectTo(url) {
        window.location.href = url;
    }
</script>
</body>
</html>
