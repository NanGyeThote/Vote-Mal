<?php
session_start(); // Start the session

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

// Initialize error message
$error_message = "";

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, username, password FROM superadmin WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION['superadmin_id'] = $id;
        $_SESSION['username'] = $username;

        // Redirect to user dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid email or password";
    }

    $stmt->close();
}

$conn->close();

// Store error message in session if any
if ($error_message) {
    $_SESSION['error_message'] = $error_message;
    header("Location: login.php"); // Redirect back to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/teststyle.css">
    <link rel="icon" href="image/img1.jpg" type="image/jpg">
    <title>Login</title>
    <style>
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

        .main {
            width: 350px;
            background: #e3f2fd;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            text-align: center;
            position: relative;
        }

        h2 {
            font-size: 1.8em;
            color: #01579b;
            font-weight: bold;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            height: 40px;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #90caf9;
            border-radius: 5px;
            outline: none;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            height: 45px;
            color: #ffffff;
            background: #0288d1;
            font-size: 1em;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
            margin-bottom: 10px;
        }

        button:hover {
            background: #0277bd;
        }

        a {
            color: #0288d1;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            display: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    <div class="main">
        <h2>Login</h2>
        <?php
        // Display error message if set
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message" id="error-message">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="#">Forgot Password?</a>
    </div>

    <script>
        // Display the error message for 3 seconds if present
        window.onload = function() {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 3000);
            }
        };
    </script>
</body>
</html>
