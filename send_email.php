
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Error: Only POST requests are allowed.";
    exit;
}

if (!isset($_POST['firstName']) || !isset($_POST['email'])) {
    http_response_code(400);
    echo "Error: Missing required fields.";
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $state = htmlspecialchars($_POST['state']);
    $message = htmlspecialchars($_POST['message']);
    
    $to = "aleksa700@outlook.com";
    $subject = "New Contact Form Submission";
    
    $body = "First Name: $firstName\n";
    $body .= "Last Name: $lastName\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n";
    $body .= "State: $state\n";
    $body .= "Message:\n$message\n";

    // File validation
    $allowedTypes = ['application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    if (!empty($_FILES['file']['name'])) {
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = mime_content_type($fileTmp);

        if (!in_array($fileType, $allowedTypes)) {
            die("Invalid file format. Only PDF and XLS files are allowed.");
        }

        // Email with attachment
        $boundary = md5(time());
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $emailContent = "--$boundary\r\n";
        $emailContent .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $emailContent .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $emailContent .= $body . "\r\n";

        // Attach file
        $fileContent = chunk_split(base64_encode(file_get_contents($fileTmp)));
        $emailContent .= "--$boundary\r\n";
        $emailContent .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
        $emailContent .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
        $emailContent .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $emailContent .= $fileContent . "\r\n";
        $emailContent .= "--$boundary--";

        // Send email with attachment
        if (mail($to, $subject, $emailContent, $headers)) {
            echo "Your message has been sent!";
        } else {
            echo "Error sending your message.";
        }
    } else {
        // Email without attachment
        $headers = "From: $email\r\n";
        if (mail($to, $subject, $body, $headers)) {
            echo "Your message has been sent!";
        } else {
            echo "Error sending your message.";
        }
    }

    // Send confirmation to the user
    $confirmSubject = "Thank you for contacting us!";
    $confirmMessage = "Thank you for reaching out to us. We will get back to you as soon as possible.";
    $confirmHeaders = "From: aleksa700@outlook.com\r\n";
    mail($email, $confirmSubject, $confirmMessage, $confirmHeaders);
}
?>
