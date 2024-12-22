<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "voting_db";  // Use the correct database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the OTP from the user input
    $entered_otp = $conn->real_escape_string($_POST['otp']);

    // Get the OTP stored in the session
    $session_otp = $_SESSION['otp'];
    $username = $_SESSION['username'];

    if ($entered_otp == $session_otp) {
        // OTP is correct, update user status to 'active'
        $query = "UPDATE users SET status = 'active' WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect to the main page after successful verification
            header('Location: index.php');
            exit();
        } else {
            echo "Error updating user status.";
        }
    } else {
        echo "Invalid OTP. Please try again.";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/teststyle.css">
    <link rel="icon" href="image/img1.jpg" type="image/jpg">
    <title>OTP Verification</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Body Styling */
        body {
            background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container */
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }

        /* Header Styling */
        .header {
            background: linear-gradient(135deg, #3494e6, #ec6ead);
            padding: 20px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            color: white;
        }

        .header h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 0;
        }

        /* Main Form Styling */
        .main {
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, #3494e6, #ec6ead);
        }

        .main form {
            width: 100%;
        }

        .main label {
            display: block;
            font-size: 15px;
            margin-bottom: 10px;
            color: #573b8a;
            font-weight: bold;
        }

        .main input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .main input[type="text"]:focus {
            border-color: #00a2ff;
            outline: none;
        }

        .main button {
            width: 60%;
            height: 40px;
            margin: 10px auto;
            justify-content: center;
            display: block;
            color: #fff;
            background: #573b8a;
            font: 1em;
            font-weight: bold;
            margin-top: 20px;
            outline: none;
            border: none;
            border-radius: 5px;
            transition: .2s ease-in;
            cursor: pointer;
        }

        .main button:hover {
            background: #6d44b8;
        }

        /* Animation */
        .animate__animated {
            animation-duration: 1s;
        }

        .animate__bounceIn {
            animation-name: bounceIn;
            animation-timing-function: ease-in;
        }

        @keyframes bounceIn {
            0%, 20%, 40%, 60%, 80%, 100% {
                transition-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
            }
            0% {
                opacity: 0;
                transform: scale3d(0.3, 0.3, 0.3);
            }
            20% {
                transform: scale3d(1.1, 1.1, 1.1);
            }
            40% {
                transform: scale3d(0.9, 0.9, 0.9);
            }
            60% {
                opacity: 1;
                transform: scale3d(1.03, 1.03, 1.03);
            }
            80% {
                transform: scale3d(0.97, 0.97, 0.97);
            }
            100% {
                opacity: 1;
                transform: scale3d(1, 1, 1);
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 class="animate__animated animate__bounceIn">OTP Verification</h2>
        </div>
        <div class="main">
            <form action="otp_verification.php" method="POST">
                <label for="otp">Enter OTP</label>
                <input type="text" id="otp" name="otp" placeholder="Enter the OTP" required>
                <button type="submit">Verify OTP</button>
            </form>
        </div>
    </div>
</body>
</html>
