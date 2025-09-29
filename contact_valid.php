<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$error = "";

// ===== VALIDATION =====
if (!isset($_POST['name']) || trim($_POST['name']) === "" || !preg_match('/^[a-zA-Z\s]+$/', $_POST['name'])) {
    $error .= "Enter a valid Name\n";
}
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $error .= "Enter a valid Email Id\n";
}
if (!isset($_POST['phone']) || !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
    $error .= "Enter a valid 10-digit Phone Number\n";
}
if (!isset($_POST['message']) || trim($_POST['message']) === "") {
    $error .= "Message is required\n";
}

// ===== MAIL SEND =====
if ($error === "") {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "enquiryformnetcom@gmail.com";  // Your Gmail
        $mail->Password   = "wtglpiuuhfqjgfdu";             // Gmail App Password
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;

        $mail->setFrom("sales.suyashautomation@gmail.com", "Website Enquiry");
        $mail->addAddress("sales.suyashautomation@gmail.com"); // Admin Email

        $mail->isHTML(true);
        $mail->Subject = "Suyash Automation & Controls - New Enquiry";
        $mail->Body = "<h2 style='color:#C50B33;'>Request For Contact Form</h2>
            <table border='1' cellpadding='8'>
                <tr><td><b>Full Name:</b></td><td>" . htmlspecialchars($_POST['name']) . "</td></tr>
                <tr><td><b>Email:</b></td><td>" . htmlspecialchars($_POST['email']) . "</td></tr>
                <tr><td><b>Phone:</b></td><td>" . htmlspecialchars($_POST['phone']) . "</td></tr>
                <tr><td><b>Message:</b></td><td>" . nl2br(htmlspecialchars($_POST['message'])) . "</td></tr>
            </table>";

        if ($mail->send()) {
            // AUTO REPLY
            $mail->clearAddresses();
            $mail->addAddress($_POST['email']);
            $mail->isHTML(false);
            $mail->Subject = "Thank You for Contacting Us";
            $mail->Body = "Hello " . htmlspecialchars($_POST['name']) . ",\n\nThank you for contacting us. We will get back to you shortly.\n\nRegards,\nSuyash Automation & Controls";
            $mail->send();

            echo "sent";
        } else {
            echo "Mail sending failed.";
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else {
    echo $error;
}
?>
