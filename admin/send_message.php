<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = 'hponekhantnaing@gmail.com';
    $subject = 'Support Request from ' . $name;
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $emailBody = "<html><body>";
    $emailBody .= "<h2>Support Request</h2>";
    $emailBody .= "<p><strong>Name:</strong> $name</p>";
    $emailBody .= "<p><strong>Email:</strong> $email</p>";
    $emailBody .= "<p><strong>Message:</strong><br>$message</p>";
    $emailBody .= "</body></html>";

    if (mail($to, $subject, $emailBody, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
    }
}
?>
