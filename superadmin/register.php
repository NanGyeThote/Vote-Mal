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

// Registration logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = $_POST['voter_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO voters (voter_id, username, email, password, is_approved) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $voter_id, $username, $email, $password);

    // Execute statement
    if ($stmt->execute()) {
        echo "<script>document.getElementById('approvalModal').style.display='block';</script>";
    } else {
        echo "Error: " . $stmt->error;
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
    <link rel="stylesheet" href="css/teststyle.css">
    <link rel="icon" href="image/img1.jpg" type="image/jpg">
    <title>Register</title>
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
            height: 500px;
            background: #e3f2fd;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        #chk {
            display: none;
        }

        .signup {
            position: relative;
            width: 100%;
            height: 100%;
        }

        label {
            color: #01579b;
            font-size: 2em;
            display: flex;
            justify-content: center;
            margin: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: .3s ease-in-out;
        }

        a {
            color: #0288d1;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        h2 {
            text-align: center;
            font-size: 1.8em;
            color: #01579b;
            font-weight: bold;
            cursor: pointer;
            transition: .5s ease-in-out;
        }

        input {
            width: 70%;
            height: 25px;
            background: #ffffff;
            display: flex;
            margin: 15px auto;
            padding: 10px;
            border: 1px solid #90caf9;
            outline: none;
            border-radius: 5px;
        }

        button {
            width: 70%;
            height: 40px;
            margin: 20px auto;
            display: block;
            color: #ffffff;
            background: #0288d1;
            font-size: 1em;
            font-weight: bold;
            outline: none;
            border: none;
            border-radius: 5px;
            transition: .3s ease-in-out;
            cursor: pointer;
        }

        button:hover {
            background: #0277bd;
        }

        .login {
            height: 460px;
            background: #ffffff;
            border-radius: 60% / 10%;
            transform: translateY(-180px);
            transition: .8s ease-in-out;
        }

        .login label {
            color: #01579b;
            transform: scale(.6);
        }

        #chk:checked ~ .login {
            transform: translateY(-500px);
        }

        #chk:checked ~ .login label {
            transform: scale(1);
        }

        #chk:checked ~ .signup label {
            transform: scale(.6);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.5s;
        }

        .modal-content {
            background-color: #f1f8e9;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #8bc34a;
            width: 80%;
            max-width: 500px;
            animation: slideIn 0.5s;
            border-radius: 10px;
        }

        .close {
            color: #4caf50;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #1b5e20;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .error-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #90caf9;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 1.2em;
            text-align: center;
            z-index: 9999;
            width: 80%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Online Voting System</h2>
        </div>
        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">
            <div class="signup">
            <form action="register.php" method="POST" onsubmit="return validatePassword()">
                <label for="chk" aria-hidden="true">Register</label>
                <input type="text" name="voter_id" placeholder="Voter ID" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>

            <!-- Centered Error Message Box -->
            <div id="passwordError" class="error-box">
                Password must be 8 characters long, start with an uppercase letter, and contain at least 1 special character.
            </div>

            </div>
            <div class="login">
                <form action="login.php" method="POST">
                    <label for="chk" aria-hidden="true">Login</label>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Registration Successful!</h2>
            <p>You have been successfully registered. You can now <a href="register.php">login</a>.</p>
        </div>
    </div>
    
    <div id="approvalModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Registration Pending Approval</h2>
            <p>Your registration is pending approval. Please wait for admin approval.</p>
        </div>
    </div>

    <script>
        // Modal Logic
        var modal = document.getElementById("approvalModal");
        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script>
        // Get the modal
        var modal = document.getElementById("successModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        function validatePassword() {
            var password = document.getElementById("password").value;
            var passwordError = document.getElementById("passwordError");
            
            // Regular expression for password: 8 characters long, starts with uppercase, and contains at least one special character
            var passwordRegex = /^[A-Z](?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{7,}$/;
            
            if (!passwordRegex.test(password)) {
                // Show the error box
                passwordError.style.display = "block";

                // Hide the error box after 3 seconds
                setTimeout(function() {
                    passwordError.style.display = "none";
                }, 3000);

                return false;  // Prevent form submission
            } else {
                passwordError.style.display = "none";
                return true;  // Allow form submission
            }
        }
    </script>
</body>
</html>