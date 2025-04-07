<?php
$to = "info@renertiaco.com";
$subject = "Test Email from Bluehost";
$message = "Hello, this is a test email from your Bluehost server.";
$headers = "From: no-reply@yourdomain.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sent successfully!";
} else {
    echo "Mail function is not working!";
}
?>
