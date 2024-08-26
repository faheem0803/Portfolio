ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<?php

// Replace this with your own email address
$to = 'faheem0803@gmail.com';

function url() {
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $contact_message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    if ($subject == '') {
        $subject = "Contact Form Submission";
    }

    // Set Message
    $message = "Email from: " . htmlspecialchars($name) . "<br />";
    $message .= "Email address: " . htmlspecialchars($email) . "<br />";
    $message .= "Message: <br />";
    $message .= nl2br(htmlspecialchars($contact_message));
    $message .= "<br /> ----- <br /> This email was sent from your site " . url() . " contact form. <br />";

    // Set From: header
    $from =  htmlspecialchars($name) . " <" . htmlspecialchars($email) . ">";

    // Email Headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // For Windows server configuration
    ini_set("sendmail_from", $to);

    // Send email
    if (mail($to, $subject, $message, $headers)) {
        echo "OK";
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>
